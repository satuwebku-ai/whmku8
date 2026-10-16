<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Route as RouteFacade;

class NavMenu extends Model
{
    protected $fillable = [
        'parent_id', 'label', 'type', 'route_name', 'page_id', 'default_child_id', 'url',
        'open_in_new_tab', 'is_active', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'open_in_new_tab' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(NavMenu::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(NavMenu::class, 'parent_id')
            ->where('is_active', true)
            ->orderBy('sort_order');
    }

    /**
     * Sama seperti children(), tapi TANPA filter is_active -- dipakai
     * khusus di admin supaya submenu yang lagi disembunyikan (nonaktif)
     * tetap kelihatan di daftar (redup), bukan hilang begitu saja dan
     * bikin bingung "kok submenu-nya nggak ada". Jangan dipakai di
     * layout publik -- di situ harus children() yang sudah difilter.
     */
    public function allChildren(): HasMany
    {
        return $this->hasMany(NavMenu::class, 'parent_id')->orderBy('sort_order');
    }

    /**
     * Halaman bawaan sistem yang boleh dipilih untuk tipe "route"
     * (route => label). Sumbernya config/nav_menu.php.
     *
     * @return array<string, string>
     */
    public static function builtinRoutes(): array
    {
        return array_map(fn (array $meta) => $meta['label'], config('nav_menu.routes', []));
    }

    /** Metadata satu route bawaan (label, desc, bi, fa, active), atau [] kalau tidak dikenal. */
    private static function routeMeta(?string $routeName): array
    {
        // Jangan config('nav_menu.routes.' . $routeName): nama route berisi titik
        // (blog.index) dan akan dibaca sebagai kunci bertingkat.
        return config('nav_menu.routes', [])[(string) $routeName] ?? [];
    }

    /** Nama tampilan route bawaan; nama route-nya sendiri kalau tidak dikenal. */
    public function getRouteLabelAttribute(): ?string
    {
        return self::routeMeta($this->route_name)['label'] ?? $this->route_name;
    }

    /**
     * Subnav yang jadi tujuan LANGSUNG saat Menu Utama ini diklik di
     * navbar publik, dipakai untuk menu yang punya Subnav tapi tidak
     * ingin ditampilkan sebagai dropdown (lihat getDirectChildTargetAttribute).
     * Hanya relevan untuk Menu Utama (parent_id null).
     */
    public function defaultChild(): BelongsTo
    {
        return $this->belongsTo(NavMenu::class, 'default_child_id');
    }

    public function page(): BelongsTo
    {
        // Halaman CMS = baris cms_contents bertipe 'page'. Kolomnya page_id,
        // jadi FK disebut eksplisit (tebakan Eloquent akan salah: cms_page_id).
        return $this->belongsTo(CmsPage::class, 'page_id');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Cek apakah sebuah nama route bawaan (lihat config/nav_menu.php) sedang
     * terdaftar sebagai Menu Utama ATAU Submenu yang aktif -- dipakai
     * oleh halaman yang aksesnya sengaja digantungkan ke Menu Navigasi
     * (mis. Domain Premium), supaya halaman itu otomatis 404 kalau
     * belum/tidak lagi dipasang di admin/nav-menus atau
     * admin/nav-submenus, tanpa perlu saklar Aktif/Nonaktif terpisah
     * di Pengaturan.
     */
    public static function isRouteRegisteredAndActive(string $routeName): bool
    {
        return static::query()
            ->where('type', 'route')
            ->where('route_name', $routeName)
            ->where('is_active', true)
            ->exists();
    }

    /**
     * URL tujuan menu ini, atau null kalau tujuannya tidak lagi valid
     * (mis. halaman terkait sudah dihapus atau di-draf-kan). Item dengan
     * URL null sengaja disembunyikan di layout publik, bukan ditampilkan
     * sebagai tautan mati.
     */
    public function getResolvedUrlAttribute(): ?string
    {
        // Menu Utama yang di-setting untuk langsung menuju salah satu
        // Subnav-nya: tujuannya IKUT Subnav itu, bukan field type/route/
        // page/url milik menu ini sendiri (yang sengaja dikosongkan saat
        // mode ini aktif -- lihat NavMenuController::validated()).
        if ($this->parent_id === null && $this->direct_child_target) {
            return $this->direct_child_target->resolved_url;
        }

        return match ($this->type) {
            'route' => ($this->route_name && RouteFacade::has($this->route_name))
                ? route($this->route_name)
                : null,

            'page' => ($this->page && $this->page->is_published)
                ? route('page.show', $this->page->slug)
                : null,

            'url' => $this->url ?: null,

            default => null,
        };
    }

    /**
     * Kalau Menu Utama ini di-setting untuk langsung menuju salah satu
     * Subnav-nya (bukan menampilkan dropdown), accessor ini mengembalikan
     * Subnav tujuannya -- tapi hanya kalau Subnav itu masih benar-benar
     * berada di bawah menu ini dan tautannya masih valid. Kalau tidak,
     * kembalikan null supaya layout publik jatuh balik ke perilaku lama
     * (dropdown kalau punya Subnav, tautan sendiri kalau tidak).
     */
    public function getDirectChildTargetAttribute(): ?self
    {
        if (! $this->default_child_id) {
            return null;
        }

        $child = $this->defaultChild;

        // Cast eksplisit -- parent_id bisa balik sebagai string dari
        // sebagian driver DB, jangan pakai perbandingan ketat langsung.
        return ($child && (int) $child->parent_id === (int) $this->id && $child->resolved_url) ? $child : null;
    }

    public function getMegaIconAttribute(): string
    {
        return match ($this->type) {
            'route' => self::routeMeta($this->route_name)['bi'] ?? 'bi-grid-1x2',
            'page' => 'bi-file-earmark-text',
            default => 'bi-link-45deg',
        };
    }

    /** Versi Font Awesome dari mega_icon (dipakai tema default & modern). */
    public function getMegaFaIconAttribute(): string
    {
        return 'fa-solid ' . match ($this->type) {
            'route' => self::routeMeta($this->route_name)['fa'] ?? 'fa-table-cells-large',
            'page' => 'fa-file-lines',
            default => 'fa-link',
        };
    }

    public function getMegaDescriptionAttribute(): string
    {
        return match ($this->type) {
            'route' => self::routeMeta($this->route_name)['desc'] ?? 'Buka halaman',
            'page' => 'Halaman informasi',
            'url' => ($host = parse_url((string) $this->url, PHP_URL_HOST))
                ? preg_replace('/^www\./', '', $host)
                : 'Tautan',
            default => '',
        };
    }

    /**
     * Nama pola route untuk menandai menu yang sedang aktif di navigasi
     * (mis. semua URL /hosting/* menyorot menu "Hosting").
     */
    public function getActivePatternAttribute(): ?string
    {
        if ($this->parent_id === null && $this->direct_child_target) {
            return $this->direct_child_target->active_pattern;
        }

        return match ($this->type) {
            'route' => match ($this->route_name) {
                // Hosting & VPS berbagi nama route (catalog.category/product),
                // jadi dibedakan lewat parameter {section} di URL. Tanpa ini
                // pola 'catalog.*' menyalakan menu Hosting juga di /vps.
                'catalog.index' => self::catalogKind() === ProductType::KIND_VPS ? 'catalog.index' : 'catalog.*',
                'catalog.vps' => self::catalogKind() === ProductType::KIND_VPS ? 'catalog.*' : 'catalog.vps',
                default => self::routeMeta($this->route_name)['active'] ?? $this->route_name,
            },
            'page' => 'page.show',
            default => null,
        };
    }

    /** Apakah menu ini yang sedang dibuka (dipakai layout publik untuk kelas "active"). */
    public function getIsCurrentAttribute(): bool
    {
        $pattern = $this->active_pattern;

        return $pattern !== null && request()->routeIs($pattern);
    }

    /**
     * Perilaku (kind) katalog yang sedang dibuka: 'hosting' | 'vps' | null di
     * halaman non-katalog. /hosting dan /vps adalah route sendiri; halaman
     * kategori/produk memakai route bersama sehingga perilakunya dibaca dari
     * jenis produk (product_types.kind) milik slug {section} di URL -- data
     * admin, bukan tebakan dari teks URL. Dihitung sekali per request.
     */
    private static function catalogKind(): ?string
    {
        $request = request();

        if (! $request->attributes->has('catalog_kind')) {
            $route = $request->route();

            $request->attributes->set('catalog_kind', match ($route?->getName()) {
                'catalog.vps' => ProductType::KIND_VPS,
                'catalog.index' => ProductType::KIND_HOSTING,
                'catalog.category', 'catalog.product' => ProductType::kindOfSlug((string) $route->parameter('section')),
                default => null,
            });
        }

        return $request->attributes->get('catalog_kind');
    }
}
