<?php $__env->startSection('title', $package->exists ? 'Edit Paket Server' : 'Tambah Paket Server'); ?>

<?php $__env->startSection('content'); ?>
  <div class="mb-3">
    <h1 class="h4 fw-bold text-dark mb-1"><?php echo e($package->exists ? 'Edit Paket Server' : 'Tambah Paket Server'); ?></h1>
    <p class="small text-muted mb-0"><?php echo e($server->name); ?> · Paket ini hanya bisa ditautkan ke produk pada server yang sama.</p>
  </div>

  <form method="POST" action="<?php echo e($package->exists ? route('admin.servers.packages.update', [$server, $package]) : route('admin.servers.packages.store', $server)); ?>" class="card border rounded-4 p-4" style="max-width:46rem">
    <?php echo csrf_field(); ?>
    <?php if($package->exists): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>
    <div class="mb-3">
      <label class="form-label small fw-medium">Nama paket di panel</label>
      <input name="name" value="<?php echo e(old('name', $package->name)); ?>" class="form-control form-control-sm" required maxlength="100" placeholder="cloud_hosting_pro">
      <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>
    <div class="row g-3 mb-3">
      <div class="col-6 col-lg-3">
        <label class="form-label small fw-medium">Disk (GB)</label>
        <input type="number" name="disk_limit" min="1" value="<?php echo e(old('disk_limit', $package->disk_limit)); ?>" class="form-control form-control-sm" placeholder="Tanpa batas">
        <?php $__errorArgs = ['disk_limit'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      </div>
      <div class="col-6 col-lg-3">
        <label class="form-label small fw-medium">Bandwidth (GB)</label>
        <input type="number" name="bandwidth_limit" min="1" value="<?php echo e(old('bandwidth_limit', $package->bandwidth_limit)); ?>" class="form-control form-control-sm" placeholder="Tanpa batas">
        <?php $__errorArgs = ['bandwidth_limit'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      </div>
      <div class="col-6 col-lg-3">
        <label class="form-label small fw-medium">CPU (core)</label>
        <input type="number" name="cpu_limit" min="1" max="65535" value="<?php echo e(old('cpu_limit', $package->cpu_limit)); ?>" class="form-control form-control-sm">
        <?php $__errorArgs = ['cpu_limit'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      </div>
      <div class="col-6 col-lg-3">
        <label class="form-label small fw-medium">RAM (MB)</label>
        <input type="number" name="ram_limit" min="1" value="<?php echo e(old('ram_limit', $package->ram_limit)); ?>" class="form-control form-control-sm">
        <?php $__errorArgs = ['ram_limit'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      </div>
    </div>
    <div class="row g-3 mb-3">
      <div class="col-sm-6">
        <label class="form-label small fw-medium">Harga acuan (Rp)</label>
        <input type="number" name="price" min="0" step="0.01" value="<?php echo e(old('price', $package->price ?? 0)); ?>" class="form-control form-control-sm">
        <div class="text-muted small mt-1">Harga penjualan tetap diatur per siklus pada produk.</div>
        <?php $__errorArgs = ['price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      </div>
      <div class="col-sm-6">
        <label class="form-label small fw-medium">Status</label>
        <select name="status" class="form-select form-select-sm" required>
          <option value="active" <?php if(old('status', $package->status ?? 'active') === 'active'): echo 'selected'; endif; ?>>Aktif</option>
          <option value="inactive" <?php if(old('status', $package->status) === 'inactive'): echo 'selected'; endif; ?>>Nonaktif</option>
        </select>
        <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      </div>
    </div>
    <div class="d-flex gap-2">
      <button class="btn btn-primary btn-sm" type="submit">Simpan Paket</button>
      <a href="<?php echo e(route('admin.servers.packages.index', $server)); ?>" class="btn btn-outline-secondary btn-sm">Batal</a>
    </div>
  </form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/admin/server-packages/form.blade.php ENDPATH**/ ?>