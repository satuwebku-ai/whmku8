<?php $__env->startSection('title', 'Lisensi Saya'); ?>

<?php $__env->startSection('content'); ?>
  <div class="d-flex align-items-center justify-content-between mb-4">
    <div><h1 class="h4 fw-bold mb-1">Lisensi Saya</h1><p class="text-muted small mb-0">Riwayat lisensi yang dipesan melalui katalog publik.</p></div>
    <a href="<?php echo e(route('license.index')); ?>" class="btn btn-primary btn-sm">Beli Lisensi</a>
  </div>
  <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="table-responsive">
      <table class="table align-middle mb-0">
        <thead><tr><th class="px-4">Lisensi</th><th>Siklus</th><th>Status Order</th><th class="text-end px-4">Nominal</th></tr></thead>
        <tbody>
          <?php $__empty_1 = true; $__currentLoopData = $licenses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $license): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
              <td class="px-4 fw-semibold"><?php echo e($license->product_name); ?></td>
              <td class="text-muted">Order <?php echo e($license->order_number); ?></td>
              <td><span class="badge text-bg-light"><?php echo e($license->status instanceof \BackedEnum ? $license->status->value : $license->status); ?></span></td>
              <td class="text-end px-4">Rp <?php echo e(number_format((float) $license->amount, 0, ',', '.')); ?></td>
            </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr><td colspan="4" class="text-center text-muted py-5">Belum ada order lisensi.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
    <?php if($licenses->hasPages()): ?> <div class="p-3 border-top"><?php echo e($licenses->links('pagination.bootstrap')); ?></div> <?php endif; ?>
  </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('client.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/themes/client-themes/default/client/licenses/index.blade.php ENDPATH**/ ?>