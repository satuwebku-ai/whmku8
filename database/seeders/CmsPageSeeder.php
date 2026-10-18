<?php

namespace Database\Seeders;

use App\Models\CmsPage;
use Illuminate\Database\Seeder;

/**
 * Halaman statis (/{slug}) yang tampil sebagai menu footer.
 *
 * Aman dijalankan ulang: slug dipakai sebagai identitas. Halaman yang sudah
 * ada TIDAK ditimpa, jadi isi yang sudah Anda edit di CMS tetap utuh.
 * Ganti data perusahaan (alamat, kontak, dll.) lewat Admin > CMS > Halaman.
 */
class CmsPageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'slug' => 'tentang-kami', 'title' => 'Tentang Kami', 'sort_order' => 1,
                'content' => '<p>Kami menyediakan layanan hosting, VPS, dan domain untuk individu, UMKM, dan bisnis yang ingin online dengan cepat dan tanpa repot.</p>'
                    . '<h2>Yang Kami Tawarkan</h2><ul><li>Shared hosting dan WordPress hosting dengan panel cPanel</li><li>Cloud VPS dengan penagihan per jam</li><li>Pendaftaran dan transfer domain</li><li>Dukungan lewat tiket dan live chat</li></ul>'
                    . '<h2>Komitmen Kami</h2><p>Aktivasi layanan berjalan otomatis setelah pembayaran diterima, harga ditampilkan terbuka, dan setiap kendala ditangani tim support kami.</p>',
            ],
            [
                'slug' => 'kontak', 'title' => 'Kontak', 'sort_order' => 2,
                'content' => '<p>Butuh bantuan? Pilih saluran yang paling nyaman untuk Anda.</p>'
                    . '<h2>Dukungan Teknis</h2><p>Pelanggan dapat membuat tiket dari area klien atau memakai live chat di pojok kanan bawah situs.</p>'
                    . '<h2>Pertanyaan Umum</h2><p>Banyak jawaban sudah tersedia di <a href="/knowledge-base">Pusat Bantuan</a>.</p>'
                    . '<p><em>Catatan: tambahkan email, nomor WhatsApp, dan alamat resmi perusahaan Anda di halaman ini lewat CMS.</em></p>',
            ],
            [
                'slug' => 'syarat-ketentuan', 'title' => 'Syarat & Ketentuan', 'sort_order' => 3,
                'content' => '<p>Dengan memesan atau memakai layanan kami, Anda menyetujui ketentuan berikut.</p>'
                    . '<h2>1. Akun</h2><p>Anda bertanggung jawab menjaga kerahasiaan akun dan data yang dimasukkan harus benar dan terbaru.</p>'
                    . '<h2>2. Pembayaran</h2><p>Layanan aktif setelah pembayaran terverifikasi. Tagihan yang tidak dibayar sampai jatuh tempo dapat menyebabkan layanan ditangguhkan.</p>'
                    . '<h2>3. Penggunaan Layanan</h2><p>Layanan tidak boleh dipakai untuk aktivitas melanggar hukum, spam, malware, phishing, atau yang mengganggu pengguna lain.</p>'
                    . '<h2>4. Penangguhan dan Penghentian</h2><p>Kami dapat menangguhkan layanan yang melanggar ketentuan atau menimbulkan risiko keamanan bagi server.</p>'
                    . '<h2>5. Perubahan Ketentuan</h2><p>Ketentuan dapat diperbarui sewaktu-waktu. Versi terbaru selalu ada di halaman ini.</p>',
            ],
            [
                'slug' => 'kebijakan-privasi', 'title' => 'Kebijakan Privasi', 'sort_order' => 4,
                'content' => '<p>Kami menghargai privasi Anda dan hanya memakai data seperlunya untuk menjalankan layanan.</p>'
                    . '<h2>Data yang Kami Kumpulkan</h2><ul><li>Data akun: nama, email, nomor telepon</li><li>Data pemesanan dan tagihan</li><li>Data teknis seperti alamat IP untuk keamanan</li></ul>'
                    . '<h2>Penggunaan Data</h2><p>Data dipakai untuk memproses pesanan, menagih, memberi dukungan, dan menjaga keamanan layanan. Kami tidak menjual data pribadi Anda.</p>'
                    . '<h2>Pihak Ketiga</h2><p>Data tertentu dibagikan kepada penyedia pembayaran dan registrar domain sebatas yang diperlukan untuk menyelesaikan transaksi.</p>'
                    . '<h2>Hak Anda</h2><p>Anda dapat meminta pembaruan atau penghapusan data melalui tiket dukungan.</p>',
            ],
            [
                'slug' => 'kebijakan-refund', 'title' => 'Kebijakan Pengembalian Dana', 'sort_order' => 5,
                'content' => '<p>Ketentuan pengembalian dana kami disusun agar jelas dan adil.</p>'
                    . '<h2>Hosting</h2><p>Pengajuan refund hosting diterima dalam masa garansi yang berlaku untuk paket Anda. Biaya domain dan setup tidak dapat dikembalikan.</p>'
                    . '<h2>Domain</h2><p>Domain yang sudah terdaftar tidak dapat direfund karena biaya langsung diteruskan ke registrar.</p>'
                    . '<h2>VPS</h2><p>VPS ditagih per jam sesuai pemakaian. Saldo deposit yang belum terpakai dapat diajukan pengembaliannya lewat tiket.</p>'
                    . '<p><em>Sesuaikan lama masa garansi dengan kebijakan bisnis Anda sebelum situs dibuka ke publik.</em></p>',
            ],
            [
                'slug' => 'kebijakan-penggunaan-wajar', 'title' => 'Kebijakan Penggunaan Wajar', 'sort_order' => 6,
                'content' => '<p>Server hosting dipakai bersama, sehingga pemakaian resource perlu wajar agar semua pelanggan tetap nyaman.</p>'
                    . '<h2>Yang Tidak Diperbolehkan</h2><ul><li>Konten ilegal, phishing, atau penipuan</li><li>Pengiriman email massal tanpa izin (spam)</li><li>Skrip yang menghabiskan CPU atau memori dalam waktu lama</li><li>Penyimpanan file backup atau media besar yang tidak berkaitan dengan website</li></ul>'
                    . '<h2>Tindakan Kami</h2><p>Akun yang melanggar akan diberi peringatan. Pelanggaran berat atau berulang dapat langsung ditangguhkan.</p>',
            ],
        ];

        foreach ($pages as $row) {
            CmsPage::query()->firstOrCreate(
                ['slug' => $row['slug']],
                [
                    'title' => $row['title'],
                    'content' => $row['content'],
                    'meta_description' => null,
                    'is_published' => true,
                    'show_in_footer' => true,
                    'noindex' => false,
                    'sort_order' => $row['sort_order'],
                ]
            );
        }
    }
}
