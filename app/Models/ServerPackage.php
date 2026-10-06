<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServerPackage extends Model
{
    protected $fillable = [
        'server_id', 'name', 'disk_limit_mb', 'bandwidth_limit_mb', 'cpu_limit', 'ram_limit_mb', 'is_active',
    ];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function server(): BelongsTo
    {
        return $this->belongsTo(Server::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
