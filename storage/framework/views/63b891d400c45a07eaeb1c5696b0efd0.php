<?php $__env->startSection('title', $group->exists ? 'Edit Grup Server' : 'Tambah Grup Server'); ?>

<?php $__env->startSection('content'); ?>
  <div class="mb-3">
    <h1 class="h4 fw-bold text-dark mb-1"><?php echo e($group->exists ? 'Edit Grup Server' : 'Tambah Grup Server'); ?></h1>
    <p class="small text-muted mb-0">Prioritas lebih kecil diletakkan lebih awal pada daftar.</p>
  </div>

  <form method="POST" action="<?php echo e($group->exists ? route('admin.server-groups.update', $group) : route('admin.server-groups.store')); ?>" class="card border rounded-4 p-4" style="max-width:42rem">
    <?php echo csrf_field(); ?>
    <?php if($group->exists): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>
    <div class="mb-3">
      <label class="form-label small fw-medium">Nama grup</label>
      <input name="name" value="<?php echo e(old('name', $group->name)); ?>" class="form-control form-control-sm" required maxlength="120" placeholder="Contoh: Jakarta - Shared Hosting">
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
      <div class="col-sm-8">
        <label class="form-label small fw-medium">Lokasi</label>
        <input name="location" value="<?php echo e(old('location', $group->location)); ?>" class="form-control form-control-sm" maxlength="120" placeholder="Jakarta, Indonesia">
        <?php $__errorArgs = ['location'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      </div>
      <div class="col-sm-4">
        <label class="form-label small fw-medium">Prioritas</label>
        <input type="number" name="priority" min="0" max="100000" value="<?php echo e(old('priority', $group->priority ?? 0)); ?>" class="form-control form-control-sm" required>
        <?php $__errorArgs = ['priority'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      </div>
    </div>
    <div class="mb-3">
      <label class="form-label small fw-medium">Status</label>
      <select name="status" class="form-select form-select-sm" required>
        <option value="active" <?php if(old('status', $group->status ?? 'active') === 'active'): echo 'selected'; endif; ?>>Aktif</option>
        <option value="inactive" <?php if(old('status', $group->status) === 'inactive'): echo 'selected'; endif; ?>>Nonaktif</option>
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
    <div class="mb-4">
      <label class="form-label small fw-medium">Deskripsi</label>
      <textarea name="description" rows="3" class="form-control form-control-sm"><?php echo e(old('description', $group->description)); ?></textarea>
      <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>
    <div class="d-flex gap-2">
      <button class="btn btn-primary btn-sm" type="submit">Simpan Grup</button>
      <a href="<?php echo e(route('admin.server-groups.index')); ?>" class="btn btn-outline-secondary btn-sm">Batal</a>
    </div>
  </form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/admin/server-groups/form.blade.php ENDPATH**/ ?>