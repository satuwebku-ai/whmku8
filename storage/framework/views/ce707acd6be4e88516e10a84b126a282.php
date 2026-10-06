<?php $__env->startSection('title', 'Domain Saya'); ?>

<?php $__env->startSection('content'); ?>
  <?php
    $badgeMap = ['active' => 'badge-soft-success', 'pending' => 'badge-soft-warning', 'expired' => 'badge-soft-danger'];
  ?>

  <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
    <div>
      <h1 class="h4 fw-bold text-dark mb-1">Domain Saya</h1>
      <p class="text-muted mb-0">Daftar domain yang Anda daftarkan.</p>
    </div>
    <form method="GET">
      <select name="status" class="form-select form-select-sm" data-auto-submit>
        <option value="">Semua Status</option>
        <option value="active" <?php if(request('status') === 'active'): echo 'selected'; endif; ?>>Aktif</option>
        <option value="pending" <?php if(request('status') === 'pending'): echo 'selected'; endif; ?>>Pending</option>
        <option value="expired" <?php if(request('status') === 'expired'): echo 'selected'; endif; ?>>Expired</option>
      </select>
    </form>
  </div>

  <div class="d-flex flex-column gap-3">
    <?php $__empty_1 = true; $__currentLoopData = $domains; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $domain): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
      <a href="<?php echo e(route('client.domains.show', $domain)); ?>" class="dash-card dash-card-hover p-4 d-flex align-items-center justify-content-between gap-3 text-decoration-none">
        <div class="d-flex align-items-center gap-3 min-w-0">
          <span class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width:40px;height:40px;background:rgba(6,182,212,.1);color:#0891b2">
            <i class="fa-solid fa-globe" style="font-size:15px"></i>
          </span>
          <div class="min-w-0">
            <p class="fw-semibold text-dark text-truncate mb-0"><?php echo e($domain->domain_name); ?></p>
            <p class="text-muted mt-1 mb-0" style="font-size:11px">
              <?php if($domain->expiry_date): ?>
                Berlaku sampai <?php echo e($domain->expiry_date->format('d M Y')); ?>

              <?php else: ?>
                Tanggal kedaluwarsa belum tercatat
              <?php endif; ?>
            </p>
          </div>
        </div>
        <div class="text-end flex-shrink-0">
          <span class="badge <?php echo e($badgeMap[$domain->status === 'expired' ? 'expired' : $domain->status] ?? 'badge-soft-secondary'); ?>"><?php echo e(ucfirst($domain->status)); ?></span>
          <?php if($domain->is_expiring_soon): ?>
            <p class="fw-medium mt-1 mb-0" style="font-size:11px;color:#b45309">Segera perpanjang</p>
          <?php endif; ?>
        </div>
      </a>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
      <div class="dash-card p-5 text-center">
        <span class="rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width:44px;height:44px;background:#f1f5f9;color:#94a3b8">
          <i class="fa-solid fa-globe"></i>
        </span>
        <p class="text-muted mb-3" style="font-size:14px">Anda belum punya domain.</p>
        <a href="<?php echo e(route('domain.search')); ?>" class="btn btn-theme"><i class="fa-solid fa-magnifying-glass" style="font-size:11px"></i> Cek Domain</a>
      </div>
    <?php endif; ?>
  </div>

  <?php if($domains->hasPages()): ?>
    <div class="mt-4"><?php echo e($domains->links('pagination.bootstrap')); ?></div>
  <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('client.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/themes/client-themes/default/client/domains/index.blade.php ENDPATH**/ ?>