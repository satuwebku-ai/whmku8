<?php

/*
|--------------------------------------------------------------------------
| Peta nama file migrasi lama => nama baru
|--------------------------------------------------------------------------
| Dipakai `php artisan lumora:sync-migrations` (otomatis dijalankan setelah
| `lumora:restore`) untuk menyelaraskan tabel `migrations` milik database lama
| / backup lama dengan nama file migrasi yang sekarang.
|
|   'nama_lama' => 'nama_baru'  -> baris riwayat diganti namanya
|   'nama_lama' => null         -> migrasinya sudah dilebur ke migrasi tabel
|                                  induknya; baris riwayat dibuang
|
| Tanpa ekstensi .php. Kolom/tabel yang dulu ditambahkan migrasi terpisah
| dilengkapi oleh App\Support\SchemaRepair, bukan oleh peta ini.
|
| Catatan: nama lama yang tidak tercantum di sini tetap ditangani otomatis oleh
| lumora:sync-migrations (dicocokkan lewat nama belakang file, atau dibuang bila
| file-nya sudah tidak ada), jadi peta ini cukup memuat kasus yang perlu eksplisit.
*/
return [
    // Dilebur ke create_invoice_items_table (service_type, service_id, item_type + index).
    '2026_01_01_000075_add_service_reference_to_invoice_items_table' => null,

    // Dilebur ke create_nav_menus_table (kolom target).
    '2026_10_16_000001_add_target_to_nav_menus_table' => null,
];
