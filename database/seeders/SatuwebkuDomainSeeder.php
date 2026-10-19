<?php

namespace Database\Seeders;

use App\Models\Invoice;
use App\Models\Order;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

/**
 * Seeder: client Satuwebku + proses pembelian domain appdataku.my.id
 * (order -> invoice -> item invoice -> domain aktif).
 *
 * Jalankan:
 *   php artisan db:seed --class=SatuwebkuDomainSeeder
 *
 * Aman dijalankan berulang: kalau order domain ini sudah ada, tidak dibuat lagi.
 */
class SatuwebkuDomainSeeder extends Seeder
{
    private const DOMAIN      = 'appdataku.my.id';
    private const PRICE       = 40000;
    private const PRODUCT     = 'Registrasi Domain appdataku.my.id';
    private const ORDER_AT    = '2026-08-15 08:30:00';
    private const PAID_AT     = '2026-08-15 08:40:00';
    private const DONE_AT     = '2026-08-15 08:47:44';

    public function run(): void
    {
        DB::transaction(function () {
            $clientId = $this->seedClient();
            [$orderId, $invoiceId] = $this->seedOrderAndInvoice($clientId);
            $this->seedDomain($clientId, $orderId);
        });
    }

    /** 1. Client */
    private function seedClient(): int
    {
        $client = DB::table('clients')->where('email', 'satuwebku@gmail.com')->first();
        if ($client) {
            return (int) $client->id;
        }

        $now = now();

        return (int) DB::table('clients')->insertGetId([
            'name'              => 'Satuwebku',
            'email'             => 'satuwebku@gmail.com',
            'phone'             => '0852000000',
            'company'           => 'abc',
            'address'           => 'jla',
            'city'              => 'Palu',
            'state'             => 'Sulawesi Tengah',
            'postal_code'       => '94333',
            'country'           => 'Indonesia',
            // Ganti password ini setelah login pertama.
            'password'          => Hash::make('Hosting2026'),
            'email_verified_at' => $now,
            'status'            => 'active',
            'balance'           => 0,
            'notify_promo'      => 1,
            'created_at'        => $now,
            'updated_at'        => $now,
        ]);
    }

    /** 2. Order + item order + invoice + item invoice (sudah lunas) */
    private function seedOrderAndInvoice(int $clientId): array
    {
        // Sudah pernah di-seed? Pakai lagi.
        $order = DB::table('orders')
            ->where('client_id', $clientId)
            ->where('order_type', 'domain')
            ->where('product_name', self::PRODUCT)
            ->first();

        if ($order) {
            $invoice = DB::table('invoices')->where('order_id', $order->id)->first();

            return [(int) $order->id, $invoice ? (int) $invoice->id : null];
        }

        // Nomor diambil dari generator model supaya tidak bentrok dengan
        // data lain (memakai tabel number_sequences yang sama dengan aplikasi).
        $orderNumber   = Order::generateOrderNumber();
        $invoiceNumber = Invoice::generateInvoiceNumber();

        $orderId = (int) DB::table('orders')->insertGetId([
            'order_number'             => $orderNumber,
            'client_id'                => $clientId,
            'product_id'               => null,
            'hosting_account_id'       => null,
            'product_name'             => self::PRODUCT,
            'order_type'               => 'domain',
            'amount'                   => self::PRICE,
            'status'                   => 'completed',
            'stock_reservation_status' => 'none',
            'completed_at'             => self::DONE_AT,
            'created_at'               => self::ORDER_AT,
            'updated_at'               => self::DONE_AT,
        ]);

        DB::table('order_items')->insert([
            'order_id'    => $orderId,
            'description' => 'Registrasi domain ' . self::DOMAIN . ' (1 tahun)',
            'amount'      => self::PRICE,
            'quantity'    => 1,
            'created_at'  => self::ORDER_AT,
            'updated_at'  => self::ORDER_AT,
        ]);

        $invoiceId = (int) DB::table('invoices')->insertGetId([
            'invoice_number' => $invoiceNumber,
            'client_id'      => $clientId,
            'order_id'       => $orderId,
            'amount'         => self::PRICE,
            'tax'            => 0,
            'discount'       => 0,
            'total'          => self::PRICE,
            'status'         => 'paid',
            'is_topup'       => 0,
            'issue_date'     => '2026-08-15',
            'due_date'       => '2026-08-18',
            'paid_at'        => self::PAID_AT,
            'payment_method' => 'Duitku',
            'created_at'     => self::ORDER_AT,
            'updated_at'     => self::PAID_AT,
        ]);

        DB::table('invoice_items')->insert([
            'invoice_id'  => $invoiceId,
            'order_id'    => $orderId,
            'description' => 'Registrasi Domain ' . self::DOMAIN . ' (1 tahun)',
            'amount'      => self::PRICE,
            'created_at'  => self::ORDER_AT,
            'updated_at'  => self::ORDER_AT,
        ]);

        // Riwayat status order (dilewati kalau tabelnya tidak ada).
        if (Schema::hasTable('order_status_logs')) {
            DB::table('order_status_logs')->insert([
                [
                    'order_id' => $orderId, 'admin_id' => null,
                    'from_status' => 'pending_payment', 'to_status' => 'paid',
                    'note' => 'Pembayaran invoice ' . $invoiceNumber . ' diterima.',
                    'created_at' => self::PAID_AT, 'updated_at' => self::PAID_AT,
                ],
                [
                    'order_id' => $orderId, 'admin_id' => null,
                    'from_status' => 'paid', 'to_status' => 'completed',
                    'note' => 'Domain berhasil didaftarkan.',
                    'created_at' => self::DONE_AT, 'updated_at' => self::DONE_AT,
                ],
            ]);
        }

        return [$orderId, $invoiceId];
    }

    /** 3. Domain (terhubung ke order di atas) */
    private function seedDomain(int $clientId, int $orderId): void
    {
        // registrar & TLD hanya dipakai kalau ID-nya ada di database ini,
        // supaya tidak error foreign key.
        $registrarId = Schema::hasTable('registrars') && DB::table('registrars')->where('id', 1)->exists() ? 1 : null;
        $tldId       = Schema::hasTable('tlds') && DB::table('tlds')->where('id', 21)->exists() ? 21 : null;

        DB::table('domains')->updateOrInsert(
            ['domain_name' => self::DOMAIN],
            [
                'client_id'             => $clientId,
                'order_id'              => $orderId,
                'registrar_id'          => $registrarId,
                'tld_id'                => $tldId,
                'tld_premium_id'        => null,
                'price'                 => self::PRICE,
                'years'                 => 1,
                'is_premium'            => 0,
                'status'                => 'active',
                'register_date'         => '2026-08-15',
                'expiry_date'           => '2027-08-15',
                'auto_renew'            => 1,
                'whois_privacy'         => 0,
                'nameservers'           => json_encode(['ns1.satucloudhosting.com', 'ns2.satucloudhosting.com']),
                'provision_status'      => 'registered',
                'provisioning_attempts' => 0,
                'is_transfer'           => 0,
                'provision_message'     => 'OK',
                'created_at'            => self::DONE_AT,
                'updated_at'            => self::DONE_AT,
            ]
        );
    }
}