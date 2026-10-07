<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServerGroup extends Model
{
    /** Cara memilih server dalam satu grup. */
    public const MODES = [
        'least_accounts' => 'Paling sedikit akun (server paling lega dulu)',
        'priority'       => 'Prioritas (isi server berprioritas tertinggi sampai penuh)',
        'round_robin'    => 'Bergiliran (server yang paling lama tidak kebagian order)',
    ];

    protected $fillable = ['name', 'slug', 'description', 'selection_mode', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function servers(): HasMany
    {
        return $this->hasMany(Server::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function modeLabel(): string
    {
        return self::MODES[$this->selection_mode] ?? $this->selection_mode;
    }

    /**
     * Pilih server yang akan menerima order baru, sesuai selection_mode.
     * Hanya server yang aktif, tidak maintenance, dan belum penuh
     * (max_accounts) yang ikut dipertimbangkan. Null kalau tidak ada.
     */
    public function pickServer(): ?Server
    {
        if (! $this->is_active) {
            return null;
        }

        $candidates = $this->servers()
            ->acceptingNewAccounts()
            ->withCount(['hostingAccounts as active_accounts_count' => fn ($q) => $q->whereNotIn('status', Server::INACTIVE_ACCOUNT_STATUSES)])
            ->withMax('hostingAccounts as last_assigned_at', 'created_at')
            ->get()
            ->filter(fn (Server $s) => $s->max_accounts === null || $s->active_accounts_count < $s->max_accounts);

        if ($candidates->isEmpty()) {
            return null;
        }

        $sorted = match ($this->selection_mode) {
            // Prioritas dulu; kalau seri, yang lebih lega.
            'priority' => $candidates->sortBy([['priority', 'asc'], ['active_accounts_count', 'asc'], ['id', 'asc']]),
            // Yang terakhir kebagian order paling lama (atau belum pernah) maju duluan.
            'round_robin' => $candidates->sortBy([['last_assigned_at', 'asc'], ['id', 'asc']]),
            // Bawaan: paling sedikit akun; kalau seri, prioritas lalu id.
            default => $candidates->sortBy([['active_accounts_count', 'asc'], ['priority', 'asc'], ['id', 'asc']]),
        };

        return $sorted->first();
    }
}
