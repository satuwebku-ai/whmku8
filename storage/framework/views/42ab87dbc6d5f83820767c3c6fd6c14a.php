<?php $__env->startSection('title', 'Buat Pembayaran'); ?>

<?php $__env->startSection('content'); ?>

  <div class="mb-4">
    <h1 class="h4 fw-bold text-dark mb-1">Buat Pembayaran</h1>
    <p class="small text-muted mb-0">Pilih invoice yang belum lunas dan gateway yang dipakai. Untuk gateway otomatis, link pembayaran langsung dibuat.</p>
  </div>

  <?php if($invoices->isEmpty()): ?>
    <div class="card border rounded-4 p-4 text-center text-muted small" style="max-width:42rem">
      Tidak ada invoice berstatus unpaid/overdue. Buat invoice dulu di menu Invoice.
    </div>
  <?php elseif($gateways->isEmpty()): ?>
    <div class="card border rounded-4 p-4 text-center text-muted small" style="max-width:42rem">
      Belum ada payment gateway aktif. Tambahkan dulu di
      <a href="<?php echo e(route('admin.gateways')); ?>" class="text-accent">tab Gateway</a>.
    </div>
  <?php else: ?>
    <form method="POST" action="<?php echo e(route('admin.payment.add')); ?>" class="card border rounded-4 p-4" style="max-width:42rem">
      <?php echo csrf_field(); ?>

      <div class="mb-3">
        <label class="form-label small fw-medium text-dark">Invoice</label>
        <select name="invoice_id" class="form-select" style="padding:.25rem .6rem;font-size:.875rem;border-radius:.375rem" required>
          <option value="">Pilih invoice</option>
          <?php $__currentLoopData = $invoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($invoice->id); ?>" <?php if(old('invoice_id') == $invoice->id): echo 'selected'; endif; ?>>
              <?php echo e($invoice->invoice_number); ?> — <?php echo e($invoice->client->name ?? '—'); ?> (Rp <?php echo e(number_format($invoice->total, 0, ',', '.')); ?>)
            </option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <?php $__errorArgs = ['invoice_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      </div>

      <div class="mb-3">
        <label class="form-label small fw-medium text-dark">Payment Gateway</label>
        <select name="payment_gateway_id" class="form-select" style="padding:.25rem .6rem;font-size:.875rem;border-radius:.375rem" required>
          <option value="">Pilih gateway</option>
          <?php $__currentLoopData = $gateways; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gw): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($gw->id); ?>" <?php if(old('payment_gateway_id') == $gw->id): echo 'selected'; endif; ?>>
              <?php echo e($gw->name); ?> (<?php echo e($gw->driver_label); ?><?php echo e($gw->isSandbox() && ! $gw->isManual() ? ' — Sandbox' : ''); ?>)
            </option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <?php $__errorArgs = ['payment_gateway_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        <p class="text-muted mt-1 mb-0" style="font-size:11px">Biaya gateway (jika ada) otomatis ditambahkan ke total tagihan.</p>
      </div>

      <div class="d-flex align-items-center gap-2 pt-2">
        <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-check" style="font-size:11px"></i> Buat Pembayaran</button>
        <a href="<?php echo e(route('admin.payments')); ?>" class="btn btn-outline-secondary btn-sm">Batal</a>
      </div>
    </form>
  <?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/admin/payments/form.blade.php ENDPATH**/ ?>