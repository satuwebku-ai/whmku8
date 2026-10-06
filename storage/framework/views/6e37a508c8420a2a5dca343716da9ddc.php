<?php
  $seoTitle       = $page->seo_title;
  $seoDescription = $page->seo_description;
  $seoKeywords    = $page->meta_keywords;
  $seoImage       = $page->og_image;
  $seoNoindex     = $page->noindex;
?>

<?php $__env->startSection('content'); ?>
  <article class="card-public p-4 p-md-5">
    <h1 class="fw-bold text-dark mb-2" style="font-size:1.6rem"><?php echo e($page->title); ?></h1>
    <p class="text-muted mb-4" style="font-size:11px">Diperbarui <?php echo e($page->updated_at->format('d M Y')); ?></p>

    <div class="prose-content">
      <?php echo $page->safe_content; ?>

    </div>
  </article>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('public.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/themes/public-themes/modern/public/page.blade.php ENDPATH**/ ?>