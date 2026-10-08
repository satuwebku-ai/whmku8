# Batas Area Aplikasi

## Public (CMS)

Domain publik hanya melayani konten, lewat `routes/public.php`: beranda, halaman CMS, pengumuman, blog, knowledge base, promo, chat, dan callback pembayaran. Area ini tidak menangani data layanan milik akun client dan tidak lagi menjual produk.

Alamat toko lama di domain publik (`/hosting`, `/vps`, `/{jenis}/{kategori}`, `/lisensi`, `/cek-domain`, `/transfer-domain`, `/domain-premium`, `/keranjang`) diarahkan dengan redirect 301 ke alamat baru di portal client; query string ikut dibawa. Redirect ini didaftarkan di `routes/web.php` sebelum catch-all CMS.

## Client + Store

Portal client (`member.*`) memuat dua modul:

- **Portal** (`routes/client.php`): layanan, domain, tagihan, profil, tiket, dan checkout yang membuat order serta invoice (butuh login).
- **Store** (`routes/store.php`, prefix `/store`): katalog produk, lisensi, pencarian/transfer domain, domain premium, dan keranjang. Tamu boleh menjelajah dan menyiapkan keranjang; login baru diminta saat checkout. Nama route (`catalog.*`, `cart.*`, `license.*`, `domain.*`) tidak berubah, jadi `route('catalog.index')` otomatis menghasilkan URL di domain client.

Prefix `/store` wajib ada: portal client sudah memakai `/vps` dan `/vps/{vps}`, sehingga katalog tanpa prefix akan bentrok. Segmen jenis produk di URL katalog (`/store/{jenis}/{kategori}`) berasal dari `product_types.slug` (`ProductType::sectionPattern()`).

Tampilan toko: halaman toko meng-extend `public.store-layout` (pembungkus per tema, `resources/views/themes/public-themes/{tema}/public/store-layout.blade.php`; tema tanpa file sendiri memakai milik `default`). Pembungkus memilih layout induk lewat `ThemeRegistry::storeLayout()`: klien yang sudah login melihat toko di dalam portal client (sidebar + menu) **selama tema Publik dan tema Client yang aktif sama**; tamu, atau tema yang berbeda, memakai layout publik. View toko menaruh isinya di `@section('store-content')` (atau `@section('full-width')` untuk halaman berlatar penuh seperti cek domain), bukan `@section('content')`.

Menu sidebar client didefinisikan sekali di `App\Support\ClientMenu` dan dipakai ketiga tema client (tema hanya memilih set ikon: Font Awesome atau Bootstrap Icons). Item menu baru cukup ditambahkan di sana.

Karena keranjang (session), login, dan checkout kini satu domain, alur order tidak lagi berpindah domain. Konsekuensinya, lencana jumlah keranjang di header halaman CMS selalu kosong (session keranjang ada di domain client); lencana tetap tampil di halaman toko.

## Admin

Route admin tetap berada di `routes/admin.php` dan hanya dilayani pada domain admin. Pengelolaan produk, grup/server, klien, order, billing, dan konten tetap menjadi tanggung jawab admin.

## Batas provisioning server

Produk hosting memilih satu sumber penempatan: server langsung atau grup server. Pemilihan otomatis dari grup hanya memakai anggota cPanel yang aktif, tidak maintenance, dan masih berkapasitas. Anggota lama dari panel lain tetap terlihat saat mengedit grup agar admin dapat menghapusnya secara sadar; anggota tersebut tidak dipakai untuk order otomatis. Jika grup tidak memiliki server cPanel yang siap, checkout tetap memakai jalur aktivasi manual.

Produk upgrade dapat menggunakan server langsung yang sama atau grup aktif yang masih mencakup server akun saat ini. Upgrade tidak memindahkan akun ke server lain. Diagnostik server juga menampilkan produk yang terhubung melalui grup beserta nama grup dan kecocokan paketnya.
