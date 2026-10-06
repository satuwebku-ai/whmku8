<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceLifecycleLog extends Model
{
    public const EVENTS = ['suspend', 'unsuspend', 'terminate'];

    protected $fillable = [
        'hosting_account_id', 'admin_id', 'event', 'reason', 'note', 'event_date', 'status',
    ];

    protected function casts(): array
    {
        return ['event_date' => 'datetime'];
    }

    public function hostingAccount(): BelongsTo
    {
        return $this->belongsTo(HostingAccount::class);
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }
}
