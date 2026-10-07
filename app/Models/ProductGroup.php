<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class ProductGroup extends Model
{
    use HasFactory;

    /**
     * Nama tabel dinyatakan eksplisit karena nama file migrasi historisnya
     * masih menyebut product_categories, sementara tabel aktual adalah
     * product_groups. Jangan mengganti nama migrasi pada database berjalan.
     */
    protected $table = 'product_groups';

    protected $fillable = ['name', 'slug', 'type', 'product_type_id', 'description', 'icon', 'is_active', 'sort_order'];

    /**
     * Jenis produk selalu ikut dimuat: segmen URL publik kategori ini
     * diambil dari product_types.slug (data yang diisi admin), bukan
     * ditentukan di kode.
     */
    protected $with = ['productType'];

    public function publicUrl(): string
    {
        return $this->productType
            ? route('catalog.category', [$this->productType->slug, $this->slug])
            : route('catalog.index');
    }

    public function productUrl(Product $product): string
    {
        return $this->productType
            ? route('catalog.product', [$this->productType->slug, $this->slug, $product->slug])
            : route('catalog.index');
    }

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    protected static function booted(): void
    {
        static::saving(function (ProductGroup $category) {
            // Kategori yang dibuat tanpa memilih jenis (seeder, impor, dsb)
            // otomatis memakai jenis pertama dengan perilaku yang sama.
            if (blank($category->product_type_id)) {
                $category->product_type_id = ProductType::where('kind', $category->type ?? 'hosting')
                    ->orderBy('sort_order')->value('id');
            }

            if (blank($category->slug)) {
                $category->slug = static::uniqueSlug($category->name, $category->id);
            } else {
                $category->slug = Str::slug($category->slug);
            }
        });
    }

    public static function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name) ?: 'kategori';
        $slug = $base;
        $i = 2;

        while (static::where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }

    public function products(): HasMany
    {
        // Nama FK lama dipertahankan agar kompatibel dengan data dan relasi
        // produk yang sudah berjalan.
        return $this->hasMany(Product::class, 'product_category_id');
    }

    /** Jenis produk (data di database) yang dipilih admin untuk kategori ini. */
    public function productType(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ProductType::class, 'product_type_id');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
