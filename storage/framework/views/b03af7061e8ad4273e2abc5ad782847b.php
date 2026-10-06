<?php $__env->startSection('title', 'Buat Tiket'); ?>

<?php $__env->startSection('content'); ?>

  <div class="mb-4">
    <h1 class="h4 fw-bold text-dark mb-1">Buat Tiket</h1>
    <p class="small text-muted mb-0">Untuk mencatat keluhan klien yang masuk lewat jalur lain (telepon, WhatsApp, dsb).</p>
  </div>

  <form method="POST" action="<?php echo e(route('admin.ticket.add')); ?>" enctype="multipart/form-data" class="card border rounded-4 p-4" style="max-width:42rem">
    <?php echo csrf_field(); ?>

    <div class="mb-3">
      <label class="form-label small fw-medium text-dark">Klien</label>
      <select name="client_id" class="form-select" style="padding:.25rem .6rem;font-size:.875rem;border-radius:.375rem" required>
        <option value="">Pilih klien</option>
        <?php $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <option value="<?php echo e($client->id); ?>" <?php if(old('client_id') == $client->id): echo 'selected'; endif; ?>><?php echo e($client->name); ?> (<?php echo e($client->email); ?>)</option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </select>
      <?php $__errorArgs = ['client_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    <div class="mb-3">
      <label class="form-label small fw-medium text-dark">Subjek</label>
      <input type="text" name="subject" value="<?php echo e(old('subject')); ?>" placeholder="Website tidak bisa diakses" class="form-control form-control-sm" required>
      <?php $__errorArgs = ['subject'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    <div class="row g-3 mb-3">
      <div class="col-sm-6">
        <label class="form-label small fw-medium text-dark">Departemen</label>
        <select name="department" class="form-select" style="padding:.25rem .6rem;font-size:.875rem;border-radius:.375rem">
          <option value="support" <?php if(old('department') === 'support'): echo 'selected'; endif; ?>>Technical Support</option>
          <option value="billing" <?php if(old('department') === 'billing'): echo 'selected'; endif; ?>>Billing</option>
          <option value="sales" <?php if(old('department') === 'sales'): echo 'selected'; endif; ?>>Sales</option>
          <option value="abuse" <?php if(old('department') === 'abuse'): echo 'selected'; endif; ?>>Abuse</option>
        </select>
      </div>
      <div class="col-sm-6">
        <label class="form-label small fw-medium text-dark">Prioritas</label>
        <select name="priority" class="form-select" style="padding:.25rem .6rem;font-size:.875rem;border-radius:.375rem">
          <option value="low" <?php if(old('priority') === 'low'): echo 'selected'; endif; ?>>Low</option>
          <option value="medium" <?php if(old('priority', 'medium') === 'medium'): echo 'selected'; endif; ?>>Medium</option>
          <option value="high" <?php if(old('priority') === 'high'): echo 'selected'; endif; ?>>High</option>
          <option value="urgent" <?php if(old('priority') === 'urgent'): echo 'selected'; endif; ?>>Urgent</option>
        </select>
      </div>
    </div>

    <div class="mb-3">
      <label class="form-label small fw-medium text-dark">Pesan / Keluhan</label>
      <textarea name="message" rows="5" class="form-control form-control-sm" placeholder="Tuliskan keluhan klien..." required><?php echo e(old('message')); ?></textarea>
      <?php $__errorArgs = ['message'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    <div class="mb-3">
      <label class="form-label small fw-medium text-dark">Lampiran <span class="text-muted fw-normal">(opsional, maks 5 berkas @ 5MB)</span></label>
      <input type="file" name="attachments[]" multiple class="form-control form-control-sm">
      <?php $__errorArgs = ['attachments'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      <?php $__errorArgs = ['attachments.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    <div class="d-flex align-items-center gap-2 pt-2">
      <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-check" style="font-size:11px"></i> Buat Tiket</button>
      <a href="<?php echo e(route('admin.tickets')); ?>" class="btn btn-outline-secondary btn-sm">Batal</a>
    </div>
  </form>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/admin/tickets/form.blade.php ENDPATH**/ ?>