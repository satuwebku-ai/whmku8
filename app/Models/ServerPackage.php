<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServerPackage extends Model
{
    protected $fillable = [
        'server_id', 'name', 'disk_limit', 'bandwidth_limit',
        'cpu_limit', 'ram_limit', 'price', 'status',
    ];

    protected function casts(): array
    {
        return [
            'disk_limit' => 'integer',
            'bandwidth_limit' => 'integer',
            'cpu_limit' => 'integer',
            'ram_limit' => 'integer',
            'price' => 'decimal:2',
        ];
    }

    public function server(): BelongsTo
    {
        return $this->belongsTo(Server::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function hostingAccounts(): HasMany
    {
        return $this->hasMany(HostingAccount::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }
}
