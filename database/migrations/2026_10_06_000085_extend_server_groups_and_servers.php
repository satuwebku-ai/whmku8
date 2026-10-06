<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('server_groups', function (Blueprint $table) {
            if (! Schema::hasColumn('server_groups', 'location')) {
                $table->string('location', 100)->nullable()->after('slug'); // ID, SG, US, EU
            }
            if (! Schema::hasColumn('server_groups', 'priority')) {
                $table->unsignedInteger('priority')->default(100)->after('location'); // kecil = dipilih lebih dulu
            }
            if (! Schema::hasColumn('server_groups', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('priority');
            }
        });

        Schema::table('servers', function (Blueprint $table) {
            if (! Schema::hasColumn('servers', 'ip_address')) {
                $table->string('ip_address', 45)->nullable()->after('hostname');
            }
            if (! Schema::hasColumn('servers', 'status')) {
                // active | maintenance | full. Server "full" juga dihitung otomatis dari max_accounts.
                $table->string('status', 20)->default('active')->after('is_active')->index();
            }
        });
    }

    public function down(): void
    {
        Schema::table('servers', function (Blueprint $table) {
            if (Schema::hasColumn('servers', 'status')) {
                $table->dropIndex(['status']); // SQLite menolak drop kolom yang masih ber-index
            }
        });
        Schema::table('servers', function (Blueprint $table) {
            foreach (['status', 'ip_address'] as $col) {
                if (Schema::hasColumn('servers', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
        Schema::table('server_groups', function (Blueprint $table) {
            foreach (['is_active', 'priority', 'location'] as $col) {
                if (Schema::hasColumn('server_groups', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
