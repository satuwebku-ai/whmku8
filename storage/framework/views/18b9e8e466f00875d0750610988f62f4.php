<?php
  $seoTitle       = $announcement->seo_title;
  $seoDescription = $announcement->seo_description;
?>

<?php $__env->startSection('content'); ?>
  <a href="<?php echo e(route('announcements.index')); ?>" class="text-muted text-decoration-none" style="font-size:12px">&larr; Kembali ke Pengumuman</a>

  <article class="card-public p-4 p-md-5 mt-3">
    <div class="d-flex align-items-center gap-2 mb-3">
      <span class="badge rounded-pill text-capitalize" style="font-size:11px;background:#f1f5f9;color:#475569"><?php echo e($announcement->category); ?></span>
      <span class="text-muted" style="font-size:12px"><?php echo e($announcement->published_at?->format('d M Y H:i')); ?></span>
    </div>

    <h1 class="fw-bold text-dark mb-4" style="font-size:1.6rem"><?php echo e($announcement->title); ?></h1>

    <div class="prose-content">
      <?php echo $announcement->safe_content; ?>

    </div>
  </article>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('public.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/themes/public-themes/default/public/announcement.blade.php ENDPATH**/ ?>