<?php
  $unit = [
    'monthly' => '/bulan', 'quarterly' => '/3 bulan',
    'semi_annually' => '/6 bulan', 'annually' => '/tahun',
  ];
  $cycles = $product->availableCycles();
  $firstCycleKey = array_key_first($cycles);
?>

<a href="<?php echo e($product->category->productUrl($product)); ?>"
   class="card-public p-4 d-flex flex-column text-decoration-none position-relative h-100 prod-card <?php echo e($product->is_featured && $product->isInStock() ? 'prod-card-featured' : ''); ?>">
  <?php if($product->is_featured && $product->isInStock()): ?>
    <span class="badge-public-active position-absolute" style="top:1rem;right:1rem"><i class="fa-solid fa-star" style="font-size:9px"></i> Unggulan</span>
  <?php elseif(! $product->isInStock()): ?>
    <span class="badge-public-inactive position-absolute" style="top:1rem;right:1rem">Stok Habis</span>
  <?php endif; ?>

  <?php if($product->isDepositBilled()): ?>
    <span class="badge rounded-pill mb-2 align-self-start" style="font-size:10.5px;background:#eef2ff;color:#4338ca;padding:.3rem .65rem">
      <i class="fa-solid fa-bolt" style="font-size:9px"></i> Bayar per Jam
    </span>
  <?php endif; ?>

  <h3 class="fw-semibold text-dark mb-2" style="font-size:16px"><?php echo e($product->name); ?></h3>
  <?php if($product->tagline): ?>
    <p class="text-muted mb-3" style="font-size:13px;line-height:1.6"><?php echo e($product->tagline); ?></p>
  <?php endif; ?>

  <?php if($product->features): ?>
    <ul class="mb-3 flex-grow-1 ps-0" style="list-style:none">
      <?php $__currentLoopData = array_slice($product->features, 0, 4); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <li class="d-flex align-items-start gap-2 text-muted mb-2" style="font-size:12.5px;line-height:1.7">
          <i class="fa-solid fa-check text-success flex-shrink-0" style="width:14px;margin-top:3px;text-align:center"></i>
          <span class="min-w-0"><?php echo e($feature); ?></span>
        </li>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ul>
  <?php else: ?>
    <div class="flex-grow-1"></div>
  <?php endif; ?>

  <div class="pt-3 border-top">
    <?php if($product->isDepositBilled()): ?>
      <?php $hourly = $product->estimatedHourlyRate(); ?>
      <?php if($hourly): ?>
        <p class="fw-bold text-dark mb-0" style="font-size:1.5rem;letter-spacing:-.01em">
          Rp <?php echo e(number_format($hourly, 2, ',', '.')); ?>

          <span class="text-muted fw-normal" style="font-size:12px">/ jam</span>
        </p>
        <p class="text-muted mb-0" style="font-size:11.5px">± Rp <?php echo e(number_format($hourly * 730, 0, ',', '.')); ?> / bulan bila menyala terus · dipotong dari saldo</p>
      <?php else: ?>
        <p class="fw-bold text-dark mb-0" style="font-size:1.1rem;letter-spacing:-.01em">Sesuai Pemakaian</p>
        <p class="text-muted mb-0" style="font-size:11.5px">Dipotong otomatis dari saldo, per jam</p>
      <?php endif; ?>
    <?php elseif($product->starting_price !== null): ?>
      <p class="fw-bold text-dark mb-0" style="font-size:1.5rem;letter-spacing:-.01em">
        Rp <?php echo e(number_format($product->starting_price, 0, ',', '.')); ?>

        <span class="text-muted fw-normal" style="font-size:12px"><?php echo e($unit[$firstCycleKey] ?? ''); ?></span>
      </p>
    <?php else: ?>
      <p class="text-danger mb-0" style="font-size:14px">Harga belum tersedia</p>
    <?php endif; ?>
    <span class="btn <?php echo e($product->isInStock() ? 'btn-theme' : 'btn-outline-secondary'); ?> w-100 mt-3" style="<?php echo e($product->isInStock() ? '' : 'pointer-events:none;opacity:.6'); ?>">
      <?php echo e($product->isInStock() ? 'Lihat Detail' : 'Stok Habis'); ?>

    </span>
  </div>
</a>

<?php if (! $__env->hasRenderedOnce('ef679b6b-b28b-4690-983a-08b71ad7429f')): $__env->markAsRenderedOnce('ef679b6b-b28b-4690-983a-08b71ad7429f'); ?>
<style>
  .prod-card{ transition:transform .15s ease, box-shadow .15s ease, border-color .15s ease; }
  .prod-card:hover{ transform:translateY(-3px); box-shadow:0 12px 28px -14px rgba(15,23,42,.25); }
  .prod-card-featured{ border-color:rgba(79,70,229,.35)!important; box-shadow:0 4px 16px -8px rgba(79,70,229,.25); }
  .prod-card-featured:hover{ box-shadow:0 14px 30px -12px rgba(79,70,229,.4); }
</style>
<?php endif; ?>
<?php /**PATH /home/runner/workspace/hosting-billing/resources/views/themes/public-themes/default/public/catalog/_product-card.blade.php ENDPATH**/ ?>