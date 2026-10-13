# Patch: guard invoice (ID Protection .id, renewal H-30) + order/layanan terkait

Salin isi folder ini ke root proyek (struktur folder sama), timpa file lama.

## File
- app/Services/Payment/PaymentEligibilityService.php  -> ruleViolation(): blok ID Protection .id (PANDI) & renewal di luar H-30; dipakai check()
- app/Services/Provisioning/ProvisioningService.php   -> processPrivacyPayment(): pengaman kedua untuk domain .id
- app/Models/Invoice.php                               -> relatedServices(), isSystemGenerated()
- app/Http/Controllers/Admin/InvoiceController.php    -> update(): order_id tidak bisa diubah; invoice lunas/sistem dikunci
- resources/views/admin/invoices/form.blade.php       -> Order/Layanan read-only saat edit; dropdown hanya untuk invoice manual baru
- resources/views/admin/invoices/details.blade.php    -> tampilkan order + layanan terkait
- database/seeders/RestoreLegacyBillingSeeder.php     -> INV-0003 & INV-0008 dipulihkan sebagai cancelled, domain tidak ditautkan lagi
- app/Console/Commands/CancelRuleViolatingInvoices.php (BARU) -> lumora:cancel-invalid-invoices

## Langkah
1. Backup database.
2. Salin file, lalu: php artisan view:clear && php artisan optimize:clear
3. php artisan lumora:cancel-invalid-invoices --dry     (cek daftar)
4. php artisan lumora:cancel-invalid-invoices           (eksekusi)
   Invoice yang sudah LUNAS hanya dilaporkan (perlu refund manual).

## Verifikasi
- INV-2026-0003 & INV-2026-0008 berstatus Cancelled; domains.privacy_invoice_id / renewal_invoice_id appdataku.my.id = NULL
- Buka invoice 0003 di sisi klien: tidak bisa dibayar
- Edit INV-2026-0001: Order/Layanan tampil read-only (ORD-1001)
- Invoice perpanjangan domain muncul otomatis lagi saat H-30 (cron lumora:generate-renewal-invoices)

Catatan: patch belum dijalankan (tidak ada PHP/Laravel di lingkungan penyusun).
