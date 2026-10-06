<?php $__env->startSection('title', 'Riwayat Transaksi — ' . $registrar->name); ?>

<?php $__env->startSection('content'); ?>

  <a href="<?php echo e(route('admin.registrars.index')); ?>" class="text-decoration-none text-muted" style="font-size:12px"><i class="fa-solid fa-arrow-left"></i> Kembali ke Registrar</a>
  <h1 class="h4 fw-bold text-dark mt-1 mb-4">Riwayat Transaksi — <?php echo e($registrar->name); ?></h1>

  <?php if($warning): ?>
    <div class="card border rounded-4 p-3 mb-4" style="border-color:#fde68a!important;background:#fffbeb">
      <p class="mb-0" style="font-size:14px;color:#92400e"><i class="fa-solid fa-triangle-exclamation"></i> <?php echo e($warning); ?></p>
    </div>
  <?php endif; ?>

  <div class="card border rounded-4 overflow-hidden">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead>
          <tr class="small text-uppercase text-muted" style="background:#f8fafc">
            <th class="px-4 py-3">Tanggal</th>
            <th class="py-3">Jenis</th>
            <th class="py-3">Keterangan</th>
            <th class="text-end px-4 py-3">Jumlah</th>
          </tr>
        </thead>
        <tbody>
          <?php $__empty_1 = true; $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tx): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
              <td class="px-4 py-3 text-muted" style="font-size:12px"><?php echo e($tx['date'] ?? '—'); ?></td>
              <td class="py-3"><span class="badge badge-soft-secondary"><?php echo e($tx['type']); ?></span></td>
              <td class="py-3 text-dark"><?php echo e($tx['description']); ?></td>
              <td class="text-end px-4 py-3 fw-medium <?php echo e($tx['amount'] < 0 ? 'text-danger' : 'text-success'); ?>">
                <?php echo e($tx['amount'] >= 0 ? '+' : ''); ?>Rp <?php echo e(number_format($tx['amount'], 0, ',', '.')); ?>

              </td>
            </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr><td colspan="4" class="text-center text-muted py-5">Tidak ada transaksi ditemukan.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <div class="px-4 py-3 border-top d-flex align-items-center justify-content-between">
      <p class="text-muted mb-0" style="font-size:11px">Halaman <?php echo e($page); ?></p>
      <div class="d-flex gap-2">
        <?php if($page > 1): ?>
          <a href="<?php echo e(route('admin.registrars.transactions', [$registrar, 'page' => $page - 1])); ?>" class="btn btn-outline-secondary btn-sm">Sebelumnya</a>
        <?php endif; ?>
        <?php if(count($transactions) === 25): ?>
          <a href="<?php echo e(route('admin.registrars.transactions', [$registrar, 'page' => $page + 1])); ?>" class="btn btn-outline-secondary btn-sm">Berikutnya</a>
        <?php endif; ?>
      </div>
    </div>
  </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/admin/registrars/transactions.blade.php ENDPATH**/ ?>