<?php

/*
| Halaman bawaan sistem yang boleh dipilih untuk menu bertipe "route".
|
| SATU daftar untuk semua kebutuhan menu: label di form admin, ikon &
| keterangan kartu Mega Menu, dan pola penanda menu aktif. Menambah halaman
| bawaan baru cukup menambah satu blok di sini.
|
| Dibatasi ke daftar ini (bukan nama route bebas) supaya admin tidak bisa
| memasukkan nama route yang tidak ada dan mematahkan menu.
|
| Keranjang (cart.index) SENGAJA tidak dimasukkan -- itu bagian dari proses
| checkout (dipicu tombol "Tambah ke Keranjang" dan ikon keranjang di header),
| bukan halaman untuk dijelajah lewat menu navigasi.
|
| Kunci per route:
|   label  nama di form admin
|   desc   keterangan singkat di kartu Mega Menu
|   bi     ikon Bootstrap Icons (tema NamaHost)
|   fa     ikon Font Awesome (tema default & modern)
|   active pola routeIs() penanda menu aktif; kosongkan untuk memakai nama
|          route itu sendiri. Hosting & VPS ditangani khusus di
|          NavMenu::getActivePatternAttribute() karena berbagi route.
*/
return [
    'routes' => [
        '#' => [
            'label' => '# (Tanpa Tautan)', 'desc' => 'Tanpa tautan',
            'bi' => 'bi-link-45deg', 'fa' => 'fa-link',
        ],
        'home' => [
            'label' => 'Beranda', 'desc' => 'Kembali ke beranda',
            'bi' => 'bi-house-door', 'fa' => 'fa-house',
        ],
        'catalog.index' => [
            'label' => 'Katalog Hosting', 'desc' => 'Lihat semua paket server',
            'bi' => 'bi-hdd-rack', 'fa' => 'fa-server',
        ],
        'catalog.vps' => [
            'label' => 'Katalog VPS / Cloud Server', 'desc' => 'Cloud server dan VPS',
            'bi' => 'bi-cloud', 'fa' => 'fa-cloud',
        ],
        'domain.search' => [
            'label' => 'Cek Domain', 'desc' => 'Cari dan daftarkan domain',
            'bi' => 'bi-globe2', 'fa' => 'fa-globe', 'active' => 'domain.*',
        ],
        'domains.transfer' => [
            'label' => 'Transfer Domain', 'desc' => 'Pindahkan domain ke sini',
            'bi' => 'bi-arrow-left-right', 'fa' => 'fa-right-left', 'active' => 'domains.*',
        ],
        'domain-premium.index' => [
            'label' => 'Domain Premium', 'desc' => 'Nama domain pilihan',
            'bi' => 'bi-gem', 'fa' => 'fa-gem', 'active' => 'domain-premium.*',
        ],
        'license.index' => [
            'label' => 'Lisensi', 'desc' => 'Lisensi software resmi',
            'bi' => 'bi-key', 'fa' => 'fa-key', 'active' => 'license.*',
        ],
        'promo.index' => [
            'label' => 'Promo & Diskon', 'desc' => 'Kode promo dan diskon terbaru',
            'bi' => 'bi-tag', 'fa' => 'fa-tags', 'active' => 'promo.*',
        ],
        'announcements.index' => [
            'label' => 'Pengumuman', 'desc' => 'Kabar dan info terbaru',
            'bi' => 'bi-megaphone', 'fa' => 'fa-bullhorn', 'active' => 'announcements.*',
        ],
        'blog.index' => [
            'label' => 'Blog', 'desc' => 'Artikel dan tips terbaru',
            'bi' => 'bi-journal-text', 'fa' => 'fa-newspaper',
        ],
        'knowledge-base.index' => [
            'label' => 'Pusat Bantuan', 'desc' => 'Panduan dan jawaban umum',
            'bi' => 'bi-life-preserver', 'fa' => 'fa-circle-question',
        ],
    ],
];
