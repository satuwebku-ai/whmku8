<?php

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Models\Client;
use App\Models\HostingAccount;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductGroup;
use App\Models\Provisioning;
use App\Models\Server;
use App\Models\ServerGroup;
use App\Models\ServerPackage;
use App\Services\Provisioning\ProvisioningService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HostingWorkflowErDiagramTest extends TestCase
{
    use RefreshDatabase;

    public function test_server_group_package_product_service_and_provisioning_relations_match_the_workflow(): void
    {
        $group = ServerGroup::create([
            'name' => 'Jakarta Shared',
            'location' => 'Jakarta',
            'priority' => 1,
            'status' => 'active',
        ]);

        $server = Server::create([
            'name' => 'WHM Jakarta 01',
            'hostname' => 'whm01.example.test',
            'port' => 2087,
            'panel' => 'cpanel',
            'api_username' => 'root',
            'api_token' => 'test-token',
            'server_group_id' => $group->id,
            'verify_ssl' => false,
            'is_active' => true,
        ]);

        $package = ServerPackage::create([
            'server_id' => $server->id,
            'name' => 'cloud_hosting_pro',
            'disk_limit' => 30,
            'bandwidth_limit' => 500,
            'cpu_limit' => 2,
            'ram_limit' => 2048,
            'price' => 150000,
            'status' => 'active',
        ]);

        $category = ProductGroup::forceCreate([
            'name' => 'Hosting',
            'slug' => 'hosting-workflow',
            'type' => 'hosting',
            'is_active' => true,
        ]);

        $product = Product::create([
            'product_category_id' => $category->id,
            'name' => 'Hosting Pro',
            'slug' => 'hosting-pro-workflow',
            'server_id' => $server->id,
            'server_package_id' => $package->id,
            'panel_package' => $package->name,
            'domain_option' => 'optional',
            'price_monthly' => 150000,
        ]);

        $client = Client::factory()->create();
        $account = HostingAccount::factory()->create([
            'client_id' => $client->id,
            'product_id' => $product->id,
            'server_id' => $server->id,
            'server_package_id' => $package->id,
            'package' => $package->name,
        ]);
        $order = Order::create([
            'order_number' => 'ORD-ERD-001',
            'client_id' => $client->id,
            'product_id' => $product->id,
            'hosting_account_id' => $account->id,
            'product_name' => $product->name,
            'order_type' => 'hosting',
            'status' => OrderStatus::Paid,
        ]);
        $attempt = Provisioning::create([
            'order_id' => $order->id,
            'hosting_account_id' => $account->id,
            'server_id' => $server->id,
            'server_package_id' => $package->id,
            'attempt_number' => 1,
            'status' => 'succeeded',
            'message' => 'Akun hosting berhasil dibuat.',
            'started_at' => now()->subMinute(),
            'finished_at' => now(),
        ]);

        $this->assertSame('Jakarta Shared', $server->group->name);
        $this->assertSame('cloud_hosting_pro', $product->serverPackage->name);
        $this->assertSame($server->id, $package->server->id);
        $this->assertSame($package->id, $account->serverPackage->id);
        $this->assertSame($order->id, $attempt->order->id);
        $this->assertSame($attempt->id, $account->provisionings()->first()->id);
        $this->assertSame($attempt->id, $order->provisionings()->first()->id);
    }

    public function test_manual_fulfillment_is_recorded_when_order_has_no_target_server(): void
    {
        $account = HostingAccount::factory()->create();
        $order = Order::create([
            'order_number' => 'ORD-ERD-002',
            'client_id' => $account->client_id,
            'hosting_account_id' => $account->id,
            'product_name' => 'Hosting Manual',
            'order_type' => 'hosting',
            'status' => OrderStatus::Paid,
        ]);
        $invoice = Invoice::create([
            'invoice_number' => 'INV-ERD-002',
            'client_id' => $account->client_id,
            'order_id' => $order->id,
            'amount' => 100000,
            'status' => 'paid',
            'issue_date' => today(),
            'due_date' => today()->addDays(7),
        ]);

        app(ProvisioningService::class)->provisionInvoice($invoice);

        $this->assertDatabaseHas('provisionings', [
            'order_id' => $order->id,
            'hosting_account_id' => $account->id,
            'status' => 'manual',
            'attempt_number' => 0,
        ]);
        $this->assertSame('manual', $account->fresh()->provision_status);
        $this->assertSame(OrderStatus::Failed, $order->fresh()->status);
    }
}
