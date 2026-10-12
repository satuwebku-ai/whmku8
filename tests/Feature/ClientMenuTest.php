<?php

namespace Tests\Feature;

use App\Support\ClientMenu;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class ClientMenuTest extends TestCase
{
    public function test_every_menu_item_points_to_an_existing_route(): void
    {
        foreach (['fa', 'bi'] as $set) {
            foreach (ClientMenu::flat(ClientMenu::items($set)) as $item) {
                $this->assertTrue(Route::has($item['route']), "Route {$item['route']} tidak ada ({$item['label']})");
                $this->assertNotEmpty($item['icon'], $item['label']);
            }
        }
    }

    public function test_both_icon_sets_have_the_same_items_in_the_same_order(): void
    {
        $labels = fn (string $set) => array_column(ClientMenu::flat(ClientMenu::items($set)), 'label');

        $this->assertSame($labels('fa'), $labels('bi'));
    }

    public function test_only_the_cart_item_carries_a_badge(): void
    {
        $withBadge = array_filter(ClientMenu::flat(ClientMenu::items('bi', 4)), fn ($i) => array_key_exists('badge', $i));

        $this->assertCount(1, $withBadge);
        $this->assertSame('cart.index', array_values($withBadge)[0]['route']);
        $this->assertSame(4, array_values($withBadge)[0]['badge']);
    }

    public function test_store_entries_are_part_of_the_menu(): void
    {
        $routes = array_column(ClientMenu::flat(ClientMenu::items()), 'route');

        foreach (['catalog.index', 'catalog.vps', 'domain.search', 'license.index', 'cart.index', 'client.licenses'] as $route) {
            $this->assertContains($route, $routes);
        }
    }

    public function test_client_layouts_use_the_shared_menu_instead_of_their_own_copy(): void
    {
        foreach (glob(resource_path('views/themes/client-themes/*/client/layout.blade.php')) as $layout) {
            $source = file_get_contents($layout);

            $this->assertStringContainsString('ClientMenu::items(', $source, $layout);
            $this->assertStringNotContainsString("'route' => 'client.dashboard'", $source, $layout);
        }
    }

    public function test_store_group_replaces_the_four_loose_store_entries(): void
    {
        $top = ClientMenu::items();
        $labels = array_column($top, 'label');

        $this->assertContains('Store', $labels);
        foreach (['Pesan Hosting', 'Pesan VPS', 'Beli Lisensi', 'Cek & Daftar Domain', 'Keranjang'] as $gone) {
            $this->assertNotContains($gone, $labels, "$gone harus ada di dalam grup Store, bukan di tingkat atas");
        }

        $store = $top[array_search('Store', $labels, true)];
        $this->assertSame(
            ['Pesan Hosting', 'Pesan VPS', 'Cek & Daftar Domain', 'Beli Lisensi', 'Keranjang'],
            array_column($store['children'], 'label')
        );
    }

    public function test_store_group_is_active_when_any_child_is_active(): void
    {
        $top = ClientMenu::items();
        $store = $top[array_search('Store', array_column($top, 'label'), true)];

        foreach (['catalog.index', 'catalog.vps', 'domain.*', 'license.*', 'cart.*'] as $pattern) {
            $this->assertContains($pattern, (array) $store['match']);
        }
    }
}
