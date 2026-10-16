<?php

return [
    /*
    | Slug halaman CMS yang tidak boleh dipakai.
    |
    | Alamat dari route publik yang terdaftar OTOMATIS ikut dicegah (lihat
    | CmsPage::isReservedSlug), jadi route baru tidak perlu ditambahkan ke
    | sini. Daftar ini berisi dua hal saja:
    |   1. kata di luar route: folder/berkas statis dan jalur server;
    |   2. jaring pengaman untuk alamat sistem terpenting, supaya tetap
    |      terlindungi walau pencocokan host publik meleset di suatu
    |      lingkungan (mis. domain portal belum dikonfigurasi).
    */
    'reserved_slugs' => [
        // di luar route
        'admin', 'client', 'api', 'login', 'register', 'logout', 'dashboard', 'home',
        'storage', 'build', 'vendor', 'store', 'p',
        'robots.txt', 'sitemap.xml', 'favicon.ico',
        // jaring pengaman alamat sistem
        'hosting', 'vps', 'cek-domain', 'transfer-domain', 'domain-premium', 'keranjang',
        'chat', 'announcements', 'blog', 'knowledge-base', 'payment', 'promo', 'lisensi', 'ref',
    ],
];
