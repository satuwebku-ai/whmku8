  
  <section class="mx-hero">
    <div class="mx-container">
      <div class="mx-hero-grid">
        <div>
          <span class="mx-eyebrow">Aktivasi otomatis, langsung online</span>
          <h1 class="mx-h1"><?php echo e($tagline); ?></h1>
          <p class="mx-lead">Cek nama domain impianmu, pilih paket hosting, bayar — layanan langsung aktif tanpa menunggu.</p>

          <form method="GET" action="<?php echo e(route('domain.search')); ?>" class="mx-search">
            <i class="fa-solid fa-globe"></i>
            <input type="text" name="domain" value="<?php echo e(request('domain')); ?>" placeholder="ketik nama domain impianmu…" required>
            <button type="submit" class="btn btn-theme" style="padding:.7rem 1.6rem">Cek Domain</button>
          </form>

          <div class="mx-trust">
            <span><i class="fa-solid fa-shield-halved"></i>SSL tersedia</span>
            <span><i class="fa-solid fa-bolt"></i>Aktif otomatis</span>
            <span><i class="fa-solid fa-headset"></i>Support responsif</span>
          </div>
        </div>

        <?php if($popularTlds->isNotEmpty()): ?>
          <aside class="mx-panel">
            <h3>Harga domain populer</h3>
            <?php $__currentLoopData = $popularTlds; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tld): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <div class="mx-tld">
                <b><?php echo e($tld->extension); ?></b>
                <span>Rp <?php echo e(number_format($tld->register_price, 0, ',', '.')); ?></span>
              </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </aside>
        <?php endif; ?>
      </div>
    </div>
  </section>
<?php /**PATH /home/runner/workspace/hosting-billing/resources/views/themes/public-themes/modern/public/home/_domain.blade.php ENDPATH**/ ?>