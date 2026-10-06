<?php $__env->startSection('title', 'Layanan Saya'); ?>

<?php $__env->startSection('content'); ?>
  <?php
    $badgeMap = [
      'active' => 'badge-soft-success', 'pending' => 'badge-soft-warning',
      'suspended' => 'badge-soft-danger', 'terminated' => 'badge-soft-secondary',
    ];
  ?>

  <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
    <div>
      <h1 class="h4 fw-bold text-dark mb-1">Layanan Saya</h1>
      <p class="text-muted mb-0">Daftar akun hosting Anda.</p>
    </div>
    <form method="GET">
      <select name="status" class="form-select form-select-sm" data-auto-submit>
        <option value="">Semua Status</option>
        <option value="active" <?php if(request('status') === 'active'): echo 'selected'; endif; ?>>Aktif</option>
        <option value="pending" <?php if(request('status') === 'pending'): echo 'selected'; endif; ?>>Pending</option>
        <option value="suspended" <?php if(request('status') === 'suspended'): echo 'selected'; endif; ?>>Suspended</option>
        <option value="terminated" <?php if(request('status') === 'terminated'): echo 'selected'; endif; ?>>Terminated</option>
      </select>
    </form>
  </div>

  <div class="d-flex flex-column gap-3">
    <?php $__empty_1 = true; $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
      <a href="<?php echo e(route('client.services.show', $service)); ?>" class="dash-card dash-card-hover p-4 d-flex align-items-center justify-content-between gap-3 text-decoration-none">
        <div class="d-flex align-items-center gap-3 min-w-0">
          <span class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width:40px;height:40px;background:rgba(79,70,229,.1);color:var(--lumora-theme)">
            <i class="fa-solid fa-server" style="font-size:15px"></i>
          </span>
          <div class="min-w-0">
            <p class="fw-semibold text-dark text-truncate mb-0"><?php echo e($service->domain); ?></p>
            <p class="text-muted mb-0" style="font-size:14px"><?php echo e($service->package); ?></p>
            <?php if($service->next_due_date): ?>
              <p class="text-muted mt-1 mb-0" style="font-size:11px">Jatuh tempo berikutnya: <?php echo e($service->next_due_date->format('d M Y')); ?></p>
            <?php endif; ?>
          </div>
        </div>
        <div class="text-end flex-shrink-0">
          <span class="badge <?php echo e($badgeMap[$service->status] ?? 'badge-soft-secondary'); ?>"><?php echo e(ucfirst($service->status)); ?></span>
          <p class="fw-semibold text-dark mt-2 mb-0" style="font-size:14px">Rp <?php echo e(number_format($service->price, 0, ',', '.')); ?></p>
          <p class="text-muted mb-0" style="font-size:11px"><?php echo e(str_replace('_', ' ', $service->billing_cycle)); ?></p>
        </div>
      </a>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
      <div class="dash-card p-5 text-center">
        <span class="rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width:44px;height:44px;background:#f1f5f9;color:#94a3b8">
          <i class="fa-solid fa-server"></i>
        </span>
        <p class="text-muted mb-3" style="font-size:14px">Anda belum punya layanan hosting.</p>
        <a href="<?php echo e(route('catalog.index')); ?>" class="btn btn-theme"><i class="fa-solid fa-cart-plus" style="font-size:11px"></i> Pesan Layanan</a>
      </div>
    <?php endif; ?>
  </div>

  <?php if($services->hasPages()): ?>
    <div class="mt-4"><?php echo e($services->links('pagination.bootstrap')); ?></div>
  <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('client.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/themes/client-themes/modern/client/services/index.blade.php ENDPATH**/ ?>