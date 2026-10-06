<?php $__env->startSection('title', $requirement->exists ? 'Edit Persyaratan' : 'Tambah Persyaratan'); ?>
<?php $__env->startSection('content'); ?>
  <?php echo $__env->make('admin.settings._nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

  <div class="mb-4">
    <a href="<?php echo e(route('admin.settings.requirements.index')); ?>" class="text-decoration-none text-muted" style="font-size:12px">
      <i class="fa-solid fa-arrow-left"></i> Kembali ke Persyaratan
    </a>
    <h1 class="h4 fw-bold text-dark mt-1 mb-0"><?php echo e($requirement->exists ? 'Edit Persyaratan' : 'Tambah Persyaratan'); ?></h1>
  </div>

  <form method="POST"
        action="<?php echo e($requirement->exists ? route('admin.settings.requirements.update', $requirement) : route('admin.settings.requirements.store')); ?>"
        class="card border rounded-4 p-4" style="max-width:38rem">
    <?php echo csrf_field(); ?>
    <?php if($requirement->exists): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>

    <div class="mb-3">
      <label class="form-label small fw-medium text-dark">Nama Berkas</label>
      <input type="text" name="name" value="<?php echo e(old('name', $requirement->name)); ?>"
             placeholder="mis. KTP Penanggung Jawab" class="form-control form-control-sm" required>
      <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    <div class="mb-3">
      <label class="form-label small fw-medium text-dark">Petunjuk untuk Klien</label>
      <textarea name="description" rows="3" class="form-control form-control-sm"
                placeholder="Dijelaskan ke klien saat mengunggah — mis. format file, siapa yang harus tercantum."><?php echo e(old('description', $requirement->description)); ?></textarea>
      <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    <div class="mb-3">
      <label class="form-label small fw-medium text-dark">Urutan Tampil</label>
      <input type="number" name="sort_order" min="0" max="999"
             value="<?php echo e(old('sort_order', $requirement->sort_order ?? 0)); ?>"
             class="form-control form-control-sm" style="max-width:8rem">
      <p class="text-muted mt-1 mb-0" style="font-size:11px">Angka kecil tampil lebih dulu.</p>
    </div>

    <div class="d-flex flex-column gap-2 pt-3 border-top">
      <label class="d-flex align-items-start gap-2" style="cursor:pointer">
        <input type="checkbox" name="is_required" value="1" <?php if(old('is_required', $requirement->is_required ?? true)): echo 'checked'; endif; ?> class="form-check-input" style="margin-top:.15rem">
        <span>
          <span class="d-block fw-medium text-dark" style="font-size:13px">Wajib diunggah</span>
          <span class="d-block text-muted" style="font-size:11px">
            Kalau dimatikan, berkas ini tetap ditawarkan ke klien tapi domain tetap bisa diproses walau tidak diunggah
            (mis. "Sertifikat merek, kalau ada").
          </span>
        </span>
      </label>

      <label class="d-flex align-items-start gap-2" style="cursor:pointer">
        <input type="checkbox" name="is_active" value="1" <?php if(old('is_active', $requirement->is_active ?? true)): echo 'checked'; endif; ?> class="form-check-input" style="margin-top:.15rem">
        <span>
          <span class="d-block fw-medium text-dark" style="font-size:13px">Aktif</span>
          <span class="d-block text-muted" style="font-size:11px">
            Persyaratan nonaktif tidak diminta ke klien baru, tapi berkas yang sudah pernah diunggah tetap tersimpan.
          </span>
        </span>
      </label>
    </div>

    <div class="d-flex align-items-center gap-2 pt-3 mt-3 border-top">
      <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-check" style="font-size:11px"></i> Simpan</button>
      <a href="<?php echo e(route('admin.settings.requirements.index')); ?>" class="btn btn-outline-secondary btn-sm">Batal</a>
    </div>
  </form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/admin/settings/requirements/form.blade.php ENDPATH**/ ?>