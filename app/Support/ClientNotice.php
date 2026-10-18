<?php

namespace App\Support;

use App\Models\Announcement;
use App\Models\PromoBanner;
use App\Models\Setting;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * Pengumuman & banner di area klien: pita teks berjalan di atas halaman, modal
 * pengumuman di dashboard, dan banner promo (tujuan "Area Klien").
 * Diatur di Admin > CMS > Promosi > Area Klien. Disimpan di tabel settings.
 */
class ClientNotice
{
    public const SPEEDS = ['slow' => 'Lambat', 'normal' => 'Normal', 'fast' => 'Cepat'];

    public const CATEGORY_LABELS = [
        'info' => 'Info', 'promo' => 'Promo', 'maintenance' => 'Pemeliharaan', 'incident' => 'Gangguan',
    ];

    public static function tickerEnabled(): bool
    {
        return Setting::get('client_ticker_enabled', '1') === '1';
    }

    public static function modalEnabled(): bool
    {
        return Setting::get('client_modal_enabled', '1') === '1';
    }

    public static function bannerEnabled(): bool
    {
        return Setting::get('client_banner_enabled', '1') === '1';
    }

    public static function speed(): string
    {
        $speed = (string) Setting::get('client_ticker_speed', 'normal');

        return array_key_exists($speed, self::SPEEDS) ? $speed : 'normal';
    }

    /** Pengumuman yang terbit dalam N hari terakhir yang ditampilkan (yang disematkan selalu). 0 = semua. */
    public static function windowDays(): int
    {
        return max(0, min(365, (int) Setting::get('client_notice_days', 30)));
    }

    /** Pengumuman terbit & aktif; disematkan lebih dulu, lalu terbaru. */
    public static function announcements(int $limit = 5): Collection
    {
        $days = self::windowDays();

        return Announcement::query()->live()
            ->when($days > 0, fn ($q) => $q->where(
                fn ($w) => $w->where('is_pinned', true)->orWhere('published_at', '>=', now()->subDays($days))
            ))
            ->orderByDesc('is_pinned')
            ->orderByDesc('published_at')
            ->limit($limit)
            ->get();
    }

    /** Data pita berjalan, atau null bila dimatikan / belum ada pengumuman. */
    public static function ticker(): ?array
    {
        if (! self::tickerEnabled()) {
            return null;
        }

        $list = self::announcements(5);

        if ($list->isEmpty()) {
            return null;
        }

        $items = $list->map(fn (Announcement $a) => [
            'label' => self::CATEGORY_LABELS[$a->category] ?? 'Info',
            'title' => $a->title,
            'url'   => route('announcements.show', $a->slug),
        ])->all();

        $chars = collect($items)->sum(fn ($i) => Str::length($i['label'].$i['title']) + 8);
        $factor = ['slow' => 0.30, 'normal' => 0.20, 'fast' => 0.12][self::speed()];

        $categories = $list->pluck('category');
        $bg = $categories->contains('incident') ? '#b91c1c' : ($categories->contains('maintenance') ? '#b45309' : '#0f172a');

        return [
            'items'     => $items,
            'duration'  => max(20, (int) round($chars * $factor)),
            'bg'        => $bg,
            'signature' => md5($list->pluck('id')->implode(',')),
        ];
    }

    public static function modal(): ?Announcement
    {
        return self::modalEnabled() ? self::announcements(1)->first() : null;
    }

    public static function banners(): Collection
    {
        if (! self::bannerEnabled()) {
            return collect();
        }

        return PromoBanner::query()->live()->forPage('client')->orderBy('sort_order')->orderBy('id')->get();
    }
}
