# Batas Area Aplikasi

## Public

Route konten publik berada di `routes/public.php`: beranda, halaman CMS, pengumuman, blog, knowledge base, promo, chat, dan callback pembayaran. Area ini tidak menangani data layanan milik akun client.

## Store

Route storefront publik berada di `routes/store.php`: katalog produk, lisensi, pencarian/transfer domain, domain premium, dan keranjang. Pengunjung tetap dapat melihat produk serta menyiapkan keranjang tanpa login. Route ini tetap memakai URL publik dan nama route yang sudah ada.

Checkout yang membuat order dan invoice tetap berada di `routes/client.php`, karena proses tersebut memerlukan akun client terautentikasi. Portal client juga memiliki domain dan tema tersendiri; fungsinya mencakup layanan, domain, tagihan, profil, tiket, dan checkout.

## Admin

Route admin tetap berada di `routes/admin.php` dan hanya dilayani pada domain admin. Pengelolaan produk, grup/server, klien, order, billing, dan konten tetap menjadi tanggung jawab admin.

## Batas provisioning server

Produk hosting memilih satu sumber penempatan: server langsung atau grup server. Pemilihan otomatis dari grup hanya memakai anggota cPanel yang aktif, tidak maintenance, dan masih berkapasitas. Anggota lama dari panel lain tetap terlihat saat mengedit grup agar admin dapat menghapusnya secara sadar; anggota tersebut tidak dipakai untuk order otomatis. Jika grup tidak memiliki server cPanel yang siap, checkout tetap memakai jalur aktivasi manual.

Produk upgrade dapat menggunakan server langsung yang sama atau grup aktif yang masih mencakup server akun saat ini. Upgrade tidak memindahkan akun ke server lain. Diagnostik server juga menampilkan produk yang terhubung melalui grup beserta nama grup dan kecocokan paketnya.
