<?php $__env->startSection('title', 'Daftar'); ?>

<?php $__env->startSection('form'); ?>
  <h2 class="fw-bold text-dark mb-1" style="font-size:1.4rem">Buat Akun Baru</h2>
  <p class="text-muted mb-4">Gratis, hanya butuh satu menit.</p>

  <?php if($errors->any()): ?>
    <div class="rounded-3 px-3 py-2 mb-4" style="background:#fef2f2;border:1px solid #fecaca;font-size:14px;color:#b91c1c">
      <ul class="mb-0 ps-3">
        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <li style="margin-bottom:.25rem"><?php echo e($error); ?></li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </ul>
    </div>
  <?php endif; ?>

  <form method="POST" action="<?php echo e(route('client.register.store')); ?>">
    <?php echo csrf_field(); ?>

    <div class="mb-3">
      <label class="form-label">Nama Lengkap</label>
      <input name="name" type="text" value="<?php echo e(old('name')); ?>" required autofocus class="form-control">
    </div>

    <div class="row g-3 mb-3">
      <div class="col-sm-6">
        <label class="form-label">Email</label>
        <input name="email" type="email" value="<?php echo e(old('email')); ?>" required class="form-control">
      </div>
      <div class="col-sm-6">
        <label class="form-label">No. WhatsApp / Telepon</label>
        <input name="phone" type="text" value="<?php echo e(old('phone')); ?>" required placeholder="0812xxxxxxx" class="form-control">
      </div>
    </div>

    <div class="mb-3">
      <label class="form-label">Perusahaan <span class="text-muted fw-normal">(opsional)</span></label>
      <input name="company" type="text" value="<?php echo e(old('company')); ?>" class="form-control">
    </div>

    <div class="mb-3">
      <label class="form-label">Alamat <span class="text-muted fw-normal">(opsional)</span></label>
      <input name="address" type="text" value="<?php echo e(old('address')); ?>" class="form-control">
    </div>

    <div class="row g-3 mb-3">
      <div class="col-sm-6">
        <label class="form-label">Kota</label>
        <input name="city" type="text" value="<?php echo e(old('city')); ?>" class="form-control">
      </div>
      <div class="col-sm-6">
        <label class="form-label">Negara</label>
        <input name="country" type="text" value="<?php echo e(old('country', 'Indonesia')); ?>" class="form-control">
      </div>
    </div>

    <div class="row g-3 mb-3">
      <div class="col-sm-6">
        <label class="form-label">Provinsi <span class="text-muted fw-normal">(untuk registrasi domain)</span></label>
        <input name="state" type="text" value="<?php echo e(old('state')); ?>" placeholder="DKI Jakarta" class="form-control">
      </div>
      <div class="col-sm-6">
        <label class="form-label">Kode Pos</label>
        <input name="postal_code" type="text" value="<?php echo e(old('postal_code')); ?>" class="form-control">
      </div>
    </div>

    <div class="row g-3 mb-3">
      <div class="col-sm-6">
        <label class="form-label">Password</label>
        <input name="password" type="password" required class="form-control">
        <p class="text-muted mt-1 mb-0" style="font-size:11px">Min. 8 karakter, ada huruf & angka.</p>
      </div>
      <div class="col-sm-6">
        <label class="form-label">Ulangi Password</label>
        <input name="password_confirmation" type="password" required class="form-control">
      </div>
    </div>

    <label class="d-flex align-items-start gap-2 small text-dark mb-3">
      <input type="checkbox" name="terms" value="1" <?php if(old('terms')): echo 'checked'; endif; ?> class="form-check-input flex-shrink-0" style="margin-top:2px">
      <span>
        Saya menyetujui
        <a href="<?php echo e(route('page.show', 'syarat-ketentuan')); ?>" target="_blank" class="text-theme">Syarat &amp; Ketentuan</a>
        dan
        <a href="<?php echo e(route('page.show', 'kebijakan-privasi')); ?>" target="_blank" class="text-theme">Kebijakan Privasi</a>.
      </span>
    </label>

    <?php echo $__env->make('partials.captcha', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <button type="submit" class="btn btn-theme w-100">
      Daftar
    </button>
  </form>

  <?php if(config('services.google.client_id') && config('services.google.client_secret')): ?>
    <div class="d-flex align-items-center gap-3 my-4">
      <span class="flex-grow-1 border-top"></span>
      <span class="text-muted" style="font-size:12px">atau</span>
      <span class="flex-grow-1 border-top"></span>
    </div>

    <a href="<?php echo e(route('client.google.redirect')); ?>"
       class="btn btn-outline-secondary w-100 d-flex align-items-center justify-content-center gap-2">
      <svg width="18" height="18" viewBox="0 0 18 18"><path fill="#4285F4" d="M17.64 9.2c0-.64-.06-1.25-.16-1.84H9v3.48h4.84a4.14 4.14 0 0 1-1.8 2.72v2.26h2.9c1.7-1.57 2.7-3.88 2.7-6.62z"/><path fill="#34A853" d="M9 18c2.43 0 4.47-.8 5.96-2.18l-2.9-2.26c-.8.54-1.83.86-3.06.86-2.35 0-4.34-1.59-5.05-3.72H.96v2.33A9 9 0 0 0 9 18z"/><path fill="#FBBC05" d="M3.95 10.7A5.4 5.4 0 0 1 3.67 9c0-.59.1-1.17.28-1.7V4.97H.96A9 9 0 0 0 0 9c0 1.45.35 2.83.96 4.03l2.99-2.33z"/><path fill="#EA4335" d="M9 3.58c1.32 0 2.51.45 3.44 1.35l2.58-2.58C13.46.89 11.43 0 9 0A9 9 0 0 0 .96 4.97l2.99 2.33C4.66 5.17 6.65 3.58 9 3.58z"/></svg>
      Daftar dengan Google
    </a>
    <p class="text-muted text-center mt-2 mb-0" style="font-size:11px">Langsung aktif — tidak perlu verifikasi email lagi.</p>
  <?php endif; ?>

  <p class="text-center text-muted mt-4 mb-0" style="font-size:14px">
    Sudah punya akun?
    <a href="<?php echo e(route('client.login')); ?>" class="text-decoration-none text-theme fw-medium">Masuk di sini</a>
  </p>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('client.auth.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/themes/client-themes/namahost/client/auth/register.blade.php ENDPATH**/ ?>