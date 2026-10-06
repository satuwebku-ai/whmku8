<?php $__env->startSection('title', $client->exists ? 'Edit Klien' : 'Tambah Klien'); ?>

<?php $__env->startSection('content'); ?>

  <div class="mb-4">
    <h1 class="h4 fw-bold text-dark mb-1"><?php echo e($client->exists ? 'Edit Klien' : 'Tambah Klien Baru'); ?></h1>
    <p class="small text-muted mb-0">Lengkapi data pelanggan di bawah ini.</p>
  </div>

  <form method="POST" action="<?php echo e($client->exists ? route('admin.client.update', $client) : route('admin.client.add')); ?>" class="card border rounded-4 p-4" style="max-width:42rem">
    <?php echo csrf_field(); ?>

    <div class="row g-3 mb-3">
      <div class="col-sm-6">
        <label class="form-label small fw-medium text-dark">Nama Lengkap</label>
        <input type="text" name="name" value="<?php echo e(old('name', $client->name)); ?>" class="form-control form-control-sm" required>
        <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      </div>
      <div class="col-sm-6">
        <label class="form-label small fw-medium text-dark">Email</label>
        <input type="email" name="email" value="<?php echo e(old('email', $client->email)); ?>" class="form-control form-control-sm" required>
        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      </div>
    </div>

    <div class="row g-3 mb-3">
      <div class="col-sm-6">
        <label class="form-label small fw-medium text-dark">No. Telepon</label>
        <input type="text" name="phone" value="<?php echo e(old('phone', $client->phone)); ?>" class="form-control form-control-sm">
        <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      </div>
      <div class="col-sm-6">
        <label class="form-label small fw-medium text-dark">Perusahaan (opsional)</label>
        <input type="text" name="company" value="<?php echo e(old('company', $client->company)); ?>" class="form-control form-control-sm">
        <?php $__errorArgs = ['company'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      </div>
    </div>

    <div class="mb-3">
      <label class="form-label small fw-medium text-dark">Alamat</label>
      <textarea name="address" rows="2" class="form-control form-control-sm"><?php echo e(old('address', $client->address)); ?></textarea>
      <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    <div class="row g-3 mb-3">
      <div class="col-sm-4">
        <label class="form-label small fw-medium text-dark">Kota</label>
        <input type="text" name="city" value="<?php echo e(old('city', $client->city)); ?>" class="form-control form-control-sm">
      </div>
      <div class="col-sm-4">
        <label class="form-label small fw-medium text-dark">Negara</label>
        <input type="text" name="country" value="<?php echo e(old('country', $client->country ?? 'Indonesia')); ?>" class="form-control form-control-sm">
      </div>
      <div class="col-sm-4">
        <label class="form-label small fw-medium text-dark">Status</label>
        <select name="status" class="form-select" style="padding:.25rem .6rem;font-size:.875rem;border-radius:.375rem">
          <option value="active" <?php if(old('status', $client->status) === 'active'): echo 'selected'; endif; ?>>Aktif</option>
          <option value="inactive" <?php if(old('status', $client->status) === 'inactive'): echo 'selected'; endif; ?>>Nonaktif</option>
        </select>
      </div>
    </div>

    <div class="d-flex align-items-center gap-2 pt-2">
      <button type="submit" class="btn btn-primary btn-sm">
        <i class="fa-solid fa-check" style="font-size:11px"></i> <?php echo e($client->exists ? 'Simpan Perubahan' : 'Simpan Klien'); ?>

      </button>
      <a href="<?php echo e(route('admin.clients')); ?>" class="btn btn-outline-secondary btn-sm">Batal</a>
    </div>
  </form>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/admin/clients/form.blade.php ENDPATH**/ ?>