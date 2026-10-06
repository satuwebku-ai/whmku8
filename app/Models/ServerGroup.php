<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServerGroup extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'location', 'priority', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean', 'priority' => 'integer'];
    }

    public function servers(): HasMany
    {
        return $this->hasMany(Server::class);
    }
}
