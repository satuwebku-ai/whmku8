<?php $__env->startSection('title', 'Pengaturan PDF Invoice'); ?>
<?php $__env->startSection('content'); ?>
  <?php echo $__env->make('admin.settings._nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

  <?php use App\Models\Setting; ?>

  <div class="mb-4 d-flex align-items-center justify-content-between flex-wrap gap-2">
    <div>
      <h1 class="h4 fw-bold text-dark mb-1">Pengaturan PDF Invoice</h1>
      <p class="small text-muted mb-0">Atur apa saja yang tampil di file PDF invoice yang diunduh klien atau dilampirkan di email.</p>
    </div>
    <a href="<?php echo e(route('admin.settings.pdf-invoice.preview')); ?>" target="_blank" class="btn btn-outline-secondary btn-sm">
      <i class="fa-regular fa-file-pdf" style="font-size:11px"></i> Lihat Contoh PDF
    </a>
  </div>

  <form method="POST" action="<?php echo e(route('admin.settings.pdf-invoice.update')); ?>" class="card border rounded-4 p-4" style="max-width:42rem">
    <?php echo csrf_field(); ?>

    <div class="mb-3">
      <label class="d-flex align-items-center gap-2 small fw-medium text-dark mb-1" style="cursor:pointer;width:fit-content">
        <input type="checkbox" name="pdf_show_logo" value="1" <?php if(Setting::get('pdf_show_logo', '1') === '1'): echo 'checked'; endif; ?> class="form-check-input" style="margin-top:0">
        Tampilkan logo di PDF
      </label>
      <p class="text-muted mb-0" style="font-size:11px">Kalau dimatikan, nama situs ditampilkan sebagai teks saja (tanpa gambar logo) di kop PDF.</p>
    </div>

    <div class="mb-3">
      <label class="form-label small fw-medium text-dark">NPWP / Nomor Pajak (opsional)</label>
      <input type="text" name="pdf_tax_id" value="<?php echo e(old('pdf_tax_id', Setting::get('pdf_tax_id'))); ?>" class="form-control form-control-sm" placeholder="00.000.000.0-000.000">
      <p class="text-muted mt-1 mb-0" style="font-size:11px">Tampil di kop PDF, di bawah alamat perusahaan. Kosongkan kalau tidak perlu.</p>
    </div>

    <div class="mb-3">
      <label class="form-label small fw-medium text-dark">Info Pembayaran</label>
      <textarea name="pdf_payment_info" rows="3" class="form-control form-control-sm" placeholder="Transfer ke:&#10;BCA 1234567890 a.n. PT Contoh Hosting"><?php echo e(old('pdf_payment_info', Setting::get('pdf_payment_info'))); ?></textarea>
      <p class="text-muted mt-1 mb-0" style="font-size:11px">Tampil di invoice yang <b>belum dibayar</b> -- rekening tujuan transfer, dsb. Baris baru otomatis jadi baris baru di PDF.</p>
    </div>

    <div class="mb-3">
      <label class="form-label small fw-medium text-dark">Catatan Kaki PDF</label>
      <textarea name="pdf_notes" rows="2" class="form-control form-control-sm" placeholder="Syarat & ketentuan singkat, atau pesan tambahan khusus untuk PDF invoice."><?php echo e(old('pdf_notes', Setting::get('pdf_notes'))); ?></textarea>
      <p class="text-muted mt-1 mb-0" style="font-size:11px">Beda dari "Teks Footer" di Pengaturan Umum (yang itu untuk email/halaman situs) -- ini khusus tampil di bagian bawah PDF invoice.</p>
    </div>

    <div class="rounded-3 p-3 mb-3" style="background:#f8fafc;border:1px dashed #cbd5e1">
      <p class="text-muted mb-0" style="font-size:11px">
        <i class="fa-solid fa-circle-info"></i>
        Nama Perusahaan, Alamat Perusahaan, dan Logo sudah diatur di
        <a href="<?php echo e(route('admin.settings.general')); ?>" class="text-accent">Pengaturan &rarr; Umum</a> --
        halaman ini cuma menambahkan detail khusus PDF di atas.
      </p>
    </div>

    <button type="submit" class="btn btn-primary btn-sm" style="width:fit-content"><i class="fa-solid fa-check" style="font-size:11px"></i> Simpan Pengaturan</button>
  </form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/admin/settings/pdf-invoice.blade.php ENDPATH**/ ?>