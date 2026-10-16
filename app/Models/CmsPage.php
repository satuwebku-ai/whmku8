<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class CmsPage extends Model
{
    use HasFactory;
    use UsesCmsContentTable;

    public const CONTENT_TYPE = 'page';

    public const SLUG_FALLBACK = 'halaman';

    /**
     * Slug yang tidak boleh dipakai halaman karena bentrok dengan alamat
     * sistem. Sejak halaman dilayani dari root (/{slug}), halaman berjudul
     * "Hosting" akan merebut alamat katalog hosting yang asli.
     *
     * Sumbernya dua: (1) segmen pertama SEMUA route GET di host publik --
     * otomatis, jadi route baru ikut terlindungi tanpa mengedit daftar;
     * (2) config('cms.reserved_slugs') untuk kata di luar route.
     */
    public static function isReservedSlug(string $slug): bool
    {
        $slug = Str::slug($slug);

        return in_array($slug, config('cms.reserved_slugs', []), true)
            || in_array($slug, static::publicRouteSegments(), true);
    }

    /**
     * @return array<int, string>
     */
    protected static function publicRouteSegments(): array
    {
        static $cache = null;

        // Jangan simpan hasil kosong: route bisa belum terdaftar saat pertama dipanggil.
        if ($cache !== null) {
            return $cache;
        }

        // Sama dengan Route::getDomain(): skema (http://) dibuang.
        $publicHost = str_replace(['http://', 'https://'], '', (string) config('portal_domains.public'));

        $segments = collect(Route::getRoutes()->getRoutes())
            ->filter(fn ($route) => in_array('GET', $route->methods(), true)
                && (string) $route->getDomain() === $publicHost)
            ->map(fn ($route) => explode('/', trim($route->uri(), '/'))[0])
            ->filter(fn (string $segment) => preg_match('/^[a-z0-9\-]+$/', $segment) === 1)
            ->unique()
            ->values()
            ->all();

        return $segments === [] ? [] : ($cache = $segments);
    }

    protected static function slugIsReserved(string $slug): bool
    {
        return static::isReservedSlug($slug);
    }

    protected $fillable = [
        'title', 'slug', 'content', 'meta_title', 'meta_description',
        'meta_keywords', 'og_image', 'noindex', 'is_published',
        'show_in_footer', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'noindex' => 'boolean',
            'is_published' => 'boolean',
            'show_in_footer' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (CmsPage $page) {
            if (blank($page->slug)) {
                $page->slug = static::uniqueSlug($page->title, $page->id);
            } else {
                $page->slug = Str::slug($page->slug);
            }
        });
    }

    public static function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        return static::uniqueSlugFor($title, $ignoreId);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    /**
     * Judul untuk tag <title> — pakai meta_title kalau diisi.
     */
    public function getSeoTitleAttribute(): string
    {
        // Dipaksa string: pada form "Tambah Halaman" model masih kosong,
        // sehingga title/meta_title bernilai null.
        return (string) ($this->meta_title ?: $this->title ?: '');
    }

    public function getSeoDescriptionAttribute(): string
    {
        return (string) ($this->meta_description
            ?: Str::limit(strip_tags((string) $this->content), 155));
    }

    public function getUrlAttribute(): string
    {
        return route('page.show', (string) $this->slug);
    }
}
