# Blog dan Pusat Bantuan

Fitur memakai tabel/model CMS dan Knowledge Base yang sudah ada. Tidak ada migration baru, perubahan schema, atau penghapusan tabel.

## Panel admin

Semua halaman berada di modul `content` dan mengikuti middleware admin yang sudah dipakai aplikasi.

- `admin.blog.index`: buat/edit/hapus artikel, kategori, status draf/terbit, tanggal publikasi terjadwal.
- `admin.blog.categories`: kategori artikel blog.
- `admin.knowledge-base.index`: buat/edit/hapus panduan, kategori, status terbit, penghitung tampilan.
- `admin.knowledge-base.categories`: kategori Pusat Bantuan.

Slug dibuat dari judul/nama jika kosong dan ditambahkan akhiran angka saat terjadi duplikat. Konten HTML dibersihkan dengan `HtmlSanitizer` saat dirender.

## Halaman publik

- `/blog` dan `/blog/{slug}` menampilkan artikel yang sudah terbit.
- `/knowledge-base` dan `/knowledge-base/{slug}` menampilkan panduan yang sudah terbit.
- Kedua halaman utama menyediakan filter kategori dan pencarian judul.
- Tautan Blog dan Pusat Bantuan ditambahkan ke footer tiap tema publik.

## Pemeriksaan

Ada test feature untuk visibilitas draf, sanitasi HTML, dan penghitung tampilan. Jalankan di checkout aplikasi lengkap dengan:

```sh
php artisan test --filter=PublicEditorialContentTest
```

Arsip sumber yang tersedia untuk implementasi ini tidak menyertakan `composer.json`, `artisan`, atau berkas konfigurasi PHPUnit, jadi test perlu dijalankan setelah file hasil digabungkan ke checkout aplikasi lengkap.
