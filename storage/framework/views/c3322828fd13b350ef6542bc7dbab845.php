<?php
  $unit = [
    'monthly' => '/bulan', 'quarterly' => '/3 bulan',
    'semi_annually' => '/6 bulan', 'annually' => '/tahun',
  ];
  $cycles = $product->availableCycles();
  $firstCycleKey = array_key_first($cycles);
  $featuredCard = $product->is_featured && $product->isInStock();
  // Promo hanya ada bila controller mengirim $productPromos dan produk ini kena kupon.
  $promo = ($productPromos ?? [])[$product->id] ?? null;
?>

<a href="<?php echo e($product->category->productUrl($product)); ?>" class="plan <?php echo e($featuredCard ? 'featured' : ''); ?>">
  <?php if($featuredCard): ?>
    <span class="plan-tag badge text-bg-warning"><i class="bi bi-star-fill" style="font-size:9px"></i> Terlaris</span>
  <?php elseif(! $product->isInStock()): ?>
    <span class="plan-tag badge badge-soft-secondary">Stok Habis</span>
  <?php endif; ?>

  <?php if($product->isDepositBilled()): ?>
    <span class="badge badge-soft-warning align-self-start mb-2"><i class="bi bi-lightning-charge-fill"></i> Bayar per jam</span>
  <?php endif; ?>

  <h3 class="h5 mb-1" style="padding-right:5rem"><?php echo e($product->name); ?></h3>
  <?php if($product->tagline): ?>
    <p class="text-body-secondary small mb-2"><?php echo e($product->tagline); ?></p>
  <?php endif; ?>

  <div class="mt-2">
    <?php if($product->isDepositBilled()): ?>
      <?php $hourly = $product->estimatedHourlyRate(); ?>
      <?php if($hourly): ?>
        <div class="price">Rp <?php echo e(number_format($hourly, 2, ',', '.')); ?> <small>/ jam</small></div>
        <p class="small text-body-secondary mb-0 mt-1">± Rp <?php echo e(number_format($hourly * 730, 0, ',', '.')); ?> / bulan bila menyala terus · dipotong dari saldo</p>
      <?php else: ?>
        <div class="price" style="font-size:1.4rem">Sesuai Pemakaian</div>
        <p class="small text-body-secondary mb-0 mt-1">Dipotong otomatis dari saldo, per jam</p>
      <?php endif; ?>
    <?php elseif($promo): ?>
      <div class="mb-1">
        <span class="badge text-bg-danger">Diskon <?php echo e($promo['label']); ?></span>
        <s class="small text-body-secondary ms-1">Rp <?php echo e(number_format($promo['before'], 0, ',', '.')); ?></s>
      </div>
      <div class="price">Rp <?php echo e(number_format($promo['after'], 0, ',', '.')); ?> <small><?php echo e($unit[$firstCycleKey] ?? ''); ?></small></div>
    <?php elseif($product->starting_price !== null): ?>
      <div class="price">Rp <?php echo e(number_format($product->starting_price, 0, ',', '.')); ?> <small><?php echo e($unit[$firstCycleKey] ?? ''); ?></small></div>
    <?php else: ?>
      <p class="text-danger small mb-0">Harga belum tersedia</p>
    <?php endif; ?>
  </div>

  <?php if($product->features): ?>
    <ul>
      <?php $__currentLoopData = array_slice($product->features, 0, 5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <li><i class="bi bi-check2"></i><span><?php echo e($feature); ?></span></li>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ul>
  <?php else: ?>
    <div class="flex-grow-1" style="min-height:1rem"></div>
  <?php endif; ?>

  <span class="btn <?php echo e($featuredCard ? 'btn-primary' : ($product->isInStock() ? 'btn-outline-primary' : 'btn-outline-secondary')); ?> w-100" style="<?php echo e($product->isInStock() ? '' : 'pointer-events:none;opacity:.6'); ?>">
    <?php echo e($product->isInStock() ? 'Lihat detail' : 'Stok habis'); ?>

  </span>
</a>
<?php /**PATH /home/runner/workspace/hosting-billing/resources/views/themes/public-themes/namahost/public/catalog/_product-card.blade.php ENDPATH**/ ?>