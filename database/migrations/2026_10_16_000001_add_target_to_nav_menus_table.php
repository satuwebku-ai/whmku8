<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Kolom `target` hanya dipakai untuk tujuan katalog (type:/category:/product:).
     * Halaman CMS, halaman sistem, dan URL ketikan tetap di kolom lamanya
     * (page_id, route_name, url), jadi data yang sudah ada tidak perlu diubah.
     */
    public function up(): void
    {
        if (! Schema::hasColumn('nav_menus', 'target')) {
            Schema::table('nav_menus', function (Blueprint $table) {
                $table->string('target', 40)->nullable()->after('url');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('nav_menus', 'target')) {
            Schema::table('nav_menus', function (Blueprint $table) {
                $table->dropColumn('target');
            });
        }
    }
};
