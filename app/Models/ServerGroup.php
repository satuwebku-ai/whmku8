<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * Pengelompokan server (mis. "Jakarta", "Singapore", "Reseller") agar
 * server mudah dipilah di daftar Server dan di pilihan "Server Tujuan"
 * pada form Produk. Satu grup bisa berisi server dengan panel berbeda.
 */
class ServerGroup extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'is_active', 'sort_order'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    protected static function booted(): void
    {
        static::saving(function (ServerGroup $group) {
            $group->slug = blank($group->slug)
                ? static::uniqueSlug($group->name, $group->id)
                : Str::slug($group->slug);
        });
    }

    public static function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name) ?: 'grup';
        $slug = $base;
        $i = 2;

        while (static::where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }

    /** Cari grup berdasarkan nama (tanpa peduli huruf besar/kecil), buat kalau belum ada. */
    public static function findOrCreateByName(string $name): self
    {
        $name = trim($name);

        return static::whereRaw('LOWER(name) = ?', [mb_strtolower($name)])->first()
            ?? static::create(['name' => $name]);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function servers(): HasMany
    {
        return $this->hasMany(Server::class);
    }
}
