<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class ServerGroup extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'location', 'priority', 'status'];

    protected function casts(): array
    {
        return ['priority' => 'integer'];
    }

    protected static function booted(): void
    {
        static::saving(function (ServerGroup $group) {
            if (blank($group->slug)) {
                $group->slug = static::uniqueSlug($group->name, $group->id);
            } else {
                $group->slug = Str::slug($group->slug);
            }
        });
    }

    public static function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name) ?: 'server-group';
        $slug = $base;
        $suffix = 2;

        while (static::where('slug', $slug)->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))->exists()) {
            $slug = $base . '-' . $suffix++;
        }

        return $slug;
    }

    public function servers(): HasMany
    {
        return $this->hasMany(Server::class);
    }
}
