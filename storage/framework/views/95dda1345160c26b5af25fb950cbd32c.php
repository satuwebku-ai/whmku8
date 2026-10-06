
<section id="paket" class="py-5 bg-body-tertiary">
  <div class="container">
    <div class="text-center mb-5">
      <h2>Pilih paket hosting</h2>
      <p class="text-body-secondary mb-0">Mulai kecil, naik kelas kapan saja tanpa pindah server.</p>
    </div>

    <?php if($featured->isEmpty()): ?>
      <div class="card-public p-5 text-center">
        <p class="mb-1 text-body-secondary">Katalog sedang disiapkan.</p>
        <p class="small text-body-secondary mb-0">Belum ada produk yang bisa ditampilkan — tambahkan lewat menu Produk di admin panel.</p>
      </div>
    <?php else: ?>
      <div class="row g-4 justify-content-center">
        <?php $__currentLoopData = $featured; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <div class="col-md-6 col-lg-4">
            <?php echo $__env->make('public.catalog._product-card', ['product' => $product], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
          </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </div>
      <div class="text-center mt-5">
        <a href="<?php echo e(route('catalog.index')); ?>" class="btn btn-outline-primary">Lihat semua paket <i class="bi bi-arrow-right"></i></a>
      </div>
    <?php endif; ?>
  </div>
</section>
<?php /**PATH /home/runner/workspace/hosting-billing/resources/views/themes/public-themes/namahost/public/home/_hosting.blade.php ENDPATH**/ ?>