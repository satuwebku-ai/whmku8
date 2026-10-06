<?php ($seoTitle = 'Lisensi & Sertifikat SSL'); ?>

<?php $__env->startSection('content'); ?>
  <section class="py-4 py-lg-5">
    <div class="text-center mb-4">
      <span class="badge badge-soft-warning mb-2">LISENSI & SSL</span>
      <h1 class="display-6 fw-bold text-dark">Lisensi dan sertifikat SSL yang siap dipakai</h1>
      <p class="text-muted mb-0 mx-auto" style="max-width:40rem">Bandingkan fitur, spesifikasi, dan harga. Pilih produk yang cocok, lalu tambahkan ke keranjang untuk checkout.</p>
    </div>

    <?php if($categories->isNotEmpty() && $all->isNotEmpty()): ?>
      <div class="d-flex flex-wrap justify-content-center gap-2 mb-4">
        <a href="<?php echo e(route('license.index')); ?>" class="btn btn-sm rounded-pill px-3 <?php echo e($activeCategory ? 'btn-outline-secondary' : 'btn-theme'); ?>">Semua (<?php echo e($all->count()); ?>)</a>
        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <a href="<?php echo e(route('license.index', ['kategori' => $key])); ?>" class="btn btn-sm rounded-pill px-3 <?php echo e($activeCategory === $key ? 'btn-theme' : 'btn-outline-secondary'); ?>"><?php echo e($cat['label']); ?> (<?php echo e($cat['count']); ?>)</a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </div>
    <?php endif; ?>

    <div class="row g-4">
      <?php $__empty_1 = true; $__currentLoopData = $licenses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $license): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <?php ($cheapest = $license->cheapestCycle()); ?>
        <div class="col-12 col-md-6 col-xl-4">
          <article class="card-public h-100 p-4 d-flex flex-column">
            <div class="d-flex align-items-center justify-content-between mb-3">
              <span class="d-inline-flex align-items-center justify-content-center rounded-3 text-theme" style="width:44px;height:44px;background:rgba(14,124,134,.1)">
                <i class="fa-solid <?php echo e($license->category === 'ssl' ? 'fa-lock' : 'fa-key'); ?>"></i>
              </span>
              <span class="d-flex gap-1">
                <?php if($license->brand): ?><span class="badge badge-soft-secondary"><?php echo e($license->brand); ?></span><?php endif; ?>
                <span class="badge badge-soft-success"><?php echo e($license->category === 'ssl' ? 'SSL' : 'Lisensi'); ?></span>
              </span>
            </div>

            <h2 class="h5 fw-bold text-dark mb-1"><?php echo e($license->name); ?></h2>
            <p class="text-muted small mb-3"><?php echo e($license->summary ?: \Illuminate\Support\Str::limit($license->description, 110)); ?></p>

            <?php if(! empty($license->features)): ?>
              <ul class="list-unstyled small text-muted flex-grow-1 mb-3">
                <?php $__currentLoopData = array_slice($license->features, 0, 3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <li class="d-flex gap-2 mb-1"><i class="fa-solid fa-check text-theme mt-1" style="font-size:11px"></i><span><?php echo e($feature); ?></span></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </ul>
            <?php else: ?>
              <div class="flex-grow-1"></div>
            <?php endif; ?>

            <div class="border-top pt-3 mb-3">
              <div class="text-muted" style="font-size:11px"><?php echo e(count($license->availableCycles()) > 1 ? 'Mulai dari' : 'Harga'); ?></div>
              <div class="d-flex align-items-baseline gap-1">
                <span class="h4 fw-bold text-dark mb-0">Rp <?php echo e(number_format($cheapest['price'], 0, ',', '.')); ?></span>
                <span class="text-muted small"><?php echo e(\App\Models\Addon::CYCLE_SUFFIX[$cheapest['cycle']] ?? ''); ?></span>
              </div>
              <?php if(! empty($license->specs['Registrasi & perpanjangan'])): ?>
                <div class="text-muted" style="font-size:11px">Registrasi & perpanjangan: <?php echo e(strtolower($license->specs['Registrasi & perpanjangan'])); ?></div>
              <?php endif; ?>
            </div>

            <div class="d-flex gap-2">
              <a href="<?php echo e(route('license.show', $license->slug)); ?>" class="btn btn-outline-secondary flex-fill">Detail</a>
              <?php if($license->requiresIp()): ?>
                <a href="<?php echo e(route('license.show', $license->slug)); ?>" class="btn btn-theme flex-fill"><i class="fa-solid fa-cart-plus me-1"></i> Pesan</a>
              <?php else: ?>
                <form method="POST" action="<?php echo e(route('cart.add-addon')); ?>" class="flex-fill">
                  <?php echo csrf_field(); ?>
                  <input type="hidden" name="addon_id" value="<?php echo e($license->id); ?>">
                  <input type="hidden" name="billing_cycle" value="<?php echo e($cheapest['cycle']); ?>">
                  <button class="btn btn-theme w-100"><i class="fa-solid fa-cart-plus me-1"></i> Keranjang</button>
                </form>
              <?php endif; ?>
            </div>
          </article>
        </div>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="col-12"><div class="card-public p-5 text-center text-muted">Belum ada lisensi yang tersedia.</div></div>
      <?php endif; ?>
    </div>
  </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('public.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/themes/public-themes/default/public/licenses/index.blade.php ENDPATH**/ ?>