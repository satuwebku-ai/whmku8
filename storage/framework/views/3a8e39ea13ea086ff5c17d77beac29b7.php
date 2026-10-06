<?php
  $seoTitle = $category->name;
  $seoDescription = $category->description ?: "Pilihan paket {$category->name} — aktif cepat, dukungan 24/7.";
?>

<?php $__env->startSection('content'); ?>

  <nav class="text-muted mb-3" style="font-size:12px">
    <?php if($category->urlSection() === 'vps'): ?>
      <a href="<?php echo e(route('catalog.vps')); ?>" class="text-decoration-none text-muted">VPS</a> / <?php echo e($category->name); ?>

    <?php else: ?>
      <a href="<?php echo e(route('catalog.index')); ?>" class="text-decoration-none text-muted">Hosting</a> / <?php echo e($category->name); ?>

    <?php endif; ?>
  </nav>

  <div class="mb-4">
    <h1 class="fw-bold text-dark mb-0" style="font-size:1.6rem"><?php echo e($category->name); ?></h1>
    <?php if($category->description): ?>
      <p class="text-muted mt-1 mb-0"><?php echo e($category->description); ?></p>
    <?php endif; ?>
  </div>

  <?php if($products->isEmpty()): ?>
    <div class="card-public p-5 text-center text-muted" style="font-size:14px">Belum ada produk di kategori ini.</div>
  <?php else: ?>
    <div class="row g-3">
      <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="col-sm-6 col-lg-4">
          <?php echo $__env->make('public.catalog._product-card', ['product' => $product], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <?php if($products->hasPages()): ?>
      <div class="mt-4"><?php echo e($products->links('pagination.bootstrap')); ?></div>
    <?php endif; ?>
  <?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('public.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/themes/public-themes/namahost/public/catalog/category.blade.php ENDPATH**/ ?>