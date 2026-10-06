<?php $__env->startSection('title', $menu->exists ? 'Edit Menu Utama' : 'Tambah Menu Utama'); ?>

<?php $__env->startSection('content'); ?>
  <?php echo $__env->make('admin.pages._nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

  <div class="mb-3">
    <a href="<?php echo e(route('admin.nav-menus')); ?>" class="text-decoration-none text-muted" style="font-size:12px">
      <i class="fa-solid fa-arrow-left"></i> Kembali ke Menu Utama
    </a>
  </div>

  <div class="mb-3">
    <h1 class="h4 fw-bold text-dark mb-1"><?php echo e($menu->exists ? 'Edit Menu Utama' : 'Tambah Menu Utama'); ?></h1>
    <p class="small text-muted mb-0">Menu ini akan tampil langsung di navbar publik. Submenu dibuat di halaman terpisah.</p>
  </div>

  <form method="POST" action="<?php echo e($menu->exists ? route('admin.nav-menu.update', $menu) : route('admin.nav-menu.add')); ?>" class="card border rounded-4 p-4" style="max-width:42rem">
    <?php echo csrf_field(); ?>

    <div class="mb-3">
      <label class="form-label small fw-medium text-dark">Nama Menu Utama</label>
      <input type="text" name="label" value="<?php echo e(old('label', $menu->label)); ?>" class="form-control form-control-sm" placeholder="Contoh: Hosting" maxlength="50" required autofocus>
      <?php $__errorArgs = ['label'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    <?php echo $__env->make('admin.nav-menus._destination-fields', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <label class="d-flex align-items-center gap-2 small text-dark mb-3">
      <input type="checkbox" name="is_active" value="1" <?php if(old('is_active', $menu->is_active ?? true)): echo 'checked'; endif; ?> class="form-check-input" style="margin-top:0">
      Tampilkan di navbar publik
    </label>

    <div class="d-flex align-items-center gap-2 pt-2 border-top">
      <button type="submit" class="btn btn-primary btn-sm mt-2"><i class="fa-solid fa-check"></i> Simpan Menu Utama</button>
      <a href="<?php echo e(route('admin.nav-menus')); ?>" class="btn btn-outline-secondary btn-sm mt-2">Batal</a>
    </div>
  </form>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/admin/nav-menus/main-form.blade.php ENDPATH**/ ?>