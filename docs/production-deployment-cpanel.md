# Panduan deployment production di cPanel

Panduan ini untuk instalasi yang sudah mempunyai database production dan riwayat migration. Semua pengaturan `.env` diisi manual melalui cPanel File Manager atau Terminal; jangan mengunggah `.env` ke ZIP.

> **Jangan gunakan paket baseline fresh-install untuk production aktif.** Pertahankan file di `database/migrations` yang sudah tercatat di database production. Jangan jalankan `migrate:fresh`, `migrate:refresh`, atau menghapus/menimpa riwayat migration.

## 1. Backup dan catat keadaan awal

1. Buat backup database melalui cPanel **Backup** / **phpMyAdmin** dan backup seluruh folder aplikasi serta `storage`.
2. Dari folder aplikasi, jalankan `php artisan migrate:status` dan simpan hasilnya. Jangan lanjutkan jika ada migration berstatus `Pending` yang tidak Anda harapkan.
3. Simpan salinan `.env` di lokasi aman. Pertahankan nilai `APP_KEY` yang sudah aktif.
4. Jangan menjalankan migration dari paket baseline fresh-install pada database ini.

## 2. Perbarui source tanpa mengubah database

1. Unggah source production-safe yang masih mempertahankan riwayat migration original, atau salin file aplikasi yang berubah ke instalasi saat ini.
2. Pertahankan `.env`, folder `storage`, file upload, dan isi `database/migrations` production yang sudah tercatat. Jika nama atau isi migration pada paket berbeda dari server, jangan menimpanya.
3. Jika versi kode baru memerlukan migration yang memang belum dijalankan, tinjau hasil `php artisan migrate --pretend` terlebih dahulu. Setelah backup dan memastikan migration sesuai, jalankan `php artisan migrate --force`. Jangan menjalankannya jika hasilnya tidak dipahami.

## 3. Isi `.env` secara manual

Atur nilai berikut; jangan membuat `APP_KEY` baru untuk aplikasi yang sudah berjalan:

```dotenv
APP_ENV=production
APP_DEBUG=false
APP_URL=https://satucloudhosting.com
CLIENT_DOMAIN=member.satucloudhosting.com
ADMIN_DOMAIN=pengelola.satucloudhosting.com
SESSION_DOMAIN=.satucloudhosting.com
SESSION_SECURE_COOKIE=true
```

Pertahankan seluruh kredensial dan konfigurasi database/email yang sudah ada. Jika login Google klien aktif, tambahkan callback ini di Google Cloud Console dan `.env`:

```dotenv
GOOGLE_REDIRECT_URI=https://member.satucloudhosting.com/auth/google/callback
```

## 4. Siapkan domain di cPanel

1. Buat `member.satucloudhosting.com` dan `pengelola.satucloudhosting.com` di **Domains**.
2. Arahkan document root kedua subdomain ke folder `public` dari instalasi Laravel yang sama. Jangan arahkan document root ke folder aplikasi Laravel bagian atas.
3. Buat record DNS `A` untuk `member` dan `pengelola` ke IP server cPanel, baik di cPanel maupun di penyedia DNS yang mengelola domain.
4. Terbitkan SSL untuk kedua subdomain melalui **SSL/TLS Status** / AutoSSL dan pastikan HTTP dialihkan ke HTTPS.
5. Jika cPanel tidak mengizinkan dua subdomain menggunakan folder `public` yang sama, minta penyedia hosting mengatur virtual host agar keduanya tetap menuju instalasi yang sama.

## 5. Bangun cache dan verifikasi

Jalankan dari folder aplikasi setelah `.env` diperbarui:

```bash
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan route:list --path=login
```

Pastikan hasil route menunjukkan login admin pada `pengelola.satucloudhosting.com/login` dan login klien pada `member.satucloudhosting.com/login`. Periksa juga:

- `https://satucloudhosting.com/` tetap menampilkan situs publik.
- `https://satucloudhosting.com/admin/login` kembali ke beranda publik.
- `https://member.satucloudhosting.com/login` menampilkan login klien.
- `https://pengelola.satucloudhosting.com/login` menampilkan login admin.

Method `ProductGroup::urlSection()` yang memilih prefix katalog `hosting` atau `vps` sudah ada di source; tidak perlu memasukkan ulang method yang sama.
