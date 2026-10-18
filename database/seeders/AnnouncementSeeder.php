<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\Setting;
use Illuminate\Database\Seeder;

/**
 * Pengumuman awal untuk halaman /announcements dan section "Kabar Terbaru" di beranda.
 *
 * Isinya bersifat umum (tanpa tanggal, harga, atau klaim insiden) supaya aman tampil
 * di situs sungguhan. Aman dijalankan ulang: slug jadi identitas dan pengumuman yang
 * sudah ada tidak ditimpa. Ubah atau hapus lewat Admin > CMS > Promosi > Pengumuman.
 */
class AnnouncementSeeder extends Seeder
{
    public function run(): void
    {
        $site = Setting::get('site_name') ?: config('app.name', 'kami');

        $items = [
            [
                'slug' => 'selamat-datang', 'category' => 'info', 'pinned' => true, 'days' => 1,
                'title' => "Selamat datang di {$site}",
                'excerpt' => 'Hosting, VPS, dan domain dalam satu akun, dengan aktivasi otomatis setelah pembayaran.',
                'content' => "<p>Terima kasih telah mengunjungi {$site}. Dari satu area klien Anda dapat memesan hosting, VPS, dan domain, membayar tagihan, serta menghubungi tim support.</p>"
                    . '<h2>Mulai dari mana?</h2><ul><li>Pilih paket di halaman Hosting atau VPS</li><li>Cek ketersediaan nama domain Anda</li><li>Baca panduan singkat di Pusat Bantuan</li></ul>'
                    . '<p>Butuh bantuan? Buat tiket dari area klien atau gunakan live chat di pojok kanan bawah.</p>',
            ],
            [
                'slug' => 'aktifkan-verifikasi-dua-langkah', 'category' => 'info', 'pinned' => false, 'days' => 4,
                'title' => 'Amankan akun Anda dengan verifikasi dua langkah',
                'excerpt' => 'Tambahkan lapisan keamanan ekstra agar akun dan layanan Anda lebih terlindungi.',
                'content' => '<p>Akun klien menyimpan data layanan dan tagihan Anda, jadi sebaiknya dilindungi dengan baik.</p>'
                    . '<ol><li>Masuk ke area klien lalu buka menu <strong>Profil</strong>.</li><li>Aktifkan <strong>verifikasi dua langkah</strong>.</li><li>Simpan kode cadangan di tempat yang aman.</li></ol>'
                    . '<p>Gunakan juga password yang unik dan jangan membagikan kode verifikasi kepada siapa pun.</p>',
            ],
            [
                'slug' => 'pengingat-perpanjangan-layanan-dan-domain', 'category' => 'info', 'pinned' => false, 'days' => 8,
                'title' => 'Pengingat perpanjangan layanan dan domain',
                'excerpt' => 'Kami mengirim pengingat sebelum jatuh tempo. Bayar tepat waktu agar layanan tidak terhenti.',
                'content' => '<p>Tagihan perpanjangan dibuat otomatis menjelang jatuh tempo dan pengingatnya dikirim ke email Anda.</p>'
                    . '<ul><li>Layanan yang belum dibayar setelah jatuh tempo dapat ditangguhkan sementara.</li><li>Domain yang kedaluwarsa dapat masuk masa tebus dengan biaya tambahan dari registrar.</li><li>Pastikan alamat email di profil Anda selalu aktif.</li></ul>',
            ],
            [
                'slug' => 'jadwal-pemeliharaan-dan-pemberitahuan', 'category' => 'maintenance', 'pinned' => false, 'days' => 12,
                'title' => 'Cara kami memberi tahu jadwal pemeliharaan',
                'excerpt' => 'Pemeliharaan terencana selalu diumumkan lebih dulu di halaman ini dan lewat email.',
                'content' => '<p>Sesekali kami perlu melakukan pemeliharaan server agar layanan tetap cepat dan aman.</p>'
                    . '<p>Untuk pemeliharaan terencana, kami akan memuat pengumuman di halaman ini beserta perkiraan waktu dan dampaknya, dan bila perlu mengirim email kepada pelanggan yang terdampak.</p>'
                    . '<p>Jika Anda mengalami kendala di luar pengumuman, silakan buat tiket dukungan.</p>',
            ],
        ];

        foreach ($items as $row) {
            Announcement::query()->firstOrCreate(
                ['slug' => $row['slug']],
                [
                    'title' => $row['title'],
                    'excerpt' => $row['excerpt'],
                    'content' => $row['content'],
                    'category' => $row['category'],
                    'is_published' => true,
                    'is_pinned' => $row['pinned'],
                    'published_at' => now()->subDays($row['days']),
                ]
            );
        }
    }
}
