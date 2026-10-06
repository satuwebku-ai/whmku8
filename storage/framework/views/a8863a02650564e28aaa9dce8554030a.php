<?php $__env->startSection('title', 'Domain'); ?>

<?php $__env->startSection('content'); ?>

  <?php echo $__env->make('admin.domains._nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

  <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
    <div>
      <h1 class="h4 fw-bold text-dark mb-1">Domain Aktif</h1>
      <p class="small text-muted mb-0">Domain milik klien.</p>
    </div>
    <div class="d-flex align-items-center gap-2">
      <a href="<?php echo e(route('admin.domain.search')); ?>" class="btn btn-outline-secondary btn-sm">
        <i class="fa-solid fa-magnifying-glass" style="font-size:11px"></i> Cek Domain
      </a>
      <a href="<?php echo e(route('admin.domain.add.page')); ?>" class="btn btn-primary btn-sm">
        <i class="fa-solid fa-plus" style="font-size:11px"></i> Tambah Domain
      </a>
    </div>
  </div>

  
  <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
    <?php
      $statusTabs = [
        ['label' => 'Semua', 'route' => 'admin.domains', 'status' => null],
        ['label' => 'Pending', 'route' => 'admin.domains.pending', 'status' => 'pending'],
        ['label' => 'Aktif', 'route' => 'admin.domains.active', 'status' => 'active'],
        ['label' => 'Expired', 'route' => 'admin.domains.expired', 'status' => 'expired'],
        ['label' => 'Cancelled', 'route' => 'admin.domains.cancelled', 'status' => 'cancelled'],
      ];
    ?>
    <?php $__currentLoopData = $statusTabs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tab): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <a href="<?php echo e(route($tab['route'])); ?>"
         class="px-3 py-1 small fw-medium text-decoration-none rounded-pill <?php echo e($activeStatus === $tab['status'] ? 'bg-primary text-white' : 'bg-light text-muted'); ?>">
        <?php echo e($tab['label']); ?>

      </a>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </div>

  <div class="card border rounded-4 overflow-hidden">
    <form method="GET" class="px-4 py-3 border-bottom d-flex flex-wrap align-items-center gap-2">
      <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari domain..." class="form-control form-control-sm" style="max-width:20rem;flex:1 1 200px">
      <button type="submit" class="btn btn-outline-secondary btn-sm" style="width:fit-content">Cari</button>
      <?php if(request('search')): ?>
        <a href="<?php echo e(url()->current()); ?>" class="btn btn-outline-secondary btn-sm" style="width:fit-content">Reset</a>
      <?php endif; ?>
    </form>

    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead>
          <tr class="small text-uppercase text-muted" style="background:#f8fafc">
            <th class="px-4 py-3">Domain</th>
            <th class="py-3">Klien</th>
            <th class="py-3">Registrar</th>
            <th class="py-3">Jatuh Tempo</th>
            <th class="py-3">Status</th>
            <th class="text-end px-4 py-3">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php
            $statusBadge = fn ($s) => match ($s) {
                'active' => 'badge-soft-success',
                'pending' => 'badge-soft-warning',
                'expired', 'suspended' => 'badge-soft-danger',
                default => 'badge-soft-secondary',
            };
          ?>
          <?php $__empty_1 = true; $__currentLoopData = $domains; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $domain): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
              <td class="px-4 py-3 fw-medium text-dark">
                <a href="<?php echo e(route('admin.domains.details', $domain)); ?>" class="text-decoration-none text-dark"><?php echo e($domain->domain_name); ?></a>
              </td>
              <td class="text-muted py-3"><?php echo e($domain->client->name ?? '—'); ?></td>
              <td class="text-muted py-3"><?php echo e($domain->registrar->name ?? 'Manual'); ?></td>
              <td class="text-muted py-3">
                <?php echo e($domain->expiry_date?->format('d M Y') ?? '—'); ?>

                <?php if($domain->is_expiring_soon): ?>
                  <span class="badge badge-soft-warning ms-1">Segera Habis</span>
                <?php endif; ?>
              </td>
              <td class="py-3">
                <span class="badge <?php echo e($statusBadge($domain->status === 'expired' ? 'suspended' : $domain->status)); ?>">
                  <?php echo e(ucfirst($domain->status)); ?>

                </span>
              </td>
              <td class="text-end px-4 py-3">
                <div class="d-flex align-items-center justify-content-end gap-2">
                  <a href="<?php echo e(route('admin.domains.details', $domain)); ?>" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center justify-content-center" style="width:32px;height:32px;padding:0" title="Detail">
                    <i class="fa-regular fa-eye" style="font-size:12px"></i>
                  </a>
                  <a href="<?php echo e(route('admin.domain.edit.page', $domain)); ?>" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center justify-content-center" style="width:32px;height:32px;padding:0" title="Edit">
                    <i class="fa-regular fa-pen-to-square" style="font-size:12px"></i>
                  </a>
                  <form method="POST" action="<?php echo e(route('admin.domain.delete', $domain)); ?>" data-confirm="Hapus data domain ini?" data-confirm-title="Hapus Data" data-confirm-style="danger" data-confirm-label="Ya, Hapus">
                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-outline-danger btn-sm d-inline-flex align-items-center justify-content-center" style="width:32px;height:32px;padding:0" title="Hapus">
                      <i class="fa-regular fa-trash-can" style="font-size:12px"></i>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr><td colspan="6" class="text-center text-muted py-5">Tidak ada domain di kategori ini.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <?php if($domains->hasPages()): ?>
      <div class="px-4 py-3 border-top"><?php echo e($domains->links('pagination.bootstrap')); ?></div>
    <?php endif; ?>
  </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/admin/domains/index.blade.php ENDPATH**/ ?>