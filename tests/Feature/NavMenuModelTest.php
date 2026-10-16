<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\NavMenu;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

/**
 * Peta halaman bawaan menu (label, ikon, keterangan, penanda aktif) sekarang
 * berada di config/nav_menu.php. Tes ini menjaga daftar itu tetap valid.
 */
class NavMenuModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_every_builtin_route_in_config_is_a_real_registered_route(): void
    {
        foreach (array_keys(NavMenu::builtinRoutes()) as $name) {
            if ($name === '#') {
                continue;
            }

            $this->assertTrue(Route::has($name), "config/nav_menu.php memuat route '{$name}' yang tidak terdaftar.");
        }
    }

    public function test_every_builtin_route_has_complete_metadata(): void
    {
        foreach (config('nav_menu.routes') as $name => $meta) {
            foreach (['label', 'desc', 'bi', 'fa'] as $key) {
                $this->assertNotEmpty($meta[$key] ?? null, "Route '{$name}' belum punya '{$key}'.");
            }
        }
    }

    public function test_route_label_and_mega_metadata_come_from_config_with_safe_fallbacks(): void
    {
        $blog = new NavMenu(['type' => 'route', 'route_name' => 'blog.index']);

        $this->assertSame('Blog', $blog->route_label);
        $this->assertSame('bi-journal-text', $blog->mega_icon);
        $this->assertSame('fa-solid fa-newspaper', $blog->mega_fa_icon);
        $this->assertSame('Artikel dan tips terbaru', $blog->mega_description);

        $unknown = new NavMenu(['type' => 'route', 'route_name' => 'tidak.ada']);

        $this->assertSame('tidak.ada', $unknown->route_label);
        $this->assertSame('bi-grid-1x2', $unknown->mega_icon);
        $this->assertSame('Buka halaman', $unknown->mega_description);
    }

    public function test_active_pattern_uses_config_override_or_the_route_name(): void
    {
        $this->assertSame('domains.*', (new NavMenu(['type' => 'route', 'route_name' => 'domains.transfer']))->active_pattern);
        $this->assertSame('blog.index', (new NavMenu(['type' => 'route', 'route_name' => 'blog.index']))->active_pattern);
        $this->assertSame('page.show', (new NavMenu(['type' => 'page']))->active_pattern);
        $this->assertNull((new NavMenu(['type' => 'url']))->active_pattern);
    }

    public function test_is_current_is_false_without_pattern_and_true_on_matching_route(): void
    {
        $this->assertFalse((new NavMenu(['type' => 'url']))->is_current);

        $this->get(route('blog.index'));

        $this->assertTrue((new NavMenu(['type' => 'route', 'route_name' => 'blog.index']))->is_current);
        $this->assertFalse((new NavMenu(['type' => 'route', 'route_name' => 'promo.index']))->is_current);
    }

    public function test_admin_menu_list_shows_builtin_route_label(): void
    {
        $admin = Admin::create([
            'username' => 'nav', 'name' => 'Nav', 'email' => 'nav@contoh.test',
            'password' => bcrypt('Rahasia-123'), 'role' => 'superadmin', 'is_active' => true,
        ]);

        NavMenu::create([
            'label' => 'Artikel', 'type' => 'route', 'route_name' => 'blog.index',
            'is_active' => true, 'sort_order' => 1,
        ]);

        $this->actingAs($admin, 'admin')
            ->get(route('admin.nav-menus'))
            ->assertOk()
            ->assertSee('Halaman bawaan');
    }
}
