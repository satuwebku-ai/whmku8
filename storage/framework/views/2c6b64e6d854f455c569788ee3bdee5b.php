<?php
  $seoTitle       = 'Pengumuman';
  $seoDescription = 'Informasi terbaru, jadwal maintenance, dan promo layanan kami.';
?>

<?php $__env->startSection('content'); ?>
  <h1 class="fw-bold text-dark mb-4" style="font-size:1.6rem">Pengumuman</h1>

  <div class="d-flex flex-column gap-3">
    <?php $__empty_1 = true; $__currentLoopData = $announcements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
      <a href="<?php echo e(route('announcements.show', $item->slug)); ?>" class="card-public p-4 text-decoration-none">
        <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
          <?php if($item->is_pinned): ?>
            <span class="badge rounded-pill" style="font-size:11px;background:rgba(79,70,229,.12);color:#4338ca">Disematkan</span>
          <?php endif; ?>
          <span class="badge rounded-pill text-capitalize" style="font-size:11px;background:#f1f5f9;color:#475569"><?php echo e($item->category); ?></span>
          <span class="text-muted" style="font-size:12px"><?php echo e($item->published_at?->format('d M Y')); ?></span>
        </div>
        <h2 class="fw-semibold text-dark mb-1" style="font-size:16px"><?php echo e($item->title); ?></h2>
        <?php if($item->excerpt): ?>
          <p class="text-muted mb-0" style="font-size:14px"><?php echo e($item->excerpt); ?></p>
        <?php endif; ?>
      </a>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
      <div class="card-public p-5 text-center text-muted">Belum ada pengumuman.</div>
    <?php endif; ?>
  </div>

  <?php if($announcements->hasPages()): ?>
    <div class="mt-4"><?php echo e($announcements->links('pagination.bootstrap')); ?></div>
  <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('public.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/themes/public-themes/default/public/announcements.blade.php ENDPATH**/ ?>