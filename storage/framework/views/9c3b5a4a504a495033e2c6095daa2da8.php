<?php $__env->startSection('title', 'Verifikasi Kode'); ?>

<?php $__env->startSection('form'); ?>
  <span class="rounded-4 d-flex align-items-center justify-content-center mb-4" style="width:48px;height:48px;background:rgba(79,70,229,.1);color:#4f46e5">
    <i class="fa-solid fa-shield-halved" style="font-size:18px"></i>
  </span>

  <h2 class="fw-bold text-dark mb-1" style="font-size:1.4rem">Masukkan Kode</h2>
  <p class="text-muted mb-4">
    Kami mengirim kode 6 digit ke <b class="text-dark"><?php echo e($email); ?></b>. Kode berlaku 15 menit.
  </p>

  <?php if(session('success')): ?>
    <div class="rounded-3 px-3 py-2 mb-4" style="background:#f0fdf4;border:1px solid #bbf7d0;font-size:14px;color:#15803d">
      <?php echo e(session('success')); ?>

    </div>
  <?php endif; ?>

  <?php if($errors->any()): ?>
    <div class="rounded-3 px-3 py-2 mb-4" style="background:#fef2f2;border:1px solid #fecaca;font-size:14px;color:#b91c1c">
      <?php echo e($errors->first()); ?>

    </div>
  <?php endif; ?>

  <form method="POST" action="<?php echo e(route('client.password.verify.code')); ?>">
    <?php echo csrf_field(); ?>

    <div class="mb-3">
      <label for="code" class="form-label">Kode Verifikasi</label>
      <input id="code" name="code" type="text" inputmode="numeric" pattern="[0-9]{6}" maxlength="6" required autofocus
             autocomplete="one-time-code" placeholder="000000" class="form-control text-center"
             style="font-size:1.75rem;font-weight:700;letter-spacing:.5em;padding:.75rem 0 .75rem .5em">
    </div>

    <button type="submit" class="btn btn-theme w-100">
      Verifikasi Kode
    </button>
  </form>

  <div class="d-flex align-items-center justify-content-between mt-4 pt-3 border-top" style="font-size:13px">
    <span class="text-muted">Tidak menerima kode?
      <a href="<?php echo e(route('client.password.request')); ?>" class="text-theme fw-medium">Kirim ulang</a>
    </span>
    <a href="<?php echo e(route('client.login')); ?>" class="text-muted text-decoration-none">Kembali ke login</a>
  </div>

  <p class="text-center text-muted mt-4 mb-0" style="font-size:11px">
    Periksa juga folder spam. Jangan bagikan kode ini ke siapapun.
  </p>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('client.auth.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/themes/client-themes/namahost/client/auth/verify-code.blade.php ENDPATH**/ ?>