<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductGroup;
use App\Models\ProductType;
use App\Models\Server;
use App\Models\ServerGroup;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Katalog lengkap: jenis produk, grup produk, server (cPanel/WHM + VPS cloud),
 * grup server, dan produk beserta harganya.
 *
 * Jalankan manual (TIDAK ikut `php artisan db:seed` biasa, supaya instalasi
 * produksi tetap bersih -- sama seperti DemoDataSeeder):
 *
 *   php artisan db:seed --class=ServerProductSeeder
 *
 * Aman dijalankan berulang: semua data dicari dulu (firstOrCreate), jadi
 * yang sudah ada -- termasuk yang sudah Anda ubah lewat admin -- tidak ditimpa.
 *
 * PENTING: hostname & API token server di bawah hanya CONTOH. Ganti lewat
 * Admin » Infrastruktur » Server sebelum dipakai menerima order sungguhan.
 */
class ServerProductSeeder extends Seeder
{
    /** Token contoh -- sengaja tidak valid. */
    private const PLACEHOLDER_TOKEN = 'GANTI-DENGAN-TOKEN-ASLI';

    /** Kartu harga VPS (Rp per jam) -- contoh, sesuaikan dengan harga modal provider. */
    private const VPS_RATES = [
        'price_per_vcpu_hour'        => 40,
        'price_per_ram_gb_hour'      => 25,
        'price_per_storage_gb_hour'  => 0.8,
        'price_per_backup_gb_hour'   => 0.3,
        'price_per_snapshot_gb_hour' => 0.2,
        'price_windows_license_per_vcpu_hour' => 60,
    ];

    public function run(): void
    {
        DB::transaction(function () {
            $types = $this->productTypes();
            $servers = $this->servers();
            $serverGroups = $this->serverGroups($servers);
            $groups = $this->productGroups($types);

            $this->hostingProducts($groups, $serverGroups);
            $this->vpsProducts($groups['cloud-vps'], $servers['cloud']);
        });

        $this->command?->info(sprintf(
            'Selesai: %d jenis, %d grup produk, %d server, %d grup server, %d produk.',
            ProductType::count(), ProductGroup::count(), Server::count(), ServerGroup::count(), Product::count()
        ));
        $this->command?->warn('Ganti hostname & API token server contoh lewat Admin » Infrastruktur » Server.');
    }

    /** @return array<string, ProductType> */
    private function productTypes(): array
    {
        $rows = [
            'hosting' => ['name' => 'Hosting (cPanel/WHM)', 'kind' => 'hosting', 'icon' => 'fa-server', 'color' => '#4f46e5', 'sort_order' => 1],
            'vps'     => ['name' => 'VPS / Cloud Server', 'kind' => 'vps', 'icon' => 'fa-cloud', 'color' => '#059669', 'sort_order' => 2],
        ];

        $types = [];
        foreach ($rows as $slug => $row) {
            $types[$slug] = ProductType::firstOrCreate(['slug' => $slug], $row + ['is_active' => true]);
        }

        return $types;
    }

    /** @return array<string, Server> */
    private function servers(): array
    {
        $cpanel = fn (string $host, int $max, int $priority) => [
            'hostname' => $host, 'ns1' => 'ns1.contoh.com', 'ns2' => 'ns2.contoh.com',
            'port' => 2087, 'panel' => 'cpanel', 'api_username' => 'root',
            'api_token' => self::PLACEHOLDER_TOKEN, 'verify_ssl' => true,
            'max_accounts' => $max, 'priority' => $priority,
            'is_active' => true, 'is_maintenance' => false,
        ];

        $servers = [
            'shared1'   => ['Shared JKT-01', $cpanel('srv1.contoh.com', 500, 1)],
            'shared2'   => ['Shared JKT-02', $cpanel('srv2.contoh.com', 500, 2)],
            'wordpress' => ['WordPress JKT-01', $cpanel('wp1.contoh.com', 300, 1)],
            'reseller'  => ['Reseller JKT-01', $cpanel('rs1.contoh.com', 200, 1)],
        ];

        $made = [];
        foreach ($servers as $key => [$name, $attrs]) {
            $made[$key] = Server::firstOrCreate(['name' => $name], $attrs);
        }

        // Server cloud/VPS (IDCloudHost). hostname = slug lokasi, api_username
        // = Billing Account ID (boleh kosong untuk akun default).
        $made['cloud'] = Server::firstOrCreate(
            ['name' => 'IDCloudHost JKT01'],
            [
                'hostname' => 'jkt01', 'api_username' => '', 'api_token' => self::PLACEHOLDER_TOKEN,
                'panel' => 'vps', 'vps_provider' => 'idcloudhost',
                'verify_ssl' => true, 'max_accounts' => null, 'priority' => 1,
                'is_active' => true, 'is_maintenance' => false,
                'pricing_mode' => 'manual',
            ] + self::VPS_RATES
        );

        return $made;
    }

    /**
     * Grup server: satu server boleh ada di beberapa grup, prioritas per grup.
     *
     * @param  array<string, Server>  $servers
     * @return array<string, ServerGroup>
     */
    private function serverGroups(array $servers): array
    {
        $defs = [
            'shared-hosting' => [
                'name' => 'Shared Hosting', 'mode' => 'least_accounts',
                'description' => 'Server shared hosting; order masuk ke server yang paling lega.',
                'members' => ['shared1' => 1, 'shared2' => 2],
            ],
            'wordpress-hosting' => [
                'name' => 'WordPress Hosting', 'mode' => 'priority',
                'description' => 'Server khusus WordPress; Shared JKT-02 jadi cadangan kalau penuh.',
                'members' => ['wordpress' => 1, 'shared2' => 5],
            ],
            'reseller-hosting' => [
                'name' => 'Reseller Hosting', 'mode' => 'round_robin',
                'description' => 'Server khusus paket reseller (WHM).',
                'members' => ['reseller' => 1],
            ],
        ];

        $groups = [];
        foreach ($defs as $slug => $def) {
            $group = ServerGroup::firstOrCreate(['slug' => $slug], [
                'name' => $def['name'], 'description' => $def['description'],
                'selection_mode' => $def['mode'], 'is_active' => true,
            ]);

            foreach ($def['members'] as $serverKey => $priority) {
                $group->servers()->syncWithoutDetaching([
                    $servers[$serverKey]->id => ['priority' => $priority, 'is_active' => true],
                ]);
            }

            $groups[$slug] = $group;
        }

        return $groups;
    }

    /**
     * @param  array<string, ProductType>  $types
     * @return array<string, ProductGroup>
     */
    private function productGroups(array $types): array
    {
        $defs = [
            'shared-hosting'    => ['Shared Hosting', 'hosting', 'fa-server', 'Cocok untuk website pribadi, blog, dan portofolio.'],
            'wordpress-hosting' => ['WordPress Hosting', 'hosting', 'fa-globe', 'Dioptimalkan untuk WordPress: cepat, aman, dan mudah dikelola.'],
            'reseller-hosting'  => ['Reseller Hosting', 'hosting', 'fa-users', 'Jual kembali hosting ke klien Anda dengan WHM sendiri.'],
            'cloud-vps'         => ['Cloud VPS', 'vps', 'fa-cloud', 'Server virtual dengan root access, ditagih per jam dari saldo.'],
        ];

        $groups = [];
        $order = 1;
        foreach ($defs as $slug => [$name, $kind, $icon, $description]) {
            $groups[$slug] = ProductGroup::firstOrCreate(['slug' => $slug], [
                'name' => $name, 'type' => $kind, 'product_type_id' => $types[$kind]->id,
                'description' => $description, 'icon' => $icon, 'is_active' => true, 'sort_order' => $order++,
            ]);
        }

        return $groups;
    }

    /**
     * Produk hosting memakai GRUP server (bukan satu server), jadi server
     * dipilih otomatis sesuai selection_mode grupnya.
     *
     * @param  array<string, ProductGroup>  $groups
     * @param  array<string, ServerGroup>  $serverGroups
     */
    private function hostingProducts(array $groups, array $serverGroups): void
    {
        // [nama, tagline, panel_package, [bulanan, 3 bln, 6 bln, tahunan], unggulan, fitur]
        $catalog = [
            'shared-hosting' => [
                'domain' => 'optional',
                'products' => [
                    ['Hosting Starter', 'Awal yang pas untuk website pertama Anda', 'starter', [25000, 70000, 135000, 250000], false,
                        ['5 GB SSD Storage', '50 GB Bandwidth', '1 Website', 'Free SSL', 'Support 24/7']],
                    ['Hosting Pro', 'Paling laris — cukup untuk toko online kecil', 'pro', [55000, 160000, 310000, 550000], true,
                        ['20 GB SSD Storage', 'Unlimited Bandwidth', '5 Website', 'Free SSL', 'Free Domain .com', 'Support Prioritas']],
                    ['Hosting Business', 'Untuk website dengan trafik menengah', 'business', [95000, 275000, 540000, 950000], false,
                        ['50 GB SSD Storage', 'Unlimited Bandwidth', 'Unlimited Website', 'Free SSL', 'Free Domain', 'Backup Harian']],
                ],
            ],
            'wordpress-hosting' => [
                'domain' => 'optional',
                'products' => [
                    ['WP Basic', 'WordPress siap pakai untuk blog & company profile', 'wp_basic', [45000, 130000, 250000, 450000], false,
                        ['10 GB NVMe', '1 Website WordPress', 'Auto-install WordPress', 'Free SSL', 'LiteSpeed Cache']],
                    ['WP Plus', 'Pilihan favorit untuk toko online WooCommerce', 'wp_plus', [85000, 245000, 480000, 850000], true,
                        ['30 GB NVMe', '3 Website WordPress', 'WooCommerce Ready', 'Free SSL', 'LiteSpeed Cache', 'Backup Harian']],
                    ['WP Pro', 'Performa maksimal untuk website trafik tinggi', 'wp_pro', [145000, 420000, 820000, 1450000], false,
                        ['80 GB NVMe', '10 Website WordPress', 'Staging Environment', 'Free SSL', 'Malware Scan', 'Backup Harian']],
                ],
            ],
            'reseller-hosting' => [
                'domain' => 'none',
                'products' => [
                    ['Reseller Mini', 'Mulai bisnis hosting dengan modal kecil', 'reseller_mini', [150000, 435000, 850000, 1500000], false,
                        ['50 GB SSD', '25 Akun cPanel', 'WHM Reseller', 'Free SSL', 'White Label']],
                    ['Reseller Pro', 'Untuk reseller dengan banyak klien', 'reseller_pro', [275000, 800000, 1570000, 2750000], true,
                        ['150 GB SSD', '100 Akun cPanel', 'WHM Reseller', 'Free SSL', 'White Label', 'Support Prioritas']],
                ],
            ],
        ];

        $order = 1;
        foreach ($catalog as $groupSlug => $def) {
            foreach ($def['products'] as [$name, $tagline, $package, $prices, $featured, $features]) {
                Product::firstOrCreate(
                    ['product_category_id' => $groups[$groupSlug]->id, 'name' => $name],
                    [
                        'tagline' => $tagline,
                        'features' => $features,
                        'price_monthly' => $prices[0],
                        'price_quarterly' => $prices[1],
                        'price_semi_annually' => $prices[2],
                        'price_annually' => $prices[3],
                        'setup_fee' => 0,
                        'domain_option' => $def['domain'],
                        'server_group_id' => $serverGroups[$groupSlug]->id,
                        'panel_package' => $package,
                        'billing_mode' => 'invoice',
                        'is_active' => true,
                        'is_featured' => $featured,
                        'sort_order' => $order++,
                    ]
                );
            }
        }
    }

    /**
     * Produk VPS ditagih per jam dari saldo (billing_mode=deposit). Spek
     * disimpan sebagai JSON di panel_package (RAM dalam MB, disk dalam GB);
     * tarif per jam dihitung dari kartu harga produk x spek.
     */
    private function vpsProducts(ProductGroup $group, Server $cloud): void
    {
        // [nama, tagline, vCPU, RAM MB, disk GB, unggulan]
        $plans = [
            ['VPS Nano', '1 vCPU, 1 GB RAM — untuk belajar & proyek kecil', 1, 1024, 20, false],
            ['VPS Basic', '2 vCPU, 4 GB RAM — kontrol penuh via root access', 2, 4096, 80, true],
            ['VPS Standard', '4 vCPU, 8 GB RAM — aplikasi & database menengah', 4, 8192, 160, false],
            ['VPS Pro', '8 vCPU, 16 GB RAM — beban kerja produksi', 8, 16384, 320, false],
        ];

        $order = 1;
        foreach ($plans as [$name, $tagline, $vcpu, $ram, $disk, $featured]) {
            $ramGb = $ram / 1024;

            Product::firstOrCreate(
                ['product_category_id' => $group->id, 'name' => $name],
                [
                    'tagline' => $tagline,
                    'features' => [
                        "{$vcpu} vCPU", "{$ramGb} GB RAM", "{$disk} GB SSD", 'Full Root Access',
                        'Ditagih per jam', 'Bayar sesuai pemakaian',
                    ],
                    'domain_option' => 'none',
                    'server_id' => $cloud->id,
                    'panel_package' => json_encode([
                        'vcpu' => $vcpu, 'ram' => $ram, 'disk' => $disk,
                        'os_name' => 'ubuntu', 'os_version' => '22.04', 'backup_enabled' => false,
                    ]),
                    'billing_mode' => 'deposit',
                    'pricing_mode' => 'manual',
                    'is_active' => true,
                    'is_featured' => $featured,
                    'sort_order' => $order++,
                ] + self::VPS_RATES
            );
        }
    }
}
