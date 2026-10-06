<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HostingAccountLog extends Model
{
    protected $fillable = ['hosting_account_id', 'admin_id', 'action', 'message'];

    protected static function booted(): void
    {
        // Setiap catatan suspend/unsuspend/terminate (dari scheduler maupun admin)
        // otomatis dicerminkan ke tabel terstruktur service_lifecycle_logs.
        static::created(function (self $log) {
            if (! in_array($log->action, ServiceLifecycleLog::EVENTS, true)) {
                return;
            }
            $message = (string) $log->message;
            $reason = match (true) {
                $log->admin_id === null && str_contains(mb_strtolower($message), 'invoice') => 'overdue',
                str_contains(mb_strtolower($message), 'permintaan') => 'request',
                default => 'other',
            };
            ServiceLifecycleLog::create([
                'hosting_account_id' => $log->hosting_account_id,
                'admin_id'           => $log->admin_id,
                'event'              => $log->action,
                'reason'             => $reason,
                'note'               => $message ?: null,
                'event_date'         => $log->created_at ?? now(),
                'status'             => 'success',
            ]);
        });
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
