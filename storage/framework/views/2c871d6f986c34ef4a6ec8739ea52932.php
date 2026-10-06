
<section class="mx-section">
  <div class="mx-container">
    <div class="mx-head">
      <div>
        <span class="mx-eyebrow">Layanan</span>
        <h2>Semua yang kamu butuhkan untuk online</h2>
      </div>
      <a href="<?php echo e(route('catalog.index')); ?>" class="mx-link">Lihat katalog</a>
    </div>
    <div class="mx-grid">
      <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <a href="<?php echo e($category->publicUrl()); ?>" class="mx-tile">
          <span class="idx"><?php echo e(str_pad($loop->iteration, 2, '0', STR_PAD_LEFT)); ?></span>
          <h3><?php echo e($category->name); ?></h3>
          <?php if($category->description): ?>
            <p><?php echo e(Str::limit($category->description, 90)); ?></p>
          <?php endif; ?>
          <span class="go"><?php echo e($category->products_count); ?> paket <i class="fa-solid fa-arrow-right"></i></span>
        </a>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
  </div>
</section>
<?php /**PATH /home/runner/workspace/hosting-billing/resources/views/themes/public-themes/modern/public/home/_categories.blade.php ENDPATH**/ ?>