<?php $__env->startSection('title', 'Buat Tiket'); ?>

<?php $__env->startSection('content'); ?>
  <a href="<?php echo e(route('client.tickets')); ?>" class="text-decoration-none text-muted" style="font-size:12px">&larr; Kembali ke Tiket</a>

  <h1 class="h4 fw-bold text-dark mt-2 mb-1">Buat Tiket Support</h1>
  <p class="text-muted mb-4">Jelaskan kendala Anda sedetail mungkin agar kami bisa membantu lebih cepat.</p>

  <?php if($errors->any()): ?>
    <div class="rounded-3 px-3 py-2 mb-4" style="background:#fef2f2;border:1px solid #fecaca;font-size:14px;color:#b91c1c">
      <ul class="mb-0 ps-3">
        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li style="margin-bottom:.25rem"><?php echo e($error); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </ul>
    </div>
  <?php endif; ?>

  <form method="POST" action="<?php echo e(route('client.tickets.store')); ?>" enctype="multipart/form-data" class="card-public p-4 d-flex flex-column gap-3" style="max-width:42rem">
    <?php echo csrf_field(); ?>

    <div>
      <label class="form-label">Subjek</label>
      <input type="text" name="subject" value="<?php echo e(old('subject', request('subject'))); ?>" required class="form-control" placeholder="Website tidak bisa diakses">
    </div>

    <div class="row g-3">
      <div class="col-sm-6">
        <label class="form-label">Departemen</label>
        <select name="department" class="form-select">
          <option value="support" <?php if(old('department', request('department')) === 'support'): echo 'selected'; endif; ?>>Bantuan Teknis</option>
          <option value="billing" <?php if(old('department', request('department')) === 'billing'): echo 'selected'; endif; ?>>Tagihan &amp; Pembayaran</option>
          <option value="sales" <?php if(old('department', request('department')) === 'sales'): echo 'selected'; endif; ?>>Penjualan</option>
        </select>
      </div>
      <div class="col-sm-6">
        <label class="form-label">Prioritas</label>
        <select name="priority" class="form-select">
          <option value="low" <?php if(old('priority') === 'low'): echo 'selected'; endif; ?>>Rendah</option>
          <option value="medium" <?php if(old('priority', 'medium') === 'medium'): echo 'selected'; endif; ?>>Sedang</option>
          <option value="high" <?php if(old('priority') === 'high'): echo 'selected'; endif; ?>>Tinggi</option>
        </select>
      </div>
    </div>

    <?php if($services->isNotEmpty()): ?>
      <div>
        <label class="form-label">Layanan Terkait <span class="text-muted fw-normal">(opsional)</span></label>
        <select name="hosting_account_id" class="form-select">
          <option value="">— Tidak terkait layanan tertentu —</option>
          <?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($service->id); ?>" <?php if(old('hosting_account_id') == $service->id): echo 'selected'; endif; ?>><?php echo e($service->domain); ?> (<?php echo e($service->package); ?>)</option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
      </div>
    <?php endif; ?>

    <div>
      <label class="form-label">Pesan</label>
      <textarea name="message" rows="7" required class="form-control" placeholder="Ceritakan kendala Anda..."><?php echo e(old('message', request('message'))); ?></textarea>
    </div>

    <div>
      <label class="form-label">Lampiran <span class="text-muted fw-normal">(opsional, maks 5 berkas @ 5MB)</span></label>
      <input type="file" name="attachments[]" multiple class="form-control form-control-sm">
      <p class="text-muted mt-1 mb-0" style="font-size:11px">Format: jpg, png, pdf, txt, log, zip. Screenshot error sangat membantu. Bisa pilih beberapa berkas sekaligus.</p>
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

    <div class="d-flex gap-2 pt-2">
      <button type="submit" class="btn btn-theme"><i class="fa-solid fa-paper-plane" style="font-size:11px"></i> Kirim Tiket</button>
      <a href="<?php echo e(route('client.tickets')); ?>" class="btn btn-outline-secondary">Batal</a>
    </div>
  </form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('client.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/themes/client-themes/namahost/client/tickets/create.blade.php ENDPATH**/ ?>