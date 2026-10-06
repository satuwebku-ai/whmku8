
<section class="mx-section">
  <div class="mx-container">
    <div class="mx-head">
      <div>
        <span class="mx-eyebrow">VPS & Cloud</span>
        <h2>Kontrol penuh dengan akses root</h2>
        <p>Aktif otomatis dalam hitungan menit.</p>
      </div>
      <a href="<?php echo e(route('catalog.index')); ?>" class="mx-link">Lihat semua paket</a>
    </div>
    <div class="mx-grid">
      <?php $__currentLoopData = $vpsProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php echo $__env->make('public.catalog._product-card', ['product' => $product], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
  </div>
</section>
<?php /**PATH /home/runner/workspace/hosting-billing/resources/views/themes/public-themes/modern/public/home/_vps.blade.php ENDPATH**/ ?>