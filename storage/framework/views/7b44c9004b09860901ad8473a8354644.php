<?php
  $seoTitle = 'Paket Hosting & Domain';
  $seoDescription = 'Pilih paket hosting sesuai kebutuhan Anda — mulai dari shared hosting hingga VPS, lengkap dengan domain.';
?>

<?php $__env->startSection('content'); ?>

  <?php echo $__env->make('public._promo-banner-carousel', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

  <?php if(request('dari_domain')): ?>
    <div class="card-public p-3 mb-4 d-flex align-items-center justify-content-between gap-3 flex-wrap" style="border-color:rgba(79,70,229,.25)!important;background:rgba(79,70,229,.04)">
      <div class="d-flex align-items-center gap-3">
        <div class="d-flex align-items-center gap-2" style="font-size:12px">
          <span class="rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width:24px;height:24px;font-size:11px;background:#e2e8f0;color:#64748b">✓</span>
          <span class="text-muted">Domain</span>
          <span style="width:32px;height:1px;background:#e2e8f0"></span>
          <span class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white" style="width:24px;height:24px;font-size:11px;background:var(--lumora-theme)">2</span>
          <span class="fw-semibold text-dark">Hosting</span>
        </div>
        <p class="text-muted mb-0 d-none d-sm-block" style="font-size:14px">
          Pilih paket di bawah untuk didampingkan dengan domain Anda, atau lewati kalau cuma butuh domainnya saja.
        </p>
      </div>
      <a href="<?php echo e(route('cart.index')); ?>" class="btn btn-outline-secondary btn-sm flex-shrink-0">
        Lewati — Cuma Domain Saja <i class="fa-solid fa-arrow-right" style="font-size:12px"></i>
      </a>
    </div>
  <?php endif; ?>

  <div class="text-center mb-5 mx-auto" style="max-width:40rem">
    <h1 class="fw-bold text-dark mb-3" style="font-size:1.9rem">Paket Hosting untuk Setiap Kebutuhan</h1>
    <p class="text-muted mb-0">Dari website pribadi sampai toko online — pilih paket yang pas, aktif dalam hitungan menit.</p>
    <div class="mt-4">
      <a href="<?php echo e(route('domain.search')); ?>" class="btn btn-outline-secondary">
        <i class="fa-solid fa-magnifying-glass" style="font-size:12px"></i> Cek Ketersediaan Domain
      </a>
    </div>
  </div>

  <?php if($featured->isNotEmpty()): ?>
    <div class="mb-5">
      <h2 class="fw-bold text-dark mb-3" style="font-size:1.15rem">Paket Unggulan</h2>
      <div class="row g-3">
        <?php $__currentLoopData = $featured; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <div class="col-sm-6 col-lg-4">
            <?php echo $__env->make('public.catalog._product-card', ['product' => $product], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
          </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </div>
    </div>
  <?php endif; ?>

  <div>
    <h2 class="fw-bold text-dark mb-3" style="font-size:1.15rem">Kategori</h2>

    <?php if($categories->isEmpty()): ?>
      <div class="card-public p-5 text-center text-muted" style="font-size:14px">Katalog sedang disiapkan. Silakan cek kembali nanti.</div>
    <?php else: ?>
      <div class="row g-3">
        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <div class="col-sm-6 col-lg-4">
            <a href="<?php echo e($category->publicUrl()); ?>" class="card-public p-4 text-decoration-none d-block h-100 cat-card">
              <div class="d-flex align-items-start justify-content-between mb-3">
                <span class="rounded-4 d-flex align-items-center justify-content-center cat-icon" style="width:44px;height:44px;background:rgba(79,70,229,.12);color:#4f46e5;transition:transform .15s ease">
                  <i class="fa-solid <?php echo e($category->icon ?: 'fa-box'); ?>"></i>
                </span>
                <i class="fa-solid fa-arrow-right cat-arrow" style="font-size:12px;color:var(--lumora-theme);opacity:0;transform:translateX(-4px);transition:opacity .15s ease,transform .15s ease"></i>
              </div>
              <h3 class="fw-semibold text-dark mb-1" style="font-size:15px"><?php echo e($category->name); ?></h3>
              <?php if($category->description): ?>
                <p class="text-muted mb-2" style="font-size:14px"><?php echo e($category->description); ?></p>
              <?php endif; ?>
              <p class="text-muted mb-0" style="font-size:12px"><?php echo e($category->products_count); ?> paket tersedia</p>
            </a>
          </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </div>

      <style>
        .cat-card{ transition:transform .15s ease, box-shadow .15s ease, border-color .15s ease; }
        .cat-card:hover{ transform:translateY(-3px); border-color:rgba(79,70,229,.35)!important; box-shadow:0 10px 24px -12px rgba(79,70,229,.35); }
        .cat-card:hover .cat-icon{ transform:scale(1.08); }
        .cat-card:hover .cat-arrow{ opacity:1; transform:translateX(0); }
      </style>
    <?php endif; ?>
  </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('public.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/themes/public-themes/modern/public/catalog/index.blade.php ENDPATH**/ ?>