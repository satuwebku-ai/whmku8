<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Keanggotaan grup jadi many-to-many: satu server boleh ada di beberapa
     * grup (mis. "Shared Hosting" dan "Promo"), dengan prioritas per grup.
     *
     * Kolom lama servers.server_group_id TIDAK dihapus (aman untuk rollback),
     * tapi tidak dipakai lagi -- isinya disalin ke tabel pivot di sini.
     */
    public function up(): void
    {
        Schema::create('server_group_server', function (Blueprint $table) {
            $table->id();
            $table->foreignId('server_group_id')->constrained('server_groups')->cascadeOnDelete();
            $table->foreignId('server_id')->constrained('servers')->cascadeOnDelete();
            // Angka kecil = didahulukan (dipakai mode "priority" dan pemutus seri).
            $table->unsignedSmallInteger('priority')->default(10);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['server_group_id', 'server_id']);
        });

        $now = now();

        DB::table('servers')
            ->whereNotNull('server_group_id')
            ->get(['id', 'server_group_id', 'priority'])
            ->each(function ($server) use ($now) {
                DB::table('server_group_server')->insert([
                    'server_group_id' => $server->server_group_id,
                    'server_id'       => $server->id,
                    'priority'        => $server->priority ?? 10,
                    'is_active'       => true,
                    'created_at'      => $now,
                    'updated_at'      => $now,
                ]);
            });
    }

    public function down(): void
    {
        Schema::dropIfExists('server_group_server');
    }
};
