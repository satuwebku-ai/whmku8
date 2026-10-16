<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\CmsPage;
use App\Models\NavMenu;
use App\Models\Product;
use App\Models\ProductGroup;
use App\Models\ProductType;
use App\Support\LinkTargets;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Satu dropdown "Tujuan" untuk menu navbar: halaman CMS, jenis/kategori/produk,
 * halaman sistem, dan alamat ketikan -- semuanya lewat App\Support\LinkTargets.
 */
class LinkTargetsTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): Admin
    {
        return Admin::create([
            'username' => 'nav2', 'name' => 'Nav', 'email' => 'nav2@contoh.test',
            'password' => bcrypt('Rahasia-123'), 'role' => 'superadmin', 'is_active' => true,
        ]);
    }

    private function product(array $override = []): Product
    {
        $type = ProductType::where('slug', 'hosting')->firstOrFail();
        $group = ProductGroup::create(['name' => 'Shared', 'slug' => 'shared', 'product_type_id' => $type->id]);

        return Product::create(array_merge([
            'product_category_id' => $group->id, 'name' => 'Paket Basic', 'slug' => 'paket-basic',
        ], $override));
    }

    public function test_custom_route_and_garbage_targets(): void
    {
        $this->assertSame('/hosting', LinkTargets::url('custom', '/hosting'));
        $this->assertNull(LinkTargets::url('custom', ''));
        $this->assertSame(route('blog.index'), LinkTargets::url('route:blog.index'));
        $this->assertNull(LinkTargets::url('route:tidak.ada'));
        $this->assertNull(LinkTargets::url('route:#'));
        $this->assertNull(LinkTargets::url(''));
        $this->assertNull(LinkTargets::url('product:abc'));
        $this->assertNull(LinkTargets::url('product:999999'));
    }

    public function test_page_target_follows_slug_and_hides_drafts(): void
    {
        $page = CmsPage::create(['title' => 'Tentang Kami', 'slug' => 'tentang-kami', 'is_published' => true]);

        $this->assertSame(route('page.show', 'tentang-kami'), LinkTargets::url("page:{$page->id}"));

        $page->update(['slug' => 'profil']);
        $this->assertSame(route('page.show', 'profil'), LinkTargets::url("page:{$page->id}"));

        $page->update(['is_published' => false]);
        $this->assertNull(LinkTargets::url("page:{$page->id}"));
    }

    public function test_type_target_maps_by_kind_not_by_slug(): void
    {
        $hosting = ProductType::where('slug', 'hosting')->firstOrFail();
        $vps = ProductType::where('slug', 'vps')->firstOrFail();

        $this->assertSame(route('catalog.index'), LinkTargets::url("type:{$hosting->id}"));
        $this->assertSame(route('catalog.vps'), LinkTargets::url("type:{$vps->id}"));

        $vps->update(['slug' => 'cloud-server']);
        $this->assertSame(route('catalog.vps'), LinkTargets::url("type:{$vps->id}"));
    }

    public function test_product_target_follows_slug_and_disappears_when_inactive(): void
    {
        $product = $this->product();

        $url = LinkTargets::url("product:{$product->id}");
        $this->assertStringEndsWith('/hosting/shared/paket-basic', $url);

        $product->update(['slug' => 'paket-pro']);
        $this->assertStringEndsWith('/hosting/shared/paket-pro', LinkTargets::url("product:{$product->id}"));

        $product->update(['is_active' => false]);
        $this->assertNull(LinkTargets::url("product:{$product->id}"));
    }

    public function test_nav_menu_with_catalog_target_resolves_and_reports_its_form_value(): void
    {
        $product = $this->product();

        $menu = NavMenu::create([
            'label' => 'Basic', 'type' => 'url', 'target' => "product:{$product->id}",
            'is_active' => true, 'sort_order' => 1,
        ]);

        $this->assertStringEndsWith('/hosting/shared/paket-basic', $menu->resolved_url);
        $this->assertSame("product:{$product->id}", $menu->link_target);
        $this->assertSame('Paket Basic', $menu->destination_label);

        // Menu lama (tanpa target) tetap terbaca di dropdown tanpa migrasi data.
        $this->assertSame('route:blog.index', (new NavMenu(['type' => 'route', 'route_name' => 'blog.index']))->link_target);
        $this->assertSame('custom', (new NavMenu(['type' => 'url', 'url' => '/hosting']))->link_target);
    }

    public function test_admin_can_save_menu_from_the_single_target_dropdown(): void
    {
        $product = $this->product();
        $page = CmsPage::create(['title' => 'Kontak', 'slug' => 'kontak', 'is_published' => true]);

        $this->actingAs($this->admin(), 'admin');

        $this->post(route('admin.nav-menu.add'), ['label' => 'Basic', 'target' => "product:{$product->id}", 'is_active' => 1])
            ->assertSessionHasNoErrors();
        $this->post(route('admin.nav-menu.add'), ['label' => 'Kontak', 'target' => "page:{$page->id}", 'is_active' => 1])
            ->assertSessionHasNoErrors();
        $this->post(route('admin.nav-menu.add'), ['label' => 'Blog', 'target' => 'route:blog.index', 'is_active' => 1])
            ->assertSessionHasNoErrors();
        $this->post(route('admin.nav-menu.add'), ['label' => 'WA', 'target' => 'custom', 'url' => 'https://wa.me/6281234567890', 'is_active' => 1])
            ->assertSessionHasNoErrors();

        $basic = NavMenu::where('label', 'Basic')->firstOrFail();
        $this->assertSame('url', $basic->type);
        $this->assertSame("product:{$product->id}", $basic->target);
        $this->assertNull($basic->url);

        $kontak = NavMenu::where('label', 'Kontak')->firstOrFail();
        $this->assertSame('page', $kontak->type);
        $this->assertSame($page->id, $kontak->page_id);
        $this->assertNull($kontak->target);

        $blog = NavMenu::where('label', 'Blog')->firstOrFail();
        $this->assertSame('route', $blog->type);
        $this->assertSame('blog.index', $blog->route_name);

        $wa = NavMenu::where('label', 'WA')->firstOrFail();
        $this->assertSame('url', $wa->type);
        $this->assertSame('https://wa.me/6281234567890', $wa->url);
    }

    public function test_custom_address_validation(): void
    {
        $this->actingAs($this->admin(), 'admin');

        $this->post(route('admin.nav-menu.add'), ['label' => 'Path', 'target' => 'custom', 'url' => '/hosting', 'is_active' => 1])
            ->assertSessionHasNoErrors();

        foreach (['//evil.example', 'javascript:alert(1)', 'hosting'] as $bad) {
            $this->post(route('admin.nav-menu.add'), ['label' => 'Bad', 'target' => 'custom', 'url' => $bad, 'is_active' => 1])
                ->assertSessionHasErrors('url');
        }

        $this->post(route('admin.nav-menu.add'), ['label' => 'Kosong', 'target' => 'custom', 'url' => '', 'is_active' => 1])
            ->assertSessionHasErrors('url');

        $this->post(route('admin.nav-menu.add'), ['label' => 'Tanpa', 'target' => '', 'is_active' => 1])
            ->assertSessionHasErrors('type');

        $this->post(route('admin.nav-menu.add'), ['label' => 'Hantu', 'target' => 'product:999999', 'is_active' => 1])
            ->assertSessionHasErrors('target_ref');
    }
}
