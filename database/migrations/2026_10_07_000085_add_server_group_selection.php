<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('server_groups', function (Blueprint $table) {
            // Cara memilih server saat order baru masuk: least_accounts | priority | round_robin
            $table->string('selection_mode', 30)->default('least_accounts')->after('description');
            $table->boolean('is_active')->default(true)->after('selection_mode');
        });

        Schema::table('servers', function (Blueprint $table) {
            // Angka kecil = prioritas lebih tinggi (dipakai mode "priority" dan sebagai pemutus seri).
            $table->unsignedSmallInteger('priority')->default(10)->after('max_accounts');
            // Maintenance: tidak menerima order BARU, tapi akun yang sudah ada tetap bisa dikelola.
            $table->boolean('is_maintenance')->default(false)->after('is_active');
        });

        Schema::table('products', function (Blueprint $table) {
            // Kalau diisi, server dipilih otomatis dari grup ini (menggantikan server_id tunggal).
            $table->foreignId('server_group_id')->nullable()->after('server_id')
                ->constrained('server_groups')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropConstrainedForeignId('server_group_id');
        });

        Schema::table('servers', function (Blueprint $table) {
            $table->dropColumn(['priority', 'is_maintenance']);
        });

        Schema::table('server_groups', function (Blueprint $table) {
            $table->dropColumn(['selection_mode', 'is_active']);
        });
    }
};
