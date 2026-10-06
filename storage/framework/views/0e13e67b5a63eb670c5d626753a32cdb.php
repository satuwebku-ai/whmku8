<?php $__env->startSection('title', 'Payment Gateway'); ?>

<?php $__env->startSection('content'); ?>

  
  <div class="d-flex align-items-center gap-1 mb-3 border-bottom flex-wrap">
    <?php
      $topTabs = [
        ['label' => 'Transaksi', 'route' => 'admin.payments'],
        ['label' => 'Gateway', 'route' => 'admin.gateways'],
      ];
    ?>
    <?php $__currentLoopData = $topTabs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tab): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <a href="<?php echo e(route($tab['route'])); ?>"
         class="px-3 py-2 small fw-medium text-decoration-none border-bottom border-2 <?php echo e(request()->routeIs(str_replace('.bootstrap-preview', '', $tab['route']) . '*') ? 'border-primary text-accent' : 'border-transparent text-muted'); ?>">
        <?php echo e($tab['label']); ?>

      </a>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </div>

  <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <div>
      <h1 class="h4 fw-bold text-dark mb-1">Payment Gateway</h1>
      <p class="small text-muted mb-0">Kelola metode pembayaran yang tersedia. Kredensial dienkripsi otomatis.</p>
    </div>
    <a href="<?php echo e(route('admin.gateway.add.page')); ?>" class="btn btn-primary">
      <i class="fa-solid fa-plus" style="font-size:12px"></i> Tambah Gateway
    </a>
  </div>

  <div class="card border rounded-4 overflow-hidden">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead>
          <tr class="small text-uppercase text-muted" style="background:#f8fafc">
            <th class="px-4 py-3">Nama</th>
            <th class="py-3">Driver</th>
            <th class="py-3">Mode</th>
            <th class="py-3">Biaya</th>
            <th class="text-center py-3">Transaksi</th>
            <th class="py-3">Status</th>
            <th class="text-end px-4 py-3">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php $__empty_1 = true; $__currentLoopData = $gateways; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gw): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
              <td class="px-4 py-3 fw-medium text-dark"><?php echo e($gw->name); ?></td>
              <td class="text-muted py-3"><?php echo e($gw->driver_label); ?></td>
              <td class="py-3">
                <?php if($gw->isManual()): ?>
                  <span class="text-muted" style="font-size:12px">—</span>
                <?php else: ?>
                  <span class="badge <?php echo e($gw->isSandbox() ? 'badge-soft-warning' : 'badge-soft-success'); ?>"><?php echo e($gw->isSandbox() ? 'Sandbox' : 'Production'); ?></span>
                <?php endif; ?>
              </td>
              <td class="text-muted py-3" style="font-size:12px">
                <?php if($gw->fee_flat > 0 || $gw->fee_percent > 0): ?>
                  Rp <?php echo e(number_format($gw->fee_flat, 0, ',', '.')); ?> + <?php echo e(rtrim(rtrim(number_format($gw->fee_percent, 2), '0'), '.')); ?>%
                <?php else: ?>
                  Gratis
                <?php endif; ?>
              </td>
              <td class="text-center text-muted py-3"><?php echo e($gw->payments_count); ?></td>
              <td class="py-3">
                <span class="badge <?php echo e($gw->is_active ? 'badge-soft-success' : 'badge-soft-secondary'); ?>"><?php echo e($gw->is_active ? 'Aktif' : 'Nonaktif'); ?></span>
              </td>
              <td class="text-end px-4 py-3">
                <div class="d-flex align-items-center justify-content-end gap-2">
                  <form method="POST" action="<?php echo e(route('admin.gateway.status')); ?>">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="gateway_id" value="<?php echo e($gw->id); ?>">
                    <button type="submit" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center justify-content-center" style="width:32px;height:32px;padding:0" title="<?php echo e($gw->is_active ? 'Nonaktifkan' : 'Aktifkan'); ?>">
                      <i class="fa-solid <?php echo e($gw->is_active ? 'fa-toggle-on' : 'fa-toggle-off'); ?>" style="font-size:12px"></i>
                    </button>
                  </form>
                  <a href="<?php echo e(route('admin.gateway.edit.page', $gw)); ?>" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center justify-content-center" style="width:32px;height:32px;padding:0" title="Edit">
                    <i class="fa-regular fa-pen-to-square" style="font-size:12px"></i>
                  </a>
                  <form method="POST" action="<?php echo e(route('admin.gateway.delete', $gw)); ?>" data-confirm="Hapus gateway ini?" data-confirm-title="Hapus Data" data-confirm-style="danger" data-confirm-label="Ya, Hapus">
                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-outline-danger btn-sm d-inline-flex align-items-center justify-content-center" style="width:32px;height:32px;padding:0" title="Hapus">
                      <i class="fa-regular fa-trash-can" style="font-size:12px"></i>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr><td colspan="7" class="text-center text-muted py-5">Belum ada payment gateway. Tambahkan minimal satu supaya bisa menerima pembayaran.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <?php if($gateways->hasPages()): ?>
      <div class="px-4 py-3 border-top"><?php echo e($gateways->links('pagination.bootstrap')); ?></div>
    <?php endif; ?>
  </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/admin/gateways/index.blade.php ENDPATH**/ ?>