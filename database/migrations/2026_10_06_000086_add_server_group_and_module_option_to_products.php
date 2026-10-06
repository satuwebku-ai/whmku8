<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Seperti "Server Group" + "Module Option" di sistem contoh:
            // produk menunjuk ke GRUP server, server dipilih otomatis dari grup itu.
            $table->foreignId('server_group_id')->nullable()->after('server_id')->constrained('server_groups')->nullOnDelete();
            // automation = provisioning otomatis begitu invoice lunas
            // semi       = server ditentukan otomatis, tapi admin yang menekan "Aktifkan"
            // manual     = tanpa server/provisioning otomatis
            $table->string('module_option', 20)->default('automation')->after('server_group_id');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropConstrainedForeignId('server_group_id');
            $table->dropColumn('module_option');
        });
    }
};
