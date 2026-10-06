<?php
  $seoTitle = 'Paket VPS';
  $seoDescription = 'VPS NVMe dengan akses root penuh dan aktivasi otomatis dalam hitungan menit.';
?>

<?php $__env->startSection('content'); ?>

  <?php echo $__env->make('public._promo-banner-carousel', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

  <div class="text-center mb-5 mx-auto" style="max-width:40rem">
    <h1 class="fw-bold text-dark mb-3" style="font-size:1.9rem">Paket VPS NVMe</h1>
    <p class="text-muted mb-0">Kendali penuh lewat akses root, aktif otomatis dalam hitungan menit. Naik kelas kapan saja saat kebutuhan bertambah.</p>
    <div class="mt-4 d-flex justify-content-center gap-2 flex-wrap">
      <a href="<?php echo e(route('catalog.index')); ?>" class="btn btn-outline-secondary">Lihat Paket Hosting</a>
      <a href="<?php echo e(route('domain.search')); ?>" class="btn btn-outline-secondary">
        <i class="fa-solid fa-magnifying-glass" style="font-size:12px"></i> Cek Ketersediaan Domain
      </a>
    </div>
  </div>

  <?php if($products->isEmpty()): ?>
    <div class="card-public p-5 text-center text-muted" style="font-size:14px">Paket VPS sedang disiapkan. Silakan cek kembali nanti.</div>
  <?php else: ?>
    <div class="mb-5">
      <div class="row g-3 justify-content-center">
        <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <div class="col-sm-6 col-lg-4">
            <?php echo $__env->make('public.catalog._product-card', ['product' => $product], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
          </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </div>
    </div>
  <?php endif; ?>

  <?php if($categories->count() > 1): ?>
    <div>
      <h2 class="fw-bold text-dark mb-3" style="font-size:1.15rem">Kategori VPS</h2>
      <div class="row g-3">
        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <div class="col-sm-6 col-lg-4">
            <a href="<?php echo e($category->publicUrl()); ?>" class="card-public p-4 text-decoration-none d-block h-100">
              <h3 class="fw-semibold text-dark mb-1" style="font-size:15px"><?php echo e($category->name); ?></h3>
              <?php if($category->description): ?>
                <p class="text-muted mb-2" style="font-size:14px"><?php echo e($category->description); ?></p>
              <?php endif; ?>
              <p class="text-muted mb-0" style="font-size:12px"><?php echo e($category->products_count); ?> paket tersedia</p>
            </a>
          </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </div>
    </div>
  <?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('public.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/themes/public-themes/namahost/public/catalog/vps.blade.php ENDPATH**/ ?>