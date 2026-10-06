
<section class="py-5">
  <div class="container">
    <div class="card mb-4"><div class="card-body p-4 d-flex flex-wrap justify-content-between align-items-center gap-3">
      <div class="d-flex gap-3 align-items-center">
        <span class="tile t-indigo"><i class="bi bi-hdd-rack"></i></span>
        <div>
          <h2 class="h5 mb-1">Butuh kendali penuh? Coba VPS NVMe</h2>
          <p class="mb-0 text-body-secondary">Akses root dan aktif otomatis dalam hitungan menit.</p>
        </div>
      </div>
      <a href="<?php echo e(route('catalog.vps')); ?>" class="btn btn-primary">Lihat semua paket</a>
    </div></div>

    <div class="row g-4">
      <?php $__currentLoopData = $vpsProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="col-md-6 col-lg-4">
          <?php echo $__env->make('public.catalog._product-card', ['product' => $product], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
  </div>
</section>
<?php /**PATH /home/runner/workspace/hosting-billing/resources/views/themes/public-themes/namahost/public/home/_vps.blade.php ENDPATH**/ ?>