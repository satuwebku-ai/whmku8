<?php

namespace Database\Seeders;

use App\Models\KnowledgeBase;
use App\Models\KnowledgeBaseCategory;
use Illuminate\Database\Seeder;

/**
 * Topik dan artikel Pusat Bantuan awal. Aman dijalankan ulang; artikel yang
 * sudah ada tidak ditimpa.
 */
class KnowledgeBaseSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['slug' => 'memulai', 'name' => 'Memulai', 'sort' => 1],
            ['slug' => 'hosting-cpanel', 'name' => 'Hosting & cPanel', 'sort' => 2],
            ['slug' => 'domain', 'name' => 'Domain', 'sort' => 3],
            ['slug' => 'pembayaran-tagihan', 'name' => 'Pembayaran & Tagihan', 'sort' => 4],
            ['slug' => 'vps', 'name' => 'VPS & Cloud Server', 'sort' => 5],
        ];

        $ids = [];
        foreach ($categories as $c) {
            $ids[$c['slug']] = KnowledgeBaseCategory::query()
                ->firstOrCreate(['slug' => $c['slug']], ['name' => $c['name'], 'sort_order' => $c['sort']])->id;
        }

        $articles = [
            ['memulai', 'cara-mendaftar-dan-login', 'Cara mendaftar dan login ke area klien',
                '<p>Area klien dipakai untuk mengelola layanan, tagihan, dan tiket.</p><ol><li>Klik <strong>Daftar</strong> di bagian atas situs.</li><li>Isi data dengan benar lalu verifikasi email Anda.</li><li>Login memakai email dan password yang dibuat.</li></ol><p>Jika lupa password, gunakan tautan <strong>Lupa password</strong> di halaman login.</p>'],
            ['memulai', 'cara-memesan-hosting', 'Cara memesan paket hosting',
                '<p>Proses pemesanan hanya beberapa langkah.</p><ol><li>Buka halaman <strong>Hosting</strong> dan pilih paket.</li><li>Pilih siklus pembayaran dan opsi domain.</li><li>Tambahkan ke keranjang lalu checkout.</li><li>Bayar tagihan. Layanan aktif otomatis setelah pembayaran diterima.</li></ol>'],
            ['hosting-cpanel', 'cara-login-cpanel', 'Cara login ke cPanel',
                '<p>Data login cPanel dikirim ke email Anda setelah layanan aktif.</p><ol><li>Buka area klien lalu pilih layanan hosting Anda.</li><li>Klik tombol masuk cPanel, atau buka alamat cPanel dari email.</li><li>Masukkan username dan password hosting.</li></ol>'],
            ['hosting-cpanel', 'cara-membuat-email-domain', 'Cara membuat email dengan domain sendiri',
                '<p>Anda dapat membuat alamat seperti info@namadomain.com.</p><ol><li>Masuk ke cPanel lalu buka <strong>Email Accounts</strong>.</li><li>Klik <strong>Create</strong>, isi nama dan password.</li><li>Akses lewat Webmail atau tambahkan ke aplikasi email di ponsel.</li></ol>'],
            ['hosting-cpanel', 'cara-memasang-ssl', 'Cara mengaktifkan SSL (HTTPS)',
                '<p>SSL membuat website tampil aman dengan HTTPS.</p><ol><li>Buka cPanel lalu pilih <strong>SSL/TLS Status</strong>.</li><li>Pilih domain Anda dan jalankan <strong>AutoSSL</strong>.</li><li>Tunggu beberapa menit sampai sertifikat aktif.</li></ol><p>Pastikan nameserver domain sudah mengarah ke hosting.</p>'],
            ['domain', 'cara-mengarahkan-nameserver', 'Cara mengarahkan nameserver domain',
                '<p>Agar domain terhubung ke hosting, ubah nameserver di tempat domain terdaftar.</p><ol><li>Salin nameserver dari email aktivasi hosting.</li><li>Masuk ke panel registrar domain Anda.</li><li>Ganti nameserver lalu simpan.</li></ol><p>Propagasi dapat memakan waktu hingga 24 jam.</p>'],
            ['domain', 'cara-transfer-domain', 'Cara transfer domain ke kami',
                '<p>Siapkan hal berikut sebelum transfer.</p><ul><li>Domain sudah berumur lebih dari 60 hari</li><li>Status domain tidak terkunci (unlock)</li><li>Kode EPP/auth code dari registrar lama</li></ul><p>Lalu buka halaman <strong>Transfer Domain</strong>, masukkan nama domain dan kode EPP.</p>'],
            ['pembayaran-tagihan', 'metode-pembayaran', 'Metode pembayaran yang tersedia',
                '<p>Pembayaran dapat dilakukan lewat transfer bank, e-wallet, kartu, atau QRIS sesuai gateway yang aktif. Pilih metode saat membuka tagihan lalu ikuti petunjuk di layar.</p>'],
            ['pembayaran-tagihan', 'tagihan-jatuh-tempo', 'Apa yang terjadi jika tagihan jatuh tempo?',
                '<p>Kami mengirim pengingat sebelum jatuh tempo. Jika tagihan belum dibayar setelah jatuh tempo, layanan dapat ditangguhkan sementara dan diaktifkan kembali setelah pembayaran diterima.</p>'],
            ['vps', 'cara-deposit-saldo-vps', 'Cara menambah saldo untuk VPS',
                '<p>VPS ditagih per jam dari saldo deposit Anda.</p><ol><li>Buka menu <strong>Saldo</strong> di area klien.</li><li>Pilih nominal top up lalu bayar.</li><li>Saldo bertambah otomatis setelah pembayaran terkonfirmasi.</li></ol><p>Pastikan saldo cukup agar VPS tidak dihentikan.</p>'],
        ];

        foreach ($articles as [$cat, $slug, $title, $content]) {
            KnowledgeBase::query()->firstOrCreate(
                ['slug' => $slug],
                [
                    'knowledge_base_category_id' => $ids[$cat],
                    'title' => $title,
                    'content' => $content,
                    'is_published' => true,
                ]
            );
        }
    }
}
