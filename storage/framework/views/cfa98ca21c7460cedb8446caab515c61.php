<?php
  $unit = [
    'monthly' => '/bulan', 'quarterly' => '/3 bulan',
    'semi_annually' => '/6 bulan', 'annually' => '/tahun',
  ];
  $cycles = $product->availableCycles();
  $firstCycleKey = array_key_first($cycles);
  $featuredCard = $product->is_featured && $product->isInStock();
?>

<a href="<?php echo e($product->category->productUrl($product)); ?>" class="mx-pcard <?php echo e($featuredCard ? 'mx-pcard-featured' : ''); ?>">
  <?php if($featuredCard): ?>
    <span class="mx-tag"><i class="fa-solid fa-star" style="font-size:9px"></i> Unggulan</span>
  <?php elseif(! $product->isInStock()): ?>
    <span class="mx-tag mx-tag-muted">Stok Habis</span>
  <?php endif; ?>

  <?php if($product->isDepositBilled()): ?>
    <span class="mx-eyebrow mb-2" style="font-size:10.5px"><i class="fa-solid fa-bolt"></i> Bayar per jam</span>
  <?php endif; ?>

  <h3 style="font-size:1.15rem;font-weight:700;letter-spacing:-.02em;color:#18181b;margin:0 0 .35rem;padding-right:5rem"><?php echo e($product->name); ?></h3>
  <?php if($product->tagline): ?>
    <p style="font-size:13.5px;color:#71717a;margin:0;line-height:1.6"><?php echo e($product->tagline); ?></p>
  <?php endif; ?>

  <?php if($product->features): ?>
    <ul class="mx-feat">
      <?php $__currentLoopData = array_slice($product->features, 0, 4); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <li><i class="fa-solid fa-check"></i><span><?php echo e($feature); ?></span></li>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ul>
  <?php else: ?>
    <div style="flex-grow:1;min-height:1rem"></div>
  <?php endif; ?>

  <div style="border-top:1px solid #f0efe9;padding-top:1.25rem">
    <?php if($product->isDepositBilled()): ?>
      <?php $hourly = $product->estimatedHourlyRate(); ?>
      <?php if($hourly): ?>
        <div class="mx-price">Rp <?php echo e(number_format($hourly, 2, ',', '.')); ?> <small>/ jam</small></div>
        <p style="font-size:11.5px;color:#71717a;margin:.4rem 0 0">± Rp <?php echo e(number_format($hourly * 730, 0, ',', '.')); ?> / bulan bila menyala terus · dipotong dari saldo</p>
      <?php else: ?>
        <div class="mx-price" style="font-size:1.3rem">Sesuai Pemakaian</div>
        <p style="font-size:11.5px;color:#71717a;margin:.4rem 0 0">Dipotong otomatis dari saldo, per jam</p>
      <?php endif; ?>
    <?php elseif($product->starting_price !== null): ?>
      <div class="mx-price">Rp <?php echo e(number_format($product->starting_price, 0, ',', '.')); ?> <small><?php echo e($unit[$firstCycleKey] ?? ''); ?></small></div>
    <?php else: ?>
      <p class="text-danger mb-0" style="font-size:14px">Harga belum tersedia</p>
    <?php endif; ?>
    <span class="btn <?php echo e($product->isInStock() ? 'btn-theme' : 'btn-outline-secondary'); ?> w-100 mt-3" style="<?php echo e($product->isInStock() ? '' : 'pointer-events:none;opacity:.6'); ?>">
      <?php echo e($product->isInStock() ? 'Lihat Detail' : 'Stok Habis'); ?>

    </span>
  </div>
</a>
<?php /**PATH /home/runner/workspace/hosting-billing/resources/views/themes/public-themes/modern/public/catalog/_product-card.blade.php ENDPATH**/ ?>