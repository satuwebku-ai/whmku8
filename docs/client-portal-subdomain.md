# Memisahkan situs publik dan portal client

Konfigurasi ini mempertahankan situs utama di `https://contoh.com` dan
menampilkan halaman toko hosting di `https://client.contoh.com`. Tombol Login
di toko membuka portal client di `https://client.contoh.com/client/login`.
Area admin tetap hanya tersedia di domain utama pada `/admin`.

## 1. Atur host aplikasi

Tambahkan ke `.env` server:

```dotenv
APP_URL=https://contoh.com
CLIENT_PORTAL_HOST=client.contoh.com
# Opsional. Jika kosong, host publik diambil dari APP_URL.
PUBLIC_SITE_HOST=contoh.com
```

Isi host saja pada `CLIENT_PORTAL_HOST` dan `PUBLIC_SITE_HOST` — tanpa
`https://`, port, atau path. Jangan mengatur `SESSION_DOMAIN` ke domain induk
untuk pemisahan standar; cookie sesi tetap khusus untuk masing-masing host.

## 2. Arahkan DNS dan HTTPS

Buat record DNS `client` yang mengarah ke server yang sama dengan situs utama
(record A/AAAA atau CNAME sesuai penyedia DNS). Tambahkan `client.contoh.com`
sebagai host/subdomain pada web server dan arahkan document root-nya ke folder
`public/` aplikasi Laravel yang sama. Sertifikat HTTPS harus mencakup domain
utama dan `client.contoh.com`.

## 3. Perbarui layanan login pihak ketiga

Jika Google OAuth dipakai untuk login client, tambahkan URI callback berikut
di pengaturan OAuth:

```text
https://client.contoh.com/client/auth/google/callback
```

## 4. Terapkan perubahan

Setelah DNS, virtual host, dan `.env` siap, bersihkan cache konfigurasi dan
route, lalu bangun ulang cache produksi:

```bash
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
```

Halaman publik dan katalog tersedia di domain utama serta subdomain client,
tetapi admin hanya tersedia di domain utama. Route portal client berada di
`/client` pada subdomain, misalnya `/client/login` dan `/client/`. URL GET/HEAD
lama seperti `https://contoh.com/client/login` diarahkan ke
`https://client.contoh.com/client/login`; muat ulang halaman lama sebelum
mengirim formulir karena pengiriman POST lama tidak dialihkan lintas host.
