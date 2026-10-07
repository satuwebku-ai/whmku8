<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductType extends Model
{
    /** Perilaku yang tersedia. Logika server/tagihan/URL hanya mengenal dua ini. */
    public const KINDS = [
        'hosting' => 'Hosting — server cPanel/WHM, tagihan invoice, URL /hosting/...',
        'vps'     => 'VPS / Cloud — server VM, tagihan deposit per jam, URL /vps/...',
    ];

    protected $fillable = ['slug', 'name', 'kind', 'icon', 'color', 'description', 'sort_order', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function categories(): HasMany
    {
        return $this->hasMany(ProductGroup::class, 'product_type_id');
    }

    public function kindLabel(): string
    {
        return $this->kind === 'vps' ? 'VPS / Cloud' : 'Hosting';
    }
}
