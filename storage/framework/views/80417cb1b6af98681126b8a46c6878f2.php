<?php $__env->startSection('title', 'Password Baru'); ?>

<?php $__env->startSection('form'); ?>
  <span class="rounded-4 d-flex align-items-center justify-content-center mb-4" style="width:48px;height:48px;background:rgba(16,185,129,.1);color:#059669">
    <i class="fa-solid fa-lock" style="font-size:18px"></i>
  </span>

  <h2 class="fw-bold text-dark mb-1" style="font-size:1.4rem">Buat Password Baru</h2>
  <p class="text-muted mb-4">Kode Anda sudah terverifikasi. Tentukan password baru di bawah ini.</p>

  <?php if($errors->any()): ?>
    <div class="rounded-3 px-3 py-2 mb-4" style="background:#fef2f2;border:1px solid #fecaca;font-size:14px;color:#b91c1c">
      <?php echo e($errors->first()); ?>

    </div>
  <?php endif; ?>

  <form method="POST" action="<?php echo e(route('client.password.update')); ?>">
    <?php echo csrf_field(); ?>

    <div class="mb-3">
      <label for="password" class="form-label">Password Baru</label>
      <input id="password" name="password" type="password" required autofocus minlength="8"
             autocomplete="new-password" placeholder="••••••••" class="form-control">
      <div class="form-text" style="font-size:12px">Minimal 8 karakter, mengandung huruf dan angka.</div>
    </div>

    <div class="mb-3">
      <label for="password_confirmation" class="form-label">Ulangi Password Baru</label>
      <input id="password_confirmation" name="password_confirmation" type="password" required minlength="8"
             autocomplete="new-password" placeholder="••••••••" class="form-control">
    </div>

    <button type="submit" class="btn btn-theme w-100">
      Simpan Password Baru
    </button>
  </form>

  <p class="text-center text-muted mt-4 mb-0" style="font-size:14px">
    <a href="<?php echo e(route('client.login')); ?>" class="text-decoration-none text-muted">Batal, kembali ke login</a>
  </p>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('client.auth.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/themes/client-themes/default/client/auth/reset.blade.php ENDPATH**/ ?>