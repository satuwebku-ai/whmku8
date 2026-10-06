<?php $id = $t?->id ?? 'new'; ?>
<div class="row g-2">
  <div class="col-md-7">
    <label class="form-label small fw-medium">Judul</label>
    <input type="text" name="title" value="<?php echo e(old('title', $t?->title)); ?>" maxlength="120" required class="form-control form-control-sm" placeholder="mis. Konfirmasi pembayaran">
    <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-danger small mb-0"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
  </div>
  <div class="col-md-5">
    <label class="form-label small fw-medium">Kategori <span class="text-muted fw-normal">(opsional)</span></label>
    <input type="text" name="category" value="<?php echo e(old('category', $t?->category)); ?>" maxlength="60" list="tplCats<?php echo e($id); ?>" class="form-control form-control-sm" placeholder="Pembayaran, Teknis, Umum…">
    <datalist id="tplCats<?php echo e($id); ?>"><?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($c); ?>"><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></datalist>
  </div>
  <div class="col-12">
    <label class="form-label small fw-medium">Subjek email <span class="text-muted fw-normal">(opsional, mengisi subjek saat dipakai di email)</span></label>
    <input type="text" name="subject" value="<?php echo e(old('subject', $t?->subject)); ?>" maxlength="200" class="form-control form-control-sm">
  </div>
  <div class="col-12">
    <label class="form-label small fw-medium">Isi template</label>
    <textarea name="body" rows="7" maxlength="5000" required class="form-control form-control-sm" placeholder="Halo {nama}, …"><?php echo e(old('body', $t?->body)); ?></textarea>
    <?php $__errorArgs = ['body'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-danger small mb-0"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
  </div>
  <div class="col-12 d-flex flex-wrap gap-4">
    <label class="d-flex align-items-center gap-2 small mb-0">
      <input type="checkbox" class="form-check-input mt-0" name="is_active" value="1" <?php if(old('is_active', $t?->is_active ?? true)): echo 'checked'; endif; ?>> Aktif (tampil di pilihan template)
    </label>
    <label class="d-flex align-items-center gap-2 small mb-0">
      <input type="checkbox" class="form-check-input mt-0" name="use_for_ai" value="1" <?php if(old('use_for_ai', $t?->use_for_ai ?? false)): echo 'checked'; endif; ?>> Dipakai AI sebagai panduan jawaban
    </label>
  </div>
</div>
<?php /**PATH /home/runner/workspace/hosting-billing/resources/views/admin/templates/_fields.blade.php ENDPATH**/ ?>