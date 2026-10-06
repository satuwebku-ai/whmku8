
<?php if($banners->isNotEmpty()): ?>
  <section class="container py-4">
    <div class="position-relative rounded-4 overflow-hidden" id="nhPromoCarousel">
      <?php $__currentLoopData = $banners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $banner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
          // Judul "-" = penanda "tanpa judul" (field wajib di form admin).
          $bannerTitle = trim((string) $banner->title) === '-' ? '' : $banner->title;
        ?>

        <div class="nh-promo-slide <?php echo e($i === 0 ? '' : 'd-none'); ?>">
          <?php if($banner->link_url): ?>
            <a href="<?php echo e($banner->link_url); ?>" <?php if($banner->open_in_new_tab): ?> target="_blank" rel="noopener noreferrer" <?php endif; ?> class="d-block position-relative">
          <?php else: ?>
            <div class="position-relative">
          <?php endif; ?>

            <img src="<?php echo e(route('banner.file', $banner->image)); ?>" alt="<?php echo e($banner->title); ?>" class="w-100 d-block" style="height:auto">

            <?php if($bannerTitle || $banner->subtitle || $banner->button_text): ?>
              <div class="position-absolute top-0 start-0 end-0 bottom-0 d-flex align-items-center" style="background:linear-gradient(to right, rgba(0,0,0,.6), rgba(0,0,0,.2) 60%, transparent)">
                <div class="px-4 px-lg-5" style="max-width:34rem">
                  <?php if($bannerTitle): ?>
                    <h2 class="h4 text-white fw-bold mb-1"><?php echo e($bannerTitle); ?></h2>
                  <?php endif; ?>
                  <?php if($banner->subtitle): ?>
                    <p class="text-white opacity-75 small mb-3"><?php echo e($banner->subtitle); ?></p>
                  <?php endif; ?>
                  <?php if($banner->button_text): ?>
                    <span class="btn btn-accent"><?php echo e($banner->button_text); ?></span>
                  <?php endif; ?>
                </div>
              </div>
            <?php endif; ?>

          <?php if($banner->link_url): ?>
            </a>
          <?php else: ?>
            </div>
          <?php endif; ?>
        </div>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

      <?php if($banners->count() > 1): ?>
        <div class="position-absolute d-flex gap-2" style="bottom:12px;left:50%;transform:translateX(-50%)">
          <?php $__currentLoopData = $banners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $banner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <button type="button" class="nh-promo-dot rounded-circle border-0 p-0" aria-label="Banner <?php echo e($i + 1); ?>" style="width:8px;height:8px;background:<?php echo e($i === 0 ? '#fff' : 'rgba(255,255,255,.4)'); ?>"></button>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
      <?php endif; ?>
    </div>
  </section>

  <?php if($banners->count() > 1): ?>
    <script <?php echo 'nonce="' . e(app(\App\Support\CspNonce::class)->value()) . '"'; ?>>
      (function () {
        var slides = document.querySelectorAll('#nhPromoCarousel .nh-promo-slide');
        var dots = document.querySelectorAll('#nhPromoCarousel .nh-promo-dot');
        var current = 0;

        function show(i) {
          slides.forEach(function (s, n) { s.classList.toggle('d-none', n !== i); });
          dots.forEach(function (d, n) { d.style.background = n === i ? '#fff' : 'rgba(255,255,255,.4)'; });
          current = i;
        }

        dots.forEach(function (d, n) { d.addEventListener('click', function () { show(n); }); });
        setInterval(function () { show((current + 1) % slides.length); }, 5000);
      })();
    </script>
  <?php endif; ?>
<?php endif; ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/themes/public-themes/namahost/public/home/_banner.blade.php ENDPATH**/ ?>