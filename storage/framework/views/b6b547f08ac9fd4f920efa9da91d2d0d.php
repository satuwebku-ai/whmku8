<?php $__env->startSection('title', 'Lisensi Saya'); ?>

<?php $__env->startSection('content'); ?>
  <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <div><h1 class="h4 fw-bold mb-1">Lisensi Saya</h1><p class="text-muted small mb-0">Status order dan tagihan lisensi Anda.</p></div>
    <a href="<?php echo e(route('license.index')); ?>" class="btn btn-theme btn-sm"><i class="fa-solid fa-plus me-1"></i> Beli Lisensi</a>
  </div>
  <div class="row g-3">
    <?php $__empty_1 = true; $__currentLoopData = $licenses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $license): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
      <div class="col-12 col-md-6">
        <div class="card-public p-4 h-100">
          <div class="d-flex align-items-start gap-3">
            <span class="tile t-indigo"><i class="fa-solid fa-key"></i></span>
            <div class="min-w-0 flex-grow-1">
              <h2 class="h6 fw-bold text-dark mb-1"><?php echo e($license->product_name); ?></h2>
              <p class="text-muted mb-2" style="font-size:11px">Order <?php echo e($license->order_number); ?></p>
              <span class="badge badge-soft-success"><?php echo e($license->status instanceof \BackedEnum ? $license->status->value : $license->status); ?></span>
            </div>
            <strong class="text-dark">Rp <?php echo e(number_format((float) $license->amount, 0, ',', '.')); ?></strong>
          </div>
        </div>
      </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
      <div class="col-12"><div class="card-public p-5 text-center text-muted">Belum ada order lisensi.</div></div>
    <?php endif; ?>
  </div>
  <?php if($licenses->hasPages()): ?> <div class="mt-4"><?php echo e($licenses->links('pagination.bootstrap')); ?></div> <?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('client.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/themes/client-themes/namahost/client/licenses/index.blade.php ENDPATH**/ ?>