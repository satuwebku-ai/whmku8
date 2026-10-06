<?php $__env->startSection('title', 'Pilih Metode Pembayaran'); ?>

<?php $__env->startSection('content'); ?>
  <a href="<?php echo e(route('client.invoices.show', $invoice)); ?>" class="text-decoration-none text-muted" style="font-size:12px">
    &larr; Kembali ke Invoice <?php echo e($invoice->invoice_number); ?>

  </a>

  <div class="mt-2 mb-4">
    <h1 class="h4 fw-bold text-dark mb-1">Pilih Metode Pembayaran</h1>
    <p class="text-muted mb-0">
      Total tagihan: <b class="text-dark">Rp <?php echo e(number_format($total, 0, ',', '.')); ?></b>
    </p>
  </div>

  <?php if($grouped->isEmpty()): ?>
    <div class="card-public p-5 text-center">
      <p class="text-muted mb-0" style="font-size:14px">Tidak ada metode pembayaran yang tersedia saat ini. Silakan hubungi support kami.</p>
    </div>
  <?php endif; ?>

  <?php $__currentLoopData = $grouped; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category => $methods): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="mb-4">
      <h2 class="fw-bold text-muted mb-2" style="font-size:11px;text-transform:uppercase;letter-spacing:.03em"><?php echo e($category); ?></h2>
      <div class="row g-3">
        <?php $__currentLoopData = $methods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $method): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <div class="col-sm-6 col-lg-4">
            <form method="POST" action="<?php echo e(route('client.invoices.pay-duitku', $invoice)); ?>">
              <?php echo csrf_field(); ?>
              <input type="hidden" name="payment_gateway_id" value="<?php echo e($gateway->id); ?>">
              <input type="hidden" name="method_code" value="<?php echo e($method['paymentMethod']); ?>">
              <button type="submit" class="card-public p-3 w-100 text-start d-flex align-items-center gap-3 border-0">
                <?php if(! empty($method['paymentImage'])): ?>
                  <img src="<?php echo e($method['paymentImage']); ?>" alt="<?php echo e($method['paymentName']); ?>" style="height:32px;width:auto;object-fit:contain" class="flex-shrink-0" loading="lazy">
                <?php endif; ?>
                <div class="min-w-0">
                  <p class="fw-medium text-dark text-truncate mb-0" style="font-size:14px"><?php echo e($method['paymentName']); ?></p>
                  <p class="text-muted mb-0" style="font-size:11px">
                    <?php echo e((float) ($method['totalFee'] ?? 0) > 0 ? 'Biaya Rp ' . number_format((float) $method['totalFee'], 0, ',', '.') : 'Tanpa biaya tambahan'); ?>

                  </p>
                </div>
              </button>
            </form>
          </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </div>
    </div>
  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('client.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/themes/client-themes/modern/client/invoices/duitku-methods.blade.php ENDPATH**/ ?>