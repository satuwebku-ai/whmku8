<?php

namespace Tests\Feature;

use App\Models\HostingAccount;
use App\Models\Product;
use App\Models\ProductGroup;
use App\Models\Server;
use App\Models\ServerGroup;
use App\Models\ServiceLifecycleLog;
use App\Services\Provisioning\ServerSelector;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/** Alur diagram: Product -> Server Group -> Server, serta log Provisioning & Suspend/Unsuspend/Terminate. */
class HostingWorkflowErdTest extends TestCase
{
    use RefreshDatabase;

    private function server(ServerGroup $g, string $name, array $extra = []): Server
    {
        return Server::create(array_merge([
            'name' => $name, 'panel' => 'cpanel', 'hostname' => "$name.test", 'port' => 2087,
            'api_username' => 'root', 'api_token' => 'x', 'is_active' => true,
            'server_group_id' => $g->id,
        ], $extra));
    }

    private function product(Server $s): Product
    {
        $cat = ProductGroup::create(['name' => 'Hosting', 'slug' => 'hosting', 'type' => 'hosting']);

        return Product::create([
            'product_category_id' => $cat->id, 'name' => 'Basic', 'slug' => 'basic',
            'server_id' => $s->id, 'price_monthly' => 50000,
        ]);
    }

    public function test_selector_memilih_server_terkosong_dan_melewati_yang_penuh_atau_maintenance(): void
    {
        $g = ServerGroup::create(['name' => 'ID', 'slug' => 'id', 'location' => 'ID', 'priority' => 1]);
        $full = $this->server($g, 'full', ['max_accounts' => 1]);
        $maint = $this->server($g, 'maint', ['status' => 'maintenance']);
        $busy = $this->server($g, 'busy');
        $idle = $this->server($g, 'idle');

        HostingAccount::factory()->active()->create(['server_id' => $full->id]);
        HostingAccount::factory()->active()->count(2)->create(['server_id' => $busy->id]);

        $this->assertSame('full', $full->fresh()->effectiveStatus());
        $picked = app(ServerSelector::class)->forProduct($this->product($busy));

        $this->assertSame($idle->id, $picked->id);
        $this->assertNotSame($maint->id, $picked->id);
    }

    public function test_group_nonaktif_tidak_dipakai(): void
    {
        $g = ServerGroup::create(['name' => 'SG', 'slug' => 'sg', 'is_active' => false]);
        $s = $this->server($g, 'sg1');

        $this->assertNull(app(ServerSelector::class)->pickFromGroup($g->id));
        $this->assertSame($s->id, $s->id);
    }

    public function test_log_suspend_unsuspend_terminate_tercermin_ke_tabel_terstruktur(): void
    {
        $acc = HostingAccount::factory()->active()->create();
        $acc->logs()->create(['action' => 'suspend', 'message' => 'Invoice jatuh tempo']);
        $acc->logs()->create(['action' => 'upgrade', 'message' => 'bukan lifecycle']);
        $acc->logs()->create(['action' => 'unsuspend', 'message' => 'Dibayar']);

        $this->assertSame(['suspend', 'unsuspend'], ServiceLifecycleLog::orderBy('id')->pluck('event')->all());
        $this->assertSame('overdue', ServiceLifecycleLog::where('event', 'suspend')->value('reason'));
    }

    public function test_riwayat_provisioning_dicatat_per_percobaan(): void
    {
        $acc = HostingAccount::factory()->create();
        $acc->update(['provision_status' => 'provisioning']);
        $acc->update(['provision_status' => 'failed', 'provision_message' => 'timeout']);
        $acc->update(['provision_status' => 'provisioning']);
        $acc->update(['provision_status' => 'provisioned', 'provision_message' => 'ok']);

        $this->assertSame(['failed', 'success'], $acc->provisioningJobs()->orderBy('id')->pluck('status')->all());
    }

    public function test_admin_server_group_crud_dan_blokir_hapus_bila_ada_server(): void
    {
        $admin = \App\Models\Admin::factory()->create();
        $this->actingAs($admin, 'admin');

        $this->post(route('admin.server-groups.store'), ['name' => 'SG Singapore', 'location' => 'SG', 'priority' => 5, 'is_active' => 1])
            ->assertRedirect(route('admin.server-groups.index'));
        $g = ServerGroup::where('name', 'SG Singapore')->firstOrFail();
        $this->assertSame('sg-singapore', $g->slug);

        $this->get(route('admin.server-groups.index'))->assertOk()->assertSee('SG Singapore');
        $this->get(route('admin.server-groups.edit', $g))->assertOk();

        $this->server($g, 'sg1');
        $this->delete(route('admin.server-groups.destroy', $g))->assertSessionHas('error');
        $this->assertDatabaseHas('server_groups', ['id' => $g->id]);
    }

    public function test_alasan_suspend_divalidasi_per_jenis_aksi(): void
    {
        $admin = \App\Models\Admin::factory()->create();
        $this->actingAs($admin, 'admin');
        $acc = HostingAccount::factory()->active()->create();

        // 'payment' hanya sah untuk unsuspend, bukan suspend.
        $this->post(route('admin.hosting-accounts.suspend', $acc), ['reason' => 'payment'])
            ->assertSessionHasErrors('reason');
        $this->assertSame(0, ServiceLifecycleLog::count());
    }

    public function test_server_otomatis_terisi_type_dan_module(): void
    {
        $g = ServerGroup::create(['name' => 'ID', 'slug' => 'id']);
        $shared = $this->server($g, 'h1');
        $cloud = Server::create([
            'name' => 'Cloud', 'panel' => 'vps', 'vps_provider' => 'idcloudhost',
            'hostname' => '', 'api_username' => '', 'api_token' => 'x', 'is_active' => true,
        ]);

        $this->assertSame('shared', $shared->fresh()->serverType->key);
        $this->assertSame('cpanel', $shared->fresh()->module->key);
        $this->assertSame('vps', $cloud->fresh()->serverType->key);
        $this->assertSame('vps:idcloudhost', $cloud->fresh()->module->key);
    }

    public function test_package_produk_menyinkronkan_nama_plan_dan_diblokir_hapus_saat_dipakai(): void
    {
        $admin = \App\Models\Admin::factory()->create();
        $this->actingAs($admin, 'admin');
        $g = ServerGroup::create(['name' => 'ID', 'slug' => 'id']);
        $server = $this->server($g, 'h1');

        $this->post(route('admin.server-packages.store'), ['server_id' => $server->id, 'name' => 'plan_pro', 'disk_limit_mb' => 0, 'is_active' => 1])
            ->assertRedirect(route('admin.server-packages.index'));
        $pkg = \App\Models\ServerPackage::firstOrFail();

        // nama sama di server yang sama ditolak
        $this->post(route('admin.server-packages.store'), ['server_id' => $server->id, 'name' => 'plan_pro'])
            ->assertSessionHasErrors('name');

        $product = $this->product($server);
        $product->update(['server_package_id' => $pkg->id, 'panel_package' => 'lama']);

        $this->put(route('admin.server-packages.update', $pkg), ['server_id' => $server->id, 'name' => 'plan_pro_v2', 'is_active' => 1]);
        $this->assertSame('plan_pro_v2', $product->fresh()->panel_package);

        $this->delete(route('admin.server-packages.destroy', $pkg))->assertSessionHas('error');
        $this->assertDatabaseHas('server_packages', ['id' => $pkg->id]);
    }

    public function test_sinkronisasi_package_dari_panel_menambah_memperbarui_dan_melaporkan_yang_hilang(): void
    {
        $g = ServerGroup::create(['name' => 'ID', 'slug' => 'id']);
        $server = $this->server($g, 'h1');
        $old = \App\Models\ServerPackage::create(['server_id' => $server->id, 'name' => 'lama', 'cpu_limit' => 2, 'ram_limit_mb' => 2048]);
        $keep = \App\Models\ServerPackage::create(['server_id' => $server->id, 'name' => 'pro', 'disk_limit_mb' => 1, 'cpu_limit' => 4, 'is_active' => false]);

        $panel = new class {
            public function listPackages(): array
            {
                return ['success' => true, 'message' => 'OK', 'packages' => ['pro', 'baru'], 'raw' => [
                    ['name' => 'pro', 'QUOTA' => '5000', 'BWLIMIT' => 'unlimited'],
                    ['name' => 'baru', 'QUOTA' => 'unlimited', 'BWLIMIT' => '10240'],
                ]];
            }
        };

        $r = app(\App\Services\Hosting\ServerPackageSyncService::class)->sync($server, $panel);

        $this->assertTrue($r['success']);
        $this->assertSame(1, $r['created']);
        $this->assertSame(1, $r['updated']);
        $this->assertSame(['lama'], $r['missing']);

        $keep->refresh();
        $this->assertSame(5000, $keep->disk_limit_mb);
        $this->assertSame(0, $keep->bandwidth_limit_mb);
        $this->assertSame(4, $keep->cpu_limit);          // CPU tidak ditimpa
        $this->assertFalse($keep->is_active);            // status tidak ditimpa
        $this->assertSame(0, \App\Models\ServerPackage::where('name', 'baru')->value('disk_limit_mb'));
        $this->assertDatabaseHas('server_packages', ['id' => $old->id]); // tidak dihapus
    }

    public function test_sinkronisasi_gagal_dilaporkan_tanpa_mengubah_data(): void
    {
        $g = ServerGroup::create(['name' => 'ID', 'slug' => 'id']);
        $server = $this->server($g, 'h1');
        $panel = new class {
            public function listPackages(): array
            {
                return ['success' => false, 'message' => 'Token ditolak', 'packages' => [], 'raw' => []];
            }
        };

        $r = app(\App\Services\Hosting\ServerPackageSyncService::class)->sync($server, $panel);

        $this->assertFalse($r['success']);
        $this->assertSame('Token ditolak', $r['message']);
        $this->assertSame(0, \App\Models\ServerPackage::count());
    }

    public function test_parse_limit(): void
    {
        $p = \App\Services\Hosting\ServerPackageSyncService::class;
        $this->assertSame(0, $p::parseLimit('Unlimited'));
        $this->assertSame(1024, $p::parseLimit('1024'));
        $this->assertNull($p::parseLimit(null));
        $this->assertNull($p::parseLimit('abc'));
    }
}
