<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Jenis kategori produk: dari enum('hosting','vps') jadi teks bebas,
        // supaya admin bisa mengetik jenis sendiri (mis. "dedicated").
        Schema::table('product_groups', function (Blueprint $table) {
            $table->string('type', 50)->default('hosting')->change();
        });

        // Jenis panel server: muat nama panel kustom yang diketik manual.
        Schema::table('servers', function (Blueprint $table) {
            $table->string('panel', 50)->default('cpanel')->change();
        });

        Schema::table('server_groups', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('description');
            $table->unsignedInteger('sort_order')->default(0)->after('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('server_groups', function (Blueprint $table) {
            $table->dropColumn(['is_active', 'sort_order']);
        });

        Schema::table('servers', function (Blueprint $table) {
            $table->string('panel', 20)->default('cpanel')->change();
        });

        // Tipe kustom tidak muat di enum lama -> kembalikan ke 'hosting'.
        \DB::table('product_groups')->whereNotIn('type', ['hosting', 'vps'])->update(['type' => 'hosting']);
        Schema::table('product_groups', function (Blueprint $table) {
            $table->enum('type', ['hosting', 'vps'])->default('hosting')->change();
        });
    }
};
