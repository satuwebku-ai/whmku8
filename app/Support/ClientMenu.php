<?php

namespace App\Support;

/**
 * Daftar menu sidebar portal client -- SATU sumber untuk semua tema client
 * (default, modern, namahost). Tema hanya memilih set ikon:
 *   'fa' = Font Awesome (default, modern)
 *   'bi' = Bootstrap Icons (namahost)
 *
 * Item baru cukup ditambahkan di sini; tidak perlu lagi menyalinnya ke tiap
 * layout tema (cara lama, yang pernah membuat "Lisensi Saya" tertinggal di
 * satu tema).
 */
class ClientMenu
{
    /**
     * Item bisa punya 'children' (submenu). Saat ini hanya grup "Store".
     *
     * @param  'fa'|'bi'  $iconSet
     * @return list<array{label:string, route:string, match:string|array, icon:string, badge?:int, children?:list<array>}>
     */
    public static function items(string $iconSet = 'fa', int $cartCount = 0): array
    {
        $items = [];

        foreach (self::definition() as $row) {
            [$label, $route, $match, $icons] = $row;
            $children = $row[4] ?? null;

            $item = self::build($label, $route, $match, $icons, $iconSet, $cartCount);

            if ($children !== null) {
                $item['children'] = array_map(
                    fn (array $c) => self::build($c[0], $c[1], $c[2], $c[3], $iconSet, $cartCount),
                    $children
                );
                // Grup aktif bila salah satu submenunya aktif.
                $item['match'] = array_merge(...array_map(fn (array $c) => (array) $c[2], $children));
            }

            $items[] = $item;
        }

        return $items;
    }

    /**
     * Daftar datar (submenu dibentangkan, induk grup dilewati) -- untuk nav
     * mobile yang berupa deretan pil tanpa dropdown.
     *
     * @param  list<array>  $items
     * @return list<array>
     */
    public static function flat(array $items): array
    {
        $out = [];

        foreach ($items as $item) {
            if (isset($item['children'])) {
                array_push($out, ...$item['children']);
            } else {
                $out[] = $item;
            }
        }

        return $out;
    }

    private static function build(string $label, string $route, string|array $match, array $icons, string $iconSet, int $cartCount): array
    {
        $item = [
            'label' => $label,
            'route' => $route,
            'match' => $match,
            'icon'  => $icons[$iconSet] ?? $icons['fa'],
        ];

        if ($route === 'cart.index') {
            $item['badge'] = $cartCount;
        }

        return $item;
    }

    /**
     * [label, nama route, pola route aktif, ['fa' => ikon FA, 'bi' => ikon BI], (opsional) submenu]
     * Urutan: ringkasan, toko, lalu layanan & akun klien.
     */
    private static function definition(): array
    {
        return [
            ['Dashboard', 'client.dashboard', 'client.dashboard*', ['fa' => 'fa-gauge', 'bi' => 'bi-speedometer2']],

            // Store -- satu grup berisi semua menu jualan + keranjang. Halaman
            // menjelajah ada di domain publik (routes/store.php); setelah memilih,
            // klien dibawa kembali ke keranjang di portal ini (/store/keranjang).
            ['Store', 'catalog.index', [], ['fa' => 'fa-store', 'bi' => 'bi-shop'], [
                ['Pesan Hosting', 'catalog.index', ['catalog.index', 'catalog.category', 'catalog.product'], ['fa' => 'fa-cart-plus', 'bi' => 'bi-cart-plus']],
                ['Pesan VPS', 'catalog.vps', 'catalog.vps', ['fa' => 'fa-cloud', 'bi' => 'bi-hdd-rack']],
                ['Cek & Daftar Domain', 'domain.search', ['domain.*', 'domains.transfer*', 'domain-premium.*'], ['fa' => 'fa-magnifying-glass', 'bi' => 'bi-search']],
                ['Beli Lisensi', 'license.index', 'license.*', ['fa' => 'fa-key', 'bi' => 'bi-key']],
                ['Keranjang', 'cart.index', 'cart.*', ['fa' => 'fa-cart-shopping', 'bi' => 'bi-cart3']],
            ]],

            // Layanan & akun klien.
            ['Layanan Saya', 'client.services', 'client.services*', ['fa' => 'fa-server', 'bi' => 'bi-server']],
            ['VPS Saya', 'client.vps', 'client.vps*', ['fa' => 'fa-cloud', 'bi' => 'bi-hdd-rack']],
            ['Domain Saya', 'client.domains', 'client.domains*', ['fa' => 'fa-globe', 'bi' => 'bi-globe2']],
            ['Billing', 'client.billing', 'client.billing', ['fa' => 'fa-receipt', 'bi' => 'bi-receipt']],
            ['Invoice', 'client.invoices', 'client.invoices*', ['fa' => 'fa-file-invoice', 'bi' => 'bi-file-earmark-text']],
            ['Lisensi Saya', 'client.licenses', 'client.licenses*', ['fa' => 'fa-key', 'bi' => 'bi-key']],
            ['Saldo Saya', 'client.balance', 'client.balance*', ['fa' => 'fa-wallet', 'bi' => 'bi-wallet2']],
            ['Tutorial', 'client.tutorials', 'client.tutorials*', ['fa' => 'fa-graduation-cap', 'bi' => 'bi-mortarboard']],
            ['Tiket Support', 'client.tickets', 'client.tickets*', ['fa' => 'fa-comments', 'bi' => 'bi-life-preserver']],
            ['Affiliate', 'client.affiliate.index', 'client.affiliate*', ['fa' => 'fa-share-nodes', 'bi' => 'bi-share']],
            ['Profil Saya', 'client.profile', 'client.profile*', ['fa' => 'fa-user', 'bi' => 'bi-person-gear']],
        ];
    }
}
