
<?php
  $benefits = [
    ['title' => 'Aktif Otomatis',     'desc' => 'Akun hosting dibuat otomatis begitu pembayaran masuk.'],
    ['title' => 'Aman & Terjaga',     'desc' => 'Sertifikat SSL tersedia, backup rutin, dan proteksi berlapis.'],
    ['title' => 'Dukungan Responsif', 'desc' => 'Tim support siap membantu lewat tiket dan chat.'],
    ['title' => 'Bayar Mudah',        'desc' => 'Transfer bank, e-wallet, kartu kredit, dan QRIS.'],
  ];
?>
<section class="mx-section" style="padding-bottom:1rem">
  <div class="mx-container">
    <div class="mx-benefits">
      <?php $__currentLoopData = $benefits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="mx-benefit">
          <span class="n"><?php echo e(str_pad($loop->iteration, 2, '0', STR_PAD_LEFT)); ?></span>
          <h3><?php echo e($item['title']); ?></h3>
          <p><?php echo e($item['desc']); ?></p>
        </div>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
  </div>
</section>
<?php /**PATH /home/runner/workspace/hosting-billing/resources/views/themes/public-themes/modern/public/home/_benefits.blade.php ENDPATH**/ ?>