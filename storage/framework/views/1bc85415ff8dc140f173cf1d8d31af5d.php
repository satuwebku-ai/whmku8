
<section class="mx-section mx-section-alt">
  <div class="mx-container">
    <div class="mx-head">
      <div>
        <span class="mx-eyebrow">Hosting</span>
        <h2>Paket hosting pilihan</h2>
        <p>Mulai kecil, naik kelas kapan saja tanpa pindah server.</p>
      </div>
      <a href="<?php echo e(route('catalog.index')); ?>" class="mx-link">Lihat semua paket</a>
    </div>

    <?php if($featured->isEmpty()): ?>
      <div class="card-public" style="padding:3rem;text-align:center">
        <p class="mb-1 text-muted">Katalog sedang disiapkan.</p>
        <p class="small text-muted mb-0">Belum ada produk yang bisa ditampilkan — tambahkan lewat menu Produk di admin panel.</p>
      </div>
    <?php else: ?>
      <div class="mx-grid">
        <?php $__currentLoopData = $featured; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <?php echo $__env->make('public.catalog._product-card', ['product' => $product], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </div>
    <?php endif; ?>
  </div>
</section>
<?php /**PATH /home/runner/workspace/hosting-billing/resources/views/themes/public-themes/modern/public/home/_hosting.blade.php ENDPATH**/ ?>