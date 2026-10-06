<?php $__env->startSection('title', 'Minat Domain'); ?>

<?php $__env->startSection('content'); ?>
  <div class="mb-4">
    <h1 class="h4 fw-bold text-dark mb-1">Minat Domain</h1>
    <p class="small text-muted mb-0">
      Pencarian, domain yang masuk keranjang, dan checkout dari client yang sudah login.
      Data pengunjung anonim tidak disimpan.
    </p>
  </div>

  <?php
    $labels = [
      'search' => 'Pencarian',
      'cart' => 'Keranjang',
      'checkout' => 'Checkout',
    ];
    $badges = [
      'search' => 'badge-soft-secondary',
      'cart' => 'badge-soft-warning',
      'checkout' => 'badge-soft-success',
    ];
  ?>

  <div class="card border rounded-4 overflow-hidden">
    <div class="px-4 py-3 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
      <div class="d-flex gap-2">
        <a href="<?php echo e(route('admin.domain-interests.index')); ?>"
           class="btn btn-sm <?php echo e(! $type ? 'btn-primary' : 'btn-outline-secondary'); ?>">Semua</a>
        <?php $__currentLoopData = $labels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <a href="<?php echo e(route('admin.domain-interests.index', ['type' => $value])); ?>"
             class="btn btn-sm <?php echo e($type === $value ? 'btn-primary' : 'btn-outline-secondary'); ?>"><?php echo e($label); ?></a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </div>
      <span class="small text-muted"><?php echo e($interests->total()); ?> catatan</span>
    </div>

    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead>
          <tr class="small text-uppercase text-muted" style="background:#f8fafc">
            <th class="px-4 py-3">Domain</th>
            <th class="py-3">Client</th>
            <th class="py-3">Aktivitas</th>
            <th class="py-3">Sumber</th>
            <th class="text-end px-4 py-3">Waktu</th>
          </tr>
        </thead>
        <tbody>
          <?php $__empty_1 = true; $__currentLoopData = $interests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $interest): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
              <td class="px-4 py-3">
                <div class="fw-semibold text-dark"><?php echo e($interest->domain_name); ?></div>
                <div class="small text-muted">
                  <?php if($interest->years): ?> <?php echo e($interest->years); ?> tahun · <?php endif; ?>
                  <?php echo e($interest->tld?->extension ?? $interest->tldPremium?->extension ?? '—'); ?>

                </div>
              </td>
              <td class="py-3">
                <div class="small fw-medium text-dark"><?php echo e($interest->client?->name ?? 'Client dihapus'); ?></div>
                <div class="small text-muted"><?php echo e($interest->client?->email ?? '—'); ?></div>
              </td>
              <td class="py-3">
                <span class="badge <?php echo e($badges[$interest->event_type] ?? 'badge-soft-secondary'); ?>">
                  <?php echo e($labels[$interest->event_type] ?? ucfirst($interest->event_type)); ?>

                </span>
              </td>
              <td class="py-3 small text-muted"><?php echo e($interest->source ?: '—'); ?></td>
              <td class="text-end px-4 py-3 small text-muted">
                <?php echo e($interest->created_at?->diffForHumans()); ?>

                <span class="d-block" style="font-size:10px"><?php echo e($interest->created_at?->format('d M Y H:i')); ?></span>
              </td>
            </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr><td colspan="5" class="text-center text-muted py-5">Belum ada minat domain tercatat.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <?php if($interests->hasPages()): ?>
      <div class="px-4 py-3 border-top"><?php echo e($interests->links()); ?></div>
    <?php endif; ?>
  </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/admin/domain-interests/index.blade.php ENDPATH**/ ?>