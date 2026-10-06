<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Client;
use App\Models\HostingAccount;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductGroup;
use App\Models\Server;
use App\Models\ServerGroup;
use App\Services\Provisioning\ProvisioningService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

/**
 * Alur diagram 5 -> 12, berurutan, dengan WHM dipalsukan lewat Http::fake:
 * Order -> Invoice -> Payment -> Provisioning -> Service -> Suspend -> Unsuspend -> Terminate.
 */
class HostingLifecycleFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_alur_lengkap_order_sampai_terminate(): void
    {
        Queue::fake();
        Http::fake(['*/json-api/*' => Http::response([
            'metadata' => ['result' => 1, 'reason' => 'OK'],
            'data' => ['nameserver' => 'ns1.test', 'nameserver2' => 'ns2.test'],
        ])]);

        // 1-4. Product, Server Group, Server, Package
        $group = ServerGroup::create(['name' => 'ID', 'slug' => 'id', 'location' => 'ID', 'priority' => 1]);
        $mk = fn (string $n, array $x = []) => Server::create(array_merge([
            'name' => $n, 'panel' => 'cpanel', 'hostname' => "$n.test", 'port' => 2087,
            'api_username' => 'root', 'api_token' => 'x', 'is_active' => true, 'server_group_id' => $group->id,
        ], $x));
        $full = $mk('full', ['max_accounts' => 1]);
        $idle = $mk('idle');
        HostingAccount::factory()->active()->create(['server_id' => $full->id]);

        $cat = ProductGroup::create(['name' => 'Hosting', 'slug' => 'hosting', 'type' => 'hosting']);
        $pkg = \App\Models\ServerPackage::create(['server_id' => $full->id, 'name' => 'plan_pro']);
        $product = Product::create([
            'product_category_id' => $cat->id, 'name' => 'Pro', 'slug' => 'pro', 'price_monthly' => 100000,
            'server_id' => $full->id, 'server_package_id' => $pkg->id, 'panel_package' => 'plan_pro',
        ]);

        // 5. Order (akun layanan belum punya server)
        $client = Client::create(['name' => 'Budi', 'email' => 'budi@example.test']);
        $account = HostingAccount::factory()->create([
            'client_id' => $client->id, 'product_id' => $product->id, 'server_id' => null,
            'domain' => 'budi.test', 'package' => 'plan_pro', 'status' => 'pending',
        ]);
        $order = Order::create([
            'client_id' => $client->id, 'product_id' => $product->id, 'hosting_account_id' => $account->id,
            'product_name' => 'Pro', 'order_type' => 'hosting', 'amount' => 100000, 'status' => 'pending',
        ]);
        $order->markPendingPayment('siap dibayar');

        // 6. Invoice
        $invoice = Invoice::withoutEvents(fn () => Invoice::create([
            'client_id' => $client->id, 'order_id' => $order->id, 'invoice_number' => 'INV-FLOW-1',
            'amount' => 100000, 'tax' => 0, 'discount' => 0, 'total' => 100000,
            'issue_date' => now(), 'due_date' => now()->addDays(7),
        ]));
        InvoiceItem::create(['invoice_id' => $invoice->id, 'order_id' => $order->id, 'description' => 'Pro', 'amount' => 100000]);

        // 7. Payment
        $payment = Payment::create([
            'reference' => 'PAY-FLOW-1', 'invoice_id' => $invoice->id, 'client_id' => $client->id,
            'amount' => 100000, 'fee' => 0, 'total' => 100000, 'currency' => 'IDR',
        ]);
        $payment->markAsPaid('Test-Gateway');
        $this->assertSame('paid', $invoice->fresh()->status);

        // 8-9. Provisioning -> Service
        app(ProvisioningService::class)->provisionInvoice($invoice->fresh());

        $account->refresh();
        $this->assertSame($idle->id, $account->server_id, 'server penuh harus dilewati, server idle dipilih');
        $this->assertSame('active', $account->status);
        $this->assertSame('provisioned', $account->provision_status);
        $this->assertNotEmpty($account->username);
        $this->assertSame('completed', $order->fresh()->status->value);
        $this->assertSame(['success'], $account->provisioningJobs()->pluck('status')->all());

        // 10-12. Suspend -> Unsuspend -> Terminate lewat admin, dengan alasan
        $this->actingAs(Admin::factory()->create(), 'admin');

        $this->post(route('admin.hosting-accounts.suspend', $account), ['reason' => 'overdue', 'note' => 'telat'])
            ->assertSessionHas('success');
        $this->assertSame('suspended', $account->fresh()->status);

        $this->post(route('admin.hosting-accounts.unsuspend', $account), ['reason' => 'payment'])
            ->assertSessionHas('success');
        $this->assertSame('active', $account->fresh()->status);

        $this->post(route('admin.hosting-accounts.terminate', $account), ['reason' => 'expired'])
            ->assertSessionHas('success');
        $this->assertSame('terminated', $account->fresh()->status);

        $logs = $account->lifecycleLogs()->orderBy('id')->get();
        $this->assertSame(['suspend', 'unsuspend', 'terminate'], $logs->pluck('event')->all());
        $this->assertSame(['overdue', 'payment', 'expired'], $logs->pluck('reason')->all());
        $this->assertSame(['success', 'success', 'success'], $logs->pluck('status')->all());
        $this->assertSame('telat', $logs[0]->note);

        // Server yang sudah terminate tidak lagi memakai kapasitas.
        $this->assertSame(0, $idle->fresh()->currentUsage());
    }

    public function test_suspend_gagal_di_panel_dicatat_sebagai_failed_dan_status_tidak_berubah(): void
    {
        Http::fake(['*/json-api/*' => Http::response(['metadata' => ['result' => 0, 'reason' => 'Account tidak ada']])]);
        $group = ServerGroup::create(['name' => 'ID', 'slug' => 'id']);
        $server = Server::create([
            'name' => 's1', 'panel' => 'cpanel', 'hostname' => 's1.test', 'port' => 2087,
            'api_username' => 'root', 'api_token' => 'x', 'is_active' => true, 'server_group_id' => $group->id,
        ]);
        $account = HostingAccount::factory()->active()->create(['server_id' => $server->id, 'username' => 'abc']);
        $this->actingAs(Admin::factory()->create(), 'admin');

        $this->post(route('admin.hosting-accounts.suspend', $account), ['reason' => 'abuse'])
            ->assertSessionHas('error');

        $this->assertSame('active', $account->fresh()->status);
        $log = $account->lifecycleLogs()->firstOrFail();
        $this->assertSame('failed', $log->status);
        $this->assertStringContainsString('Account tidak ada', (string) $log->note);
    }
}
