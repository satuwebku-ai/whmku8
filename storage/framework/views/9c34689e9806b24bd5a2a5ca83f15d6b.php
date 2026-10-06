
<section class="mx-section mx-section-alt">
  <div class="mx-container">
    <div class="mx-head">
      <div><span class="mx-eyebrow">Kabar</span><h2>Kabar terbaru</h2></div>
      <a href="<?php echo e(route('announcements.index')); ?>" class="mx-link">Lihat semua</a>
    </div>
    <div class="mx-grid">
      <?php $__currentLoopData = $announcements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <a href="<?php echo e(route('announcements.show', $item->slug)); ?>" class="mx-tile" style="min-height:0">
          <span class="idx text-uppercase"><?php echo e($item->category); ?></span>
          <h3 style="font-size:1.05rem;line-height:1.45"><?php echo e($item->title); ?></h3>
          <span class="go" style="font-weight:500;color:#71717a"><?php echo e($item->published_at?->format('d M Y')); ?> <i class="fa-solid fa-arrow-right"></i></span>
        </a>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
  </div>
</section>
<?php /**PATH /home/runner/workspace/hosting-billing/resources/views/themes/public-themes/modern/public/home/_announcements.blade.php ENDPATH**/ ?>