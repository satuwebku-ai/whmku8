<?php

namespace App\Services\Provisioning;

use App\Models\Product;
use App\Models\Server;

/**
 * Memilih server tujuan sesuai diagram: Product -> Server Group -> Server.
 * Urutan: group aktif berprioritas tertinggi (angka terkecil), lalu server
 * aktif yang belum penuh dengan pemakaian terendah.
 */
class ServerSelector
{
    public function forProduct(Product $product): ?Server
    {
        $configured = $product->server_id ? Server::find($product->server_id) : null;

        // Server VPS/cloud dikelola per-provider; jangan dipindah otomatis.
        if ($configured && $configured->isCloud()) {
            return $configured->effectiveStatus() === Server::STATUS_ACTIVE ? $configured : null;
        }

        $groupId = $configured?->server_group_id;
        if ($groupId) {
            $picked = $this->pickFromGroup($groupId);
            if ($picked) {
                return $picked;
            }
        }

        // Tanpa group, atau semua server group penuh: pakai server yang
        // dikonfigurasi di produk bila masih menerima akun.
        return ($configured && $configured->effectiveStatus() === Server::STATUS_ACTIVE) ? $configured : null;
    }

    public function pickFromGroup(int $groupId): ?Server
    {
        return Server::query()
            ->acceptingAccounts()
            ->where('server_group_id', $groupId)
            ->whereHas('group', fn ($q) => $q->where('is_active', true))
            ->get()
            ->filter(fn (Server $s) => ! $s->isCloud() && ! $s->isFull())
            ->sortBy(fn (Server $s) => $s->currentUsage())
            ->first();
    }
}
