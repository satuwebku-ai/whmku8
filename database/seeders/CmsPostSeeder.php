<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\CmsCategory;
use App\Models\CmsPost;
use Illuminate\Database\Seeder;

/**
 * Kategori dan artikel blog awal. Aman dijalankan ulang (slug sebagai identitas);
 * artikel yang sudah ada tidak ditimpa.
 */
class CmsPostSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'tips-hosting' => 'Tips Hosting',
            'domain' => 'Domain',
            'keamanan' => 'Keamanan',
            'wordpress' => 'WordPress',
        ];

        $categoryIds = [];
        foreach ($categories as $slug => $name) {
            $categoryIds[$slug] = CmsCategory::query()->firstOrCreate(['slug' => $slug], ['name' => $name])->id;
        }

        $adminId = Admin::query()->orderBy('id')->value('id');

        $posts = [
            ['slug' => 'cara-memilih-paket-hosting', 'cat' => 'tips-hosting', 'title' => 'Cara Memilih Paket Hosting yang Tepat untuk Website Anda', 'days' => 2,
                'excerpt' => 'Jangan hanya melihat harga. Kenali kebutuhan storage, jumlah website, dan trafik sebelum memilih paket.',
                'content' => '<p>Memilih hosting sering terasa membingungkan karena nama paketnya mirip-mirip. Gunakan tiga pertanyaan ini agar tidak salah pilih.</p><h2>1. Berapa website yang akan dipasang?</h2><p>Satu website pribadi cukup dengan paket dasar. Jika Anda mengelola beberapa website, pilih paket yang mengizinkan banyak domain.</p><h2>2. Seberapa besar trafiknya?</h2><p>Website baru jarang butuh resource besar. Mulai dari paket kecil, lalu naik kelas saat pengunjung bertambah.</p><h2>3. Apa yang Anda pasang?</h2><p>WordPress dan toko online membutuhkan resource lebih besar dibanding website profil sederhana.</p><p>Tip: pilih penyedia yang memudahkan upgrade paket tanpa memindahkan data.</p>'],
            ['slug' => 'perbedaan-shared-hosting-dan-vps', 'cat' => 'tips-hosting', 'title' => 'Shared Hosting vs VPS: Mana yang Cocok untuk Anda?', 'days' => 5,
                'excerpt' => 'Shared hosting praktis dan murah, VPS fleksibel dan terisolasi. Kenali perbedaannya sebelum memutuskan.',
                'content' => '<p>Kedua layanan sama-sama menjalankan website, tetapi cara kerjanya berbeda.</p><h2>Shared Hosting</h2><ul><li>Server dipakai bersama banyak akun</li><li>Dikelola lewat cPanel, cocok untuk pemula</li><li>Harga terjangkau</li></ul><h2>VPS</h2><ul><li>Resource khusus untuk Anda</li><li>Akses root, bebas memasang software</li><li>Perlu kemampuan mengelola server</li></ul><p>Jika Anda baru memulai, shared hosting biasanya cukup. Pilih VPS saat butuh kontrol penuh atau menjalankan aplikasi khusus.</p>'],
            ['slug' => 'tips-memilih-nama-domain', 'cat' => 'domain', 'title' => '7 Tips Memilih Nama Domain yang Mudah Diingat', 'days' => 9,
                'excerpt' => 'Nama domain adalah identitas online Anda. Pilih yang singkat, jelas, dan mudah diketik.',
                'content' => '<p>Nama domain yang baik membantu pelanggan menemukan Anda dengan cepat.</p><ol><li>Singkat, idealnya di bawah 15 karakter</li><li>Mudah dieja dan diucapkan</li><li>Hindari angka dan tanda hubung</li><li>Gunakan kata kunci bisnis bila cocok</li><li>Pilih ekstensi yang sesuai, misalnya .com atau .id</li><li>Cek kemiripan dengan merek lain</li><li>Daftarkan secepatnya sebelum diambil orang</li></ol>'],
            ['slug' => 'apa-itu-nameserver-dan-dns', 'cat' => 'domain', 'title' => 'Apa Itu Nameserver dan DNS? Penjelasan Sederhana', 'days' => 14,
                'excerpt' => 'Nameserver dan DNS menghubungkan domain ke hosting. Begini cara kerjanya.',
                'content' => '<p>Saat seseorang mengetik nama domain, DNS menerjemahkannya menjadi alamat server tempat website Anda berada.</p><h2>Nameserver</h2><p>Nameserver menunjukkan di mana pengaturan DNS domain Anda disimpan. Setelah membeli hosting, arahkan nameserver domain ke nameserver yang diberikan penyedia hosting.</p><h2>Berapa Lama Propagasi?</h2><p>Perubahan biasanya aktif dalam beberapa menit sampai 24 jam, tergantung cache di berbagai jaringan.</p>'],
            ['slug' => 'panduan-keamanan-website-dasar', 'cat' => 'keamanan', 'title' => 'Panduan Dasar Mengamankan Website dari Peretasan', 'days' => 18,
                'excerpt' => 'Langkah sederhana yang sering dilewatkan, padahal sangat efektif mencegah website diretas.',
                'content' => '<p>Sebagian besar peretasan terjadi karena hal-hal dasar yang terlewat.</p><ul><li>Gunakan password kuat dan unik untuk setiap akun</li><li>Aktifkan SSL agar website memakai HTTPS</li><li>Perbarui WordPress, tema, dan plugin secara rutin</li><li>Hapus plugin dan tema yang tidak dipakai</li><li>Backup rutin dan simpan salinannya di luar server</li><li>Batasi akses login dan pakai verifikasi dua langkah</li></ul>'],
            ['slug' => 'cara-mempercepat-website-wordpress', 'cat' => 'wordpress', 'title' => 'Cara Mempercepat Website WordPress Tanpa Ribet', 'days' => 23,
                'excerpt' => 'Website lambat membuat pengunjung pergi. Terapkan langkah berikut untuk membuatnya lebih cepat.',
                'content' => '<p>Kecepatan memengaruhi kenyamanan pengunjung dan peringkat di mesin pencari.</p><h2>Langkah Praktis</h2><ol><li>Pakai tema yang ringan</li><li>Kompres gambar sebelum diunggah</li><li>Aktifkan plugin cache</li><li>Batasi jumlah plugin</li><li>Gunakan versi PHP terbaru yang didukung</li></ol><p>Lakukan pengujian sebelum dan sesudah perubahan agar terlihat hasilnya.</p>'],
        ];

        foreach ($posts as $row) {
            CmsPost::query()->firstOrCreate(
                ['slug' => $row['slug']],
                [
                    'cms_category_id' => $categoryIds[$row['cat']],
                    'admin_id' => $adminId,
                    'title' => $row['title'],
                    'excerpt' => $row['excerpt'],
                    'content' => $row['content'],
                    'status' => 'published',
                    'published_at' => now()->subDays($row['days']),
                ]
            );
        }
    }
}
