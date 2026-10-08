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
     * @param  'fa'|'bi'  $iconSet
     * @return list<array{label:string, route:string, match:string|array, icon:string, badge?:int}>
     */
    public static function items(string $iconSet = 'fa', int $cartCount = 0): array
    {
        $items = [];

        foreach (self::definition() as [$label, $route, $match, $icons]) {
            $item = [
                'label' => $label,
                'route' => $route,
                'match' => $match,
                'icon'  => $icons[$iconSet] ?? $icons['fa'],
            ];

            if ($route === 'cart.index') {
                $item['badge'] = $cartCount;
            }

            $items[] = $item;
        }

        return $items;
    }

    /**
     * [label, nama route, pola route aktif, ['fa' => ikon FA, 'bi' => ikon BI]]
     * Urutan: ringkasan, toko, lalu layanan & akun klien.
     */
    private static function definition(): array
    {
        return [
            ['Dashboard', 'client.dashboard', 'client.dashboard*', ['fa' => 'fa-gauge', 'bi' => 'bi-speedometer2']],

            // Toko -- route-nya ada di routes/store.php (prefix /store, domain client).
            ['Pesan Hosting', 'catalog.index', ['catalog.index', 'catalog.category', 'catalog.product'], ['fa' => 'fa-cart-plus', 'bi' => 'bi-cart-plus']],
            ['Pesan VPS', 'catalog.vps', 'catalog.vps', ['fa' => 'fa-cloud', 'bi' => 'bi-hdd-rack']],
            ['Cek & Daftar Domain', 'domain.search', ['domain.*', 'domains.transfer*', 'domain-premium.*'], ['fa' => 'fa-magnifying-glass', 'bi' => 'bi-search']],
            ['Beli Lisensi', 'license.index', 'license.*', ['fa' => 'fa-key', 'bi' => 'bi-key']],
            ['Keranjang', 'cart.index', 'cart.*', ['fa' => 'fa-cart-shopping', 'bi' => 'bi-cart3']],

            // Layanan & akun klien.
            ['Layanan Saya', 'client.services', 'client.services*', ['fa' => 'fa-server', 'bi' => 'bi-server']],
            ['VPS Saya', 'client.vps', 'client.vps*', ['fa' => 'fa-cloud', 'bi' => 'bi-hdd-rack']],
            ['Domain Saya', 'client.domains', 'client.domains*', ['fa' => 'fa-globe', 'bi' => 'bi-globe2']],
            ['Billing', 'client.billing', 'client.billing', ['fa' => 'fa-receipt', 'bi' => 'bi-receipt']],
            ['Invoice', 'client.invoices', 'client.invoices*', ['fa' => 'fa-file-invoice', 'bi' => 'bi-file-earmark-text']],
            ['Lisensi Saya', 'client.licenses', 'client.licenses*', ['fa' => 'fa-key', 'bi' => 'bi-key']],
            ['Saldo Saya', 'client.balance', 'client.balance*', ['fa' => 'fa-wallet', 'bi' => 'bi-wallet2']],
            ['Tiket Support', 'client.tickets', 'client.tickets*', ['fa' => 'fa-comments', 'bi' => 'bi-life-preserver']],
            ['Affiliate', 'client.affiliate.index', 'client.affiliate*', ['fa' => 'fa-share-nodes', 'bi' => 'bi-share']],
            ['Profil Saya', 'client.profile', 'client.profile*', ['fa' => 'fa-user', 'bi' => 'bi-person-gear']],
        ];
    }
}
