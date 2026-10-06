# Portal klien dan admin di subdomain cPanel

Aplikasi tetap satu instalasi Laravel. Situs publik tetap di `satucloudhosting.com`, area klien menggunakan `member.satucloudhosting.com`, dan panel admin menggunakan `pengelola.satucloudhosting.com`.

> **Penting — migration di paket ini adalah baseline untuk instalasi/database baru.** File migration susulan sudah digabung ke file `create_` dan beberapa backfill data lama dihilangkan. Jangan mengganti folder migration atau menjalankan `migrate:fresh` pada database production yang aktif; pertahankan riwayat migration production yang sudah tercatat.

## Pengaturan aplikasi

Tambahkan atau sesuaikan nilai berikut di `.env` pada server. Jangan unggah file `.env` ke arsip distribusi.

```dotenv
APP_ENV=production
APP_DEBUG=false
APP_URL=https://satucloudhosting.com
CLIENT_DOMAIN=member.satucloudhosting.com
ADMIN_DOMAIN=pengelola.satucloudhosting.com
SESSION_DOMAIN=.satucloudhosting.com
SESSION_SECURE_COOKIE=true
```

Pertahankan `APP_KEY` yang sudah dipakai aplikasi. `SESSION_DOMAIN` membuat sesi yang sama tersedia di kedua subdomain, yang dibutuhkan fitur admin untuk masuk sementara sebagai klien. Pastikan seluruh subdomain hanya dilayani melalui HTTPS.

Jika login Google untuk klien aktif, tambahkan URI callback berikut ke daftar redirect resmi di Google Cloud Console dan `.env`:

```dotenv
GOOGLE_REDIRECT_URI=https://member.satucloudhosting.com/auth/google/callback
```

## Pengaturan cPanel

1. Buat domain/subdomain `member.satucloudhosting.com` dan `pengelola.satucloudhosting.com` dari menu **Domains**.
2. Arahkan document root keduanya ke folder `public` dari **instalasi Laravel yang sama**. Jangan jadikan folder root aplikasi Laravel sebagai document root publik. Jika cPanel menolak document root yang sama untuk dua host, minta penyedia hosting mengarahkan kedua host ke web root aplikasi yang sama sebelum mengaktifkan perubahan ini.
3. Jika DNS dikelola di cPanel, pastikan record `member` dan `pengelola` mengarah ke server hosting. Jika DNS dikelola di tempat lain, buat record `A` untuk kedua nama ke IP server cPanel.
4. Terbitkan SSL untuk kedua nama melalui **SSL/TLS Status** / AutoSSL, lalu pastikan masing-masing URL memakai HTTPS.
5. Setelah `.env` dan document root diperbarui, bersihkan dan bangun ulang cache konfigurasi/rute:

```bash
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
```

## URL setelah aktif

- `https://satucloudhosting.com/` — situs/katalog publik.
- `https://member.satucloudhosting.com/` — login klien, lalu dashboard klien.
- `https://pengelola.satucloudhosting.com/` — login admin, lalu dashboard admin.

URL lama berawalan `/client` akan diarahkan ke host klien, sedangkan URL lama `/admin/...` akan diarahkan ke beranda publik di `APP_URL` agar halaman login admin tidak ditampilkan dari jalur lama. Pengalihan hanya berlaku untuk permintaan GET; form POST lama tidak diteruskan lintas host. Panel admin tetap tersedia di subdomain admin dan tetap harus dilindungi autentikasi yang kuat.
