<?php $__env->startSection('title', 'Pengaturan'); ?>
<?php $__env->startSection('content'); ?>

  <div class="mb-4">
    <h1 class="h4 fw-bold text-dark mb-1">Pengaturan</h1>
    <p class="small text-muted mb-0">Semua pengaturan sistem dikelompokkan di sini.</p>
  </div>

  <div class="row g-3">
    <?php $__currentLoopData = $cards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $card): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <div class="col-12 col-md-6 col-xl-4">
        <a href="<?php echo e(route($card['route'])); ?>" class="settings-card d-flex align-items-start gap-3 card border rounded-4 p-4 h-100 text-decoration-none">
          <span class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                style="width:44px;height:44px;background:rgba(79,70,229,.1);color:#4f46e5">
            <i class="fa-solid <?php echo e($card['icon']); ?>"></i>
          </span>
          <span class="min-w-0">
            <span class="d-block fw-bold text-dark mb-1" style="font-size:15px"><?php echo e($card['label']); ?></span>
            <span class="d-block text-muted" style="font-size:12px;line-height:1.6"><?php echo e($card['desc']); ?></span>
          </span>
        </a>
      </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </div>

  <style>
    .settings-card { transition: transform .12s ease, box-shadow .12s ease, border-color .12s ease; }
    .settings-card:hover {
      transform: translateY(-2px);
      border-color: #c7d2fe !important;
      box-shadow: 0 8px 20px rgba(79,70,229,.08);
    }
  </style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/admin/settings/index.blade.php ENDPATH**/ ?>