<?php

namespace App\Support;

use App\Models\Setting;
use Illuminate\Support\Collection;

/**
 * Pengaturan tampilan footer situs publik (Admin > CMS > Footer).
 *
 * Disimpan di tabel settings (tanpa migrasi): footer_size, footer_layout,
 * footer_order (JSON), dan footer_show_{blok}. Dipakai partial
 * public.partials.footer di semua tema.
 */
class FooterConfig
{
    public const BLOCKS = [
        'brand'    => ['label' => 'Profil Situs', 'desc' => 'Nama situs dan tagline.'],
        'products' => ['label' => 'Produk', 'desc' => 'Semua paket, lisensi, cari domain, transfer domain.'],
        'pages'    => ['label' => 'Informasi', 'desc' => 'Halaman CMS yang diberi centang "Tampilkan link di footer".'],
        'help'     => ['label' => 'Bantuan', 'desc' => 'Blog, Pusat Bantuan, dan Pengumuman.'],
        'account'  => ['label' => 'Akun', 'desc' => 'Area klien dan pendaftaran.'],
    ];

    public const SIZES = [
        'small'  => 'Kecil',
        'medium' => 'Sedang (bawaan tema)',
        'large'  => 'Besar',
    ];

    public const LAYOUTS = [
        'columns' => 'Kolom berdampingan',
        'inline'  => 'Satu baris tautan',
    ];

    public static function size(): string
    {
        $size = (string) Setting::get('footer_size', 'medium');

        return array_key_exists($size, self::SIZES) ? $size : 'medium';
    }

    public static function layout(): string
    {
        $layout = (string) Setting::get('footer_layout', 'columns');

        return array_key_exists($layout, self::LAYOUTS) ? $layout : 'columns';
    }

    /** Urutan blok tersimpan; blok yang belum tercatat ditambahkan di belakang. */
    public static function order(): array
    {
        $keys = array_keys(self::BLOCKS);
        $saved = json_decode((string) Setting::get('footer_order'), true);
        $saved = is_array($saved) ? array_values(array_intersect($saved, $keys)) : [];

        return array_values(array_unique(array_merge($saved, $keys)));
    }

    public static function isVisible(string $key): bool
    {
        return Setting::get('footer_show_'.$key, '1') === '1';
    }

    /** Gaya inline per ukuran. Sedang = tanpa override, jadi tampilan bawaan tema tidak berubah. */
    public static function styles(): array
    {
        return match (self::size()) {
            'small' => [
                'footer' => 'padding-top:1.25rem;padding-bottom:1rem',
                'title'  => 'font-size:13px',
                'link'   => 'font-size:12px',
                'gap'    => 'g-3',
                'item'   => 'margin-bottom:.15rem',
                'text'   => 'font-size:12px',
                'icon'   => 28,
                'logo'   => 40,
            ],
            'large' => [
                'footer' => 'padding-top:4.5rem;padding-bottom:2.5rem',
                'title'  => 'font-size:18px',
                'link'   => 'font-size:16px',
                'gap'    => 'g-5',
                'item'   => 'margin-bottom:.6rem',
                'text'   => 'font-size:15px',
                'icon'   => 48,
                'logo'   => 68,
            ],
            default => ['footer' => '', 'title' => '', 'link' => '', 'gap' => 'g-4', 'item' => 'margin-bottom:.35rem', 'text' => '', 'icon' => 36, 'logo' => 52],
        };
    }

    /**
     * Branding footer, mengikuti header (Pengaturan > Branding): "logo_and_text"
     * = ikon kecil + nama, "logo_only" = logo lengkap, selain itu teks saja.
     * Ikon jatuh balik ke logo utama; null = pakai lambang bawaan tema.
     *
     * @return array{display:string,icon:?string,logo:?string}
     */
    public static function brand(): array
    {
        $display = (string) Setting::get('branding_display', 'logo_and_text');
        $logo = Setting::get('site_logo') ?: null;
        $icon = Setting::get('site_icon') ?: $logo;

        if ($display === 'logo_only' && ! $logo) {
            $display = 'text';
        }

        return ['display' => $display, 'icon' => $icon, 'logo' => $logo];
    }

    /** @return array<int, array{label:string,url:string}> */
    public static function links(string $block, Collection $footerPages): array
    {
        return match ($block) {
            'products' => [
                ['label' => 'Semua paket', 'url' => route('catalog.index')],
                ['label' => 'Lisensi', 'url' => route('license.index')],
                ['label' => 'Cari domain', 'url' => route('domain.search')],
                ['label' => 'Transfer domain', 'url' => route('domains.transfer')],
            ],
            'pages' => $footerPages->map(fn ($p) => ['label' => $p->title, 'url' => route('page.show', $p->slug)])->all(),
            'help' => [
                ['label' => 'Blog', 'url' => route('blog.index')],
                ['label' => 'Pusat Bantuan', 'url' => route('knowledge-base.index')],
                ['label' => 'Pengumuman', 'url' => route('announcements.index')],
            ],
            'account' => [
                ['label' => 'Area klien', 'url' => route('client.login')],
                ['label' => 'Daftar', 'url' => route('client.register')],
            ],
            default => [],
        };
    }

    /**
     * Semua yang dibutuhkan partial footer: blok yang tampil (berurutan, blok
     * tautan yang kosong dilewati), ukuran, dan tata letak.
     */
    public static function resolve(Collection $footerPages): array
    {
        $blocks = [];

        foreach (self::order() as $key) {
            if (! self::isVisible($key)) {
                continue;
            }

            $links = self::links($key, $footerPages);

            if ($key !== 'brand' && $links === []) {
                continue;
            }

            $blocks[] = ['key' => $key, 'title' => self::BLOCKS[$key]['label'], 'links' => $links];
        }

        return [
            'blocks'  => $blocks,
            'layout'  => self::layout(),
            'style'   => self::styles(),
            'tagline' => (string) Setting::get('site_tagline'),
            'brand'   => self::brand(),
        ];
    }
}
