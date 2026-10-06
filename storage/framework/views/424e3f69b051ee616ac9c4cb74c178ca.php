<?php $__env->startSection('title', 'Lupa Password'); ?>

<?php $__env->startSection('form'); ?>
  <span class="rounded-4 d-flex align-items-center justify-content-center mb-4" style="width:48px;height:48px;background:rgba(79,70,229,.1);color:#4f46e5">
    <i class="fa-solid fa-key" style="font-size:18px"></i>
  </span>

  <h2 class="fw-bold text-dark mb-1" style="font-size:1.4rem">Lupa Password</h2>
  <p class="text-muted mb-4">Masukkan email akun Anda. Kami akan mengirim kode verifikasi ke sana.</p>

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

  <form method="POST" action="<?php echo e(route('client.password.email')); ?>">
    <?php echo csrf_field(); ?>

    <div class="mb-3">
      <label for="email" class="form-label">Email Terdaftar</label>
      <input id="email" name="email" type="email" value="<?php echo e(old('email')); ?>" required autofocus
             autocomplete="email" placeholder="email@contoh.com" class="form-control">
    </div>

    <button type="submit" class="btn btn-theme w-100">
      Kirim Kode Reset
    </button>
  </form>

  <p class="text-center text-muted mt-4 mb-0" style="font-size:14px">
    Ingat password Anda?
    <a href="<?php echo e(route('client.login')); ?>" class="text-decoration-none text-theme fw-medium">Masuk</a>
  </p>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('client.auth.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/themes/client-themes/namahost/client/auth/forgot.blade.php ENDPATH**/ ?>