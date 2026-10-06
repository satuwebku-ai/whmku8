<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Akun admin awal (dipakai AdminSeeder)
    |--------------------------------------------------------------------------
    | Isi lewat .env supaya tidak ada kredensial di kode sumber. Kalau
    | ADMIN_PASSWORD kosong, seeder membuat password acak dan menampilkannya
    | SEKALI di layar.
    */
    'admin' => [
        'email' => env('ADMIN_EMAIL'),
        'password' => env('ADMIN_PASSWORD'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Domain publik dan portal klien
    |--------------------------------------------------------------------------
    | Host harus berisi nama host saja (tanpa skema atau path), misalnya
    | domain-anda.com dan client.domain-anda.com.
    */
    'public_site_host' => env('PUBLIC_SITE_HOST'),
    'client_portal_host' => env('CLIENT_PORTAL_HOST'),

];
