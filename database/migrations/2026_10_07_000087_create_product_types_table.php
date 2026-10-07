<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * "Jenis Produk" jadi data di database yang dikelola admin (bukan lagi
     * daftar yang tertulis di kode). Tiap jenis punya "perilaku" (kind):
     *   hosting = server cPanel/WHM, tagihan invoice, URL /hosting/...
     *   vps     = server VM/cloud, tagihan deposit/jam, URL /vps/...
     * Kolom lama product_groups.type (enum hosting|vps) TETAP ada dan diisi
     * otomatis dari kind jenis yang dipilih, sehingga logika yang sudah
     * berjalan (server, tagihan, katalog) tidak berubah.
     */
    public function up(): void
    {
        Schema::create('product_types', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 60)->unique();
            $table->string('name');
            $table->string('kind', 20)->default('hosting'); // hosting | vps
            $table->string('icon', 50)->nullable();         // class Font Awesome, mis. fa-server
            $table->string('color', 7)->default('#4f46e5'); // warna tab di halaman Produk
            $table->text('description')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::table('product_groups', function (Blueprint $table) {
            $table->foreignId('product_type_id')->nullable()->after('type')
                ->constrained('product_types')->nullOnDelete();
        });

        $now = now();

        $hostingId = DB::table('product_types')->insertGetId([
            'slug' => 'hosting', 'name' => 'Hosting (cPanel/WHM)', 'kind' => 'hosting',
            'icon' => 'fa-server', 'color' => '#4f46e5', 'sort_order' => 1,
            'is_active' => true, 'created_at' => $now, 'updated_at' => $now,
        ]);

        $vpsId = DB::table('product_types')->insertGetId([
            'slug' => 'vps', 'name' => 'VPS / Cloud Server', 'kind' => 'vps',
            'icon' => 'fa-cloud', 'color' => '#059669', 'sort_order' => 2,
            'is_active' => true, 'created_at' => $now, 'updated_at' => $now,
        ]);

        // Kategori yang sudah ada diarahkan ke jenis bawaan sesuai type lamanya.
        DB::table('product_groups')->where('type', 'vps')->update(['product_type_id' => $vpsId]);
        DB::table('product_groups')->where(fn ($q) => $q->where('type', 'hosting')->orWhereNull('type'))
            ->update(['product_type_id' => $hostingId]);
    }

    public function down(): void
    {
        Schema::table('product_groups', function (Blueprint $table) {
            $table->dropConstrainedForeignId('product_type_id');
        });

        Schema::dropIfExists('product_types');
    }
};
