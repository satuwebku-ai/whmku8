<?php

namespace App\Support;

use App\Models\Setting;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

/**
 * Daftar tema diambil LANGSUNG dari folder (bukan dari config/), supaya:
 *  - tema baru cukup ditaruh foldernya, tanpa daftar manual;
 *  - tidak terpengaruh `php artisan config:cache` di server produksi
 *    (penyebab dropdown tetap cuma menampilkan "Default" setelah deploy).
 *
 * Struktur:
 *   resources/views/themes/public-themes/{key}/public/...
 *   resources/views/themes/client-themes/{key}/client/...
 * Label opsional: file theme.json di dalam folder {key}, mis. {"label":"Modern (Elegan)"}
 */
class ThemeRegistry
{
    /** @return array<string, array{label:string}> */
    public static function available(string $scope): array
    {
        $base = resource_path("views/themes/{$scope}-themes");
        $themes = [];

        if (File::isDirectory($base)) {
            foreach (File::directories($base) as $dir) {
                $key = basename($dir);
                $label = ucfirst($key);
                $meta = $dir . '/theme.json';

                if (File::exists($meta)) {
                    $json = json_decode(File::get($meta), true);
                    $label = $json['label'] ?? $label;
                }

                $themes[$key] = ['label' => $label];
            }
        }

        if (! isset($themes['default'])) {
            $themes = ['default' => ['label' => 'Default']] + $themes;
        }

        ksort($themes);

        return $themes;
    }

    /**
     * Key tema yang aktif untuk scope 'public' atau 'client'. Kalau pengaturan
     * belum ada, DB belum siap (migrasi awal), atau menunjuk ke tema yang
     * foldernya sudah dihapus, jatuh balik ke "default" supaya situs tidak 500.
     */
    public static function active(string $scope): string
    {
        $key = 'default';

        try {
            if (Schema::hasTable('settings')) {
                $key = (string) Setting::get("{$scope}_template", 'default');
            }
        } catch (\Throwable) {
            // DB belum siap -- pakai default.
        }

        return array_key_exists($key, self::available($scope)) ? $key : 'default';
    }

    /**
     * Layout induk untuk halaman toko (katalog, domain, lisensi, keranjang).
     *
     * Klien yang sudah login melihat toko DI DALAM portal client (sidebar,
     * menu, dan tema client). Itu hanya dilakukan bila tema Publik dan tema
     * Client yang aktif sama: view toko ditulis dengan CSS tema Publik, dan
     * layout client dari tema lain memuat CSS yang berbeda sehingga tampilannya
     * bisa berantakan. Tamu, atau tema yang berbeda, memakai layout publik.
     */
    public static function storeLayout(): string
    {
        if (! auth('client')->check()) {
            return 'public.layout';
        }

        return self::active('public') === self::active('client') ? 'client.layout' : 'public.layout';
    }
}
