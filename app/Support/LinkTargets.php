<?php

namespace App\Support;

use App\Models\CmsPage;
use App\Models\NavMenu;
use App\Models\Product;
use App\Models\ProductGroup;
use App\Models\ProductType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

/**
 * SATU daftar "tujuan tautan" untuk menu navbar (dan nanti menu footer).
 *
 * Nilai tujuan berupa teks berawalan:
 *   page:12            Halaman CMS (Halaman Saya)
 *   type:3             Jenis produk   (mis. Hosting, VPS)
 *   category:5         Kategori produk
 *   product:9          Produk
 *   route:blog.index   Halaman sistem (config/nav_menu.php)
 *   custom             Alamat ketikan admin (URL penuh atau path "/hosting")
 *
 * Tujuan disimpan sebagai REFERENSI (id), bukan teks alamat, sehingga kalau slug
 * atau nama berubah menu tetap benar, dan kalau tujuannya dinonaktifkan/dihapus
 * url() mengembalikan null (menu disembunyikan, bukan tautan mati).
 */
class LinkTargets
{
    public const CUSTOM = 'custom';

    /** Jenis tujuan yang menunjuk ke satu baris database. */
    private const MODEL_KINDS = ['page', 'type', 'category', 'product'];

    /**
     * Untuk dropdown "Tujuan": ['Nama Grup' => ['nilai' => 'Label']].
     * Grup yang kosong tidak ikut ditampilkan.
     *
     * @return array<string, array<string, string>>
     */
    public static function groups(): array
    {
        $groups = [
            'Halaman Saya' => CmsPage::published()->orderBy('title')->get()
                ->mapWithKeys(fn ($p) => ["page:{$p->id}" => $p->title])->all(),

            'Jenis Produk' => ProductType::where('is_active', true)
                ->orderBy('sort_order')->orderBy('name')->get()
                ->mapWithKeys(fn ($t) => ["type:{$t->id}" => $t->name])->all(),

            'Kategori Produk' => ProductGroup::active()->orderBy('name')->get()
                ->mapWithKeys(fn ($g) => ["category:{$g->id}" => trim(($g->productType ? $g->productType->name . ' › ' : '') . $g->name)])->all(),

            'Produk' => Product::active()->orderBy('name')->get()
                ->mapWithKeys(fn ($p) => ["product:{$p->id}" => $p->name])->all(),

            'Halaman Sistem' => collect(NavMenu::builtinRoutes())
                ->reject(fn ($label, $key) => $key === '#')
                ->mapWithKeys(fn ($label, $key) => ["route:{$key}" => $label])->all(),

            'Lainnya' => [
                'route:#' => 'Tanpa tautan (#)',
                self::CUSTOM => 'Tulis sendiri (URL / path)…',
            ],
        ];

        return array_filter($groups);
    }

    /**
     * Ubah nilai tujuan menjadi alamat. Null = tujuan tidak (lagi) valid.
     */
    public static function url(?string $target, ?string $custom = null): ?string
    {
        if ($target === self::CUSTOM) {
            return filled($custom) ? $custom : null;
        }

        [$kind, $id] = self::parse($target);

        if ($kind === 'route') {
            return ($id && Route::has($id)) ? route($id) : null;
        }

        $model = self::find($target);

        if (! $model) {
            return null;
        }

        return match ($kind) {
            'page'     => route('page.show', $model->slug),
            // Halaman indeks katalog hanya ada untuk dua perilaku: hosting dan VPS.
            // Dipetakan lewat perilaku (kind), bukan slug, supaya tetap benar
            // walau admin mengganti slug jenis produknya.
            'type'     => $model->isVps() ? route('catalog.vps') : route('catalog.index'),
            'category' => $model->publicUrl(),
            'product'  => $model->category ? $model->category->productUrl($model, true) : null,
            default    => null,
        };
    }

    /** Nama tampilan tujuan (untuk daftar di admin), atau null bila sudah tidak ada. */
    public static function label(?string $target): ?string
    {
        [$kind, $id] = self::parse($target);

        if ($kind === 'route') {
            return $id ? (NavMenu::builtinRoutes()[$id] ?? $id) : null;
        }

        $model = self::find($target);

        return match ($kind) {
            'page'                         => $model?->title,
            'type', 'category', 'product'  => $model?->name,
            default                        => null,
        };
    }

    /** Keterangan singkat untuk kartu mega menu, atau null. */
    public static function description(?string $target): ?string
    {
        [$kind] = self::parse($target);
        $model = self::find($target);

        $text = match ($kind) {
            'product'          => $model?->tagline,
            'category', 'type' => $model?->description,
            default            => null,
        };

        return filled($text) ? Str::limit(strip_tags((string) $text), 60) : null;
    }

    /** Baris database yang ditunjuk, atau null (jenis route/custom tidak punya baris). */
    public static function find(?string $target): ?Model
    {
        [$kind, $id] = self::parse($target);

        if (! in_array($kind, self::MODEL_KINDS, true) || ! ctype_digit((string) $id)) {
            return null;
        }

        return match ($kind) {
            'page'     => CmsPage::published()->find($id),
            'type'     => ProductType::where('is_active', true)->find($id),
            'category' => ProductGroup::active()->find($id),
            'product'  => Product::active()->with('category')->find($id),
        };
    }

    /** @return array{0: ?string, 1: ?string} */
    private static function parse(?string $target): array
    {
        [$kind, $id] = array_pad(explode(':', (string) $target, 2), 2, null);

        return [$kind ?: null, $id];
    }
}
