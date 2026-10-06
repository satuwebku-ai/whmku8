<?php

namespace App\Services\Hosting;

use App\Models\Server;
use App\Models\ServerPackage;

/**
 * Menarik daftar plan dari panel (WHM listpkgs) ke tabel server_packages.
 * Hanya menambah/memperbarui disk & bandwidth; tidak pernah menghapus package
 * lokal dan tidak menyentuh CPU/RAM (tidak disediakan listpkgs) maupun status.
 */
class ServerPackageSyncService
{
    /**
     * @param object|null $panel objek dengan listPackages() -- diinjeksi untuk test.
     * @return array{success: bool, message: string, created: int, updated: int, missing: array<int, string>}
     */
    public function sync(Server $server, ?object $panel = null): array
    {
        $panel ??= HostingPanelFactory::make($server);

        if (! method_exists($panel, 'listPackages')) {
            return $this->fail('Panel ' . $server->panel . ' belum mendukung sinkronisasi package.');
        }

        try {
            $result = $panel->listPackages();
        } catch (\Throwable $e) {
            return $this->fail($e->getMessage());
        }

        if (! ($result['success'] ?? false)) {
            return $this->fail($result['message'] ?? 'Gagal mengambil daftar package.');
        }

        $created = $updated = 0;
        $seen = [];

        foreach ((array) ($result['raw'] ?? []) as $row) {
            $name = is_array($row) ? ($row['name'] ?? $row['pkgname'] ?? null) : null;
            if (! is_string($name) || $name === '') {
                continue;
            }
            $seen[] = $name;

            $package = ServerPackage::firstOrNew(['server_id' => $server->id, 'name' => $name]);
            $isNew = ! $package->exists;
            $package->disk_limit_mb = self::parseLimit($row['QUOTA'] ?? null);
            $package->bandwidth_limit_mb = self::parseLimit($row['BWLIMIT'] ?? null);
            if ($isNew) {
                $package->is_active = true;
            }

            if ($isNew || $package->isDirty()) {
                $package->save();
                $isNew ? $created++ : $updated++;
            }
        }

        $missing = $server->packages()->whereNotIn('name', $seen)->orderBy('name')->pluck('name')->all();

        return [
            'success' => true,
            'message' => 'OK',
            'created' => $created,
            'updated' => $updated,
            'missing' => $missing,
        ];
    }

    /** "unlimited"/"0" -> 0 (unlimited); angka -> int; kosong/tak dikenali -> null. */
    public static function parseLimit(mixed $value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }
        if (is_string($value) && strtolower(trim($value)) === 'unlimited') {
            return 0;
        }

        return is_numeric($value) ? max(0, (int) round((float) $value)) : null;
    }

    private function fail(string $message): array
    {
        return ['success' => false, 'message' => $message, 'created' => 0, 'updated' => 0, 'missing' => []];
    }
}
