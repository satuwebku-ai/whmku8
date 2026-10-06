<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Provisioning extends Model
{
    protected $fillable = [
        'order_id', 'hosting_account_id', 'server_id', 'server_package_id',
        'attempt_number', 'status', 'message', 'started_at', 'finished_at',
    ];

    protected function casts(): array
    {
        return [
            'attempt_number' => 'integer',
            'started_at' => 'datetime',
            'finished_at' => 'datetime',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function hostingAccount(): BelongsTo
    {
        return $this->belongsTo(HostingAccount::class);
    }

    public function server(): BelongsTo
    {
        return $this->belongsTo(Server::class);
    }

    public function serverPackage(): BelongsTo
    {
        return $this->belongsTo(ServerPackage::class);
    }
}
