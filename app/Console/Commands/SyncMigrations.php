<?php

namespace App\Console\Commands;

use App\Support\SchemaRepair;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Menyelaraskan tabel `migrations` di database yang SUDAH berjalan dengan
 * nama file migrasi hasil penataan ulang (lihat database/migration-renames.php).
 *
 * Jalankan SEKALI, SEBELUM `php artisan migrate`, pada database lama.
 * Aman diulang (idempoten) dan tidak menyentuh tabel data apa pun kecuali
 * dengan opsi --drop-unused-tables (hanya tabel kosong yang tidak dipakai).
 *
 * Setelah riwayat diselaraskan, perintah ini juga melengkapi skema yang
 * ketinggalan (lihat App\Support\SchemaRepair): kolom/tabel yang dulu
 * ditambahkan migrasi terpisah lalu dilebur ke migrasi induknya tidak akan
 * pernah dibuat lagi oleh `migrate` di database yang induknya sudah tercatat
 * selesai -- mis. hasil restore backup lama. Otomatis ikut berjalan setelah
 * `lumora:restore`.
 */
class SyncMigrations extends Command
{
    protected $signature = 'lumora:sync-migrations
                            {--dry-run : Tampilkan perubahan tanpa menyimpannya}
                            {--no-repair : Lewati pelengkapan kolom/tabel yang hilang di backup lama}
                            {--drop-unused-tables : Hapus juga tabel kosong yang tidak dipakai lagi (hosting_packages, ticket_departments)}';

    protected $description = 'Sesuaikan riwayat migrasi database lama dengan nama file migrasi yang baru (jalankan sekali sebelum migrate)';

    /** Tabel yang dulu dibuat migrasi tapi tidak dipakai kode mana pun. */
    private const UNUSED_TABLES = ['hosting_packages', 'ticket_departments'];

    public function handle(): int
    {
        if (! Schema::hasTable('migrations')) {
            $this->info('Tabel migrations belum ada — ini instalasi baru, tidak ada yang perlu disinkronkan.');

            return self::SUCCESS;
        }

        $mapFile = database_path('migration-renames.php');
        $map = is_file($mapFile) ? (array) require $mapFile : [];
        $dry = (bool) $this->option('dry-run');
        $renamed = $removed = $skipped = 0;

        DB::transaction(function () use ($map, $dry, &$renamed, &$removed, &$skipped) {
            foreach ($map as $old => $new) {
                $oldRow = DB::table('migrations')->where('migration', $old)->first();

                if (! $oldRow) {
                    $skipped++;
                    continue;
                }

                if ($new === null || DB::table('migrations')->where('migration', $new)->exists()) {
                    $this->line("  hapus   {$old}");
                    $removed++;
                    if (! $dry) {
                        DB::table('migrations')->where('id', $oldRow->id)->delete();
                    }
                    continue;
                }

                $this->line("  ganti   {$old}\n       ->  {$new}");
                $renamed++;
                if (! $dry) {
                    DB::table('migrations')->where('id', $oldRow->id)->update(['migration' => $new]);
                }
            }
        });

        // Baris riwayat yang file-nya sudah tidak ada dan tidak tercantum di peta
        // (mis. backup dari versi yang lebih lama).
        [$matched, $stale] = $this->reconcileUnknownHistory($dry);
        $renamed += $matched;
        $removed += $stale;

        if ($this->option('drop-unused-tables')) {
            foreach (self::UNUSED_TABLES as $table) {
                if (! Schema::hasTable($table)) {
                    continue;
                }

                if (DB::table($table)->exists()) {
                    $this->warn("  lewati tabel {$table}: masih berisi data.");
                    continue;
                }

                $this->line("  drop    tabel kosong {$table}");
                if (! $dry) {
                    Schema::drop($table);
                }
            }
        }

        $this->info(($dry ? '[dry-run] ' : '') . "Selesai: {$renamed} diganti nama, {$removed} dibuang, {$skipped} tidak ada di database ini.");

        if (! $this->option('no-repair')) {
            $this->repairSchema($dry);
        }

        $this->line('Sekarang jalankan: php artisan migrate');

        return self::SUCCESS;
    }

    /**
     * Riwayat yang menunjuk file migrasi yang sudah tidak ada:
     *  - ada tepat satu file bernama belakang sama (mis. create_invoice_items_table
     *    dengan nomor urut lain) dan belum tercatat -> baris diganti namanya, supaya
     *    `migrate` tidak mencoba membuat ulang tabel yang sudah ada;
     *  - selain itu (migrasi add_* yang sudah dilebur ke induknya) -> baris dibuang.
     *
     * @return array{0: int, 1: int} [diganti nama, dibuang]
     */
    private function reconcileUnknownHistory(bool $dry): array
    {
        $files = collect(glob(database_path('migrations/*.php')) ?: [])
            ->map(fn (string $file) => basename($file, '.php'))
            ->values();

        $byTail = $files->groupBy(fn (string $name) => $this->tail($name));
        $known = $files->flip();
        $renamed = $removed = 0;

        $rows = DB::table('migrations')->get(['id', 'migration'])
            ->reject(fn ($row) => $known->has($row->migration));

        foreach ($rows as $row) {
            $candidates = $byTail->get($this->tail($row->migration), collect());
            $target = $candidates->count() === 1 ? $candidates->first() : null;

            if ($target !== null && ! DB::table('migrations')->where('migration', $target)->exists()) {
                $this->line("  ganti   {$row->migration}\n       ->  {$target}");
                $renamed++;

                if (! $dry) {
                    DB::table('migrations')->where('id', $row->id)->update(['migration' => $target]);
                }

                continue;
            }

            $this->line("  hapus   {$row->migration} (file migrasinya sudah tidak ada)");
            $removed++;

            if (! $dry) {
                DB::table('migrations')->where('id', $row->id)->delete();
            }
        }

        return [$renamed, $removed];
    }

    /** Nama migrasi tanpa awalan tanggal + nomor urut (2026_01_01_000026_). */
    private function tail(string $migration): string
    {
        return preg_replace('/^\d{4}_\d{2}_\d{2}_\d{6}_/', '', $migration);
    }

    /** Lengkapi kolom/tabel yang hilang (database lama / hasil restore backup lama). */
    private function repairSchema(bool $dry): void
    {
        $this->line('');
        $this->line('Memeriksa kolom/tabel yang hilang (backup lama)...');

        $actions = SchemaRepair::run($dry);

        if ($actions === []) {
            $this->info('  skema sudah lengkap.');

            return;
        }

        foreach ($actions as $action) {
            $this->line(($dry ? '  perlu     ' : '  diperbaiki ') . $action);
        }

        $this->info(($dry ? '[dry-run] ' : '') . count($actions) . ' perbaikan skema' . ($dry ? ' diperlukan.' : ' dijalankan.'));
    }
}
