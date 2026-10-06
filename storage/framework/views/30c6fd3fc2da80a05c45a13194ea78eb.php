<?php $__env->startSection('title', 'Kirim Promo'); ?>

<?php $__env->startSection('content'); ?>

  <?php echo $__env->make('admin.activities._nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

  <div class="mb-4">
    <h1 class="h4 fw-bold text-dark mb-1">Kirim Promo ke Klien</h1>
    <p class="small text-muted mb-0">Email promosi ke seluruh klien aktif yang bersedia menerimanya.</p>
  </div>

  <div class="row g-3" style="max-width:70rem">
    <div class="col-12 col-lg-8">
      <form method="POST" action="<?php echo e(route('admin.promo.send')); ?>" class="card border rounded-4 p-4"
            data-confirm="Kirim promo ini ke <?php echo e($optedIn); ?> klien? Email yang sudah terkirim tidak bisa ditarik kembali."
            data-confirm-title="Kirim Promo" data-confirm-style="warn" data-confirm-label="Ya, Kirim Sekarang">
        <?php echo csrf_field(); ?>

        <div class="mb-3">
          <label class="form-label small fw-medium text-dark">Judul</label>
          <input type="text" name="judul" value="<?php echo e(old('judul')); ?>" class="form-control form-control-sm" required
                 placeholder="Diskon 30% untuk semua paket hosting">
          <?php $__errorArgs = ['judul'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          <p class="text-muted mt-1 mb-0" style="font-size:11px">Dipakai sebagai subjek email.</p>
        </div>

        <div class="mb-3">
          <label class="form-label small fw-medium text-dark">Isi Pesan</label>
          <textarea name="isi" rows="8" class="form-control form-control-sm" required
                    placeholder="Tulis isi promosi di sini.&#10;&#10;Pisahkan dengan baris kosong untuk membuat paragraf baru."><?php echo e(old('isi')); ?></textarea>
          <?php $__errorArgs = ['isi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          <p class="text-muted mt-1 mb-0" style="font-size:11px">Teks biasa. Baris kosong menjadi paragraf baru.</p>
        </div>

        <div class="row g-3 mb-3">
          <div class="col-sm-6">
            <label class="form-label small fw-medium text-dark">Tautan Tombol (opsional)</label>
            <input type="url" name="tautan" value="<?php echo e(old('tautan')); ?>" class="form-control form-control-sm" placeholder="https://...">
            <?php $__errorArgs = ['tautan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>
          <div class="col-sm-6">
            <label class="form-label small fw-medium text-dark">Teks Tombol</label>
            <input type="text" name="label_tautan" value="<?php echo e(old('label_tautan')); ?>" class="form-control form-control-sm" placeholder="Lihat Promo">
          </div>
        </div>

        <label class="d-flex align-items-start gap-2 small text-dark pt-3 border-top mb-2">
          <input type="checkbox" name="konfirmasi" value="1" class="form-check-input flex-shrink-0" style="margin-top:2px">
          <span>Saya sudah memeriksa isi pesan dan siap mengirimnya ke <?php echo e($optedIn); ?> klien.</span>
        </label>
        <?php $__errorArgs = ['konfirmasi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mb-2" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

        <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-paper-plane" style="font-size:11px"></i> Kirim Promo</button>
      </form>
    </div>

    <div class="col-12 col-lg-4">
      <div class="card border rounded-4 p-4 mb-3">
        <h2 class="small fw-bold text-dark mb-3">Penerima</h2>
        <div class="d-flex justify-content-between small mb-2">
          <span class="text-muted">Klien aktif</span>
          <span class="fw-medium text-dark"><?php echo e($total); ?></span>
        </div>
        <div class="d-flex justify-content-between small">
          <span class="text-muted">Bersedia dikirimi promo</span>
          <span class="fw-bold text-accent"><?php echo e($optedIn); ?></span>
        </div>
        <?php if($total > $optedIn): ?>
          <p class="text-muted mt-2 pt-2 border-top mb-0" style="font-size:11px">
            <?php echo e($total - $optedIn); ?> klien menolak email promosi. Pilihan mereka dihormati dan tidak bisa ditimpa dari sini.
          </p>
        <?php endif; ?>
      </div>

      <div class="card border rounded-4 p-4" style="background:#fffbeb;border-color:#fde68a!important">
        <p class="mb-0" style="font-size:12px;color:#92400e;line-height:1.6">
          <i class="fa-solid fa-triangle-exclamation"></i>
          <b>Sebelum mengirim:</b> pastikan domain pengirim sudah punya SPF dan DKIM di DNS.
          Mengirim promo massal dari domain tanpa keduanya membuat email masuk spam,
          dan bisa membuat email transaksional Anda (invoice, reset password) ikut terblokir.
        </p>
      </div>
    </div>
  </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/admin/activities/promo.blade.php ENDPATH**/ ?>