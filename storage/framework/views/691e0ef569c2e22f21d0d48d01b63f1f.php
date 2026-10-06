<?php
  use App\Models\Setting;
  $siteName = Setting::get('site_name', config('app.name', 'NamaHost'));
  $tagline  = Setting::get('site_tagline', 'Hosting & Domain Terpercaya');
  $loginLogo = Setting::get('site_logo');
  $brandingDisplay = Setting::get('branding_display', 'logo_and_text');
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
<title><?php echo $__env->yieldContent('title'); ?> — <?php echo e($siteName); ?></title>

<?php if(Setting::get('site_favicon')): ?>
  <link rel="icon" href="<?php echo e(route('branding.file', Setting::get('site_favicon'))); ?>">
<?php endif; ?>

<link rel="stylesheet" href="<?php echo e(asset('assets/css/vendor/bootstrap-5.3.8.min.css')); ?>?v=<?php echo e(@filemtime(public_path('assets/css/vendor/bootstrap-5.3.8.min.css')) ?: time()); ?>">
<link rel="stylesheet" href="<?php echo e(asset('assets/css/theme-namahost.css')); ?>?v=<?php echo e(@filemtime(public_path('assets/css/theme-namahost.css')) ?: time()); ?>">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>

<div class="row g-0 min-vh-100">

  
  <div class="col-lg-5 auth-side d-none d-lg-flex flex-column justify-content-between p-5">
    <a href="<?php echo e(route('home')); ?>" class="navbar-brand text-white text-decoration-none fs-4 d-flex align-items-center gap-2">
      <?php if($brandingDisplay !== 'text_only'): ?>
        <?php if($loginLogo): ?>
          <img src="<?php echo e(route('branding.file', $loginLogo)); ?>" alt="<?php echo e($siteName); ?>" style="height:44px;width:auto;object-fit:contain">
        <?php else: ?>
          <span class="brand-mark"><i class="bi bi-hdd-network"></i></span>
        <?php endif; ?>
      <?php endif; ?>
      <?php if($brandingDisplay !== 'logo_only' || ! $loginLogo): ?>
        <span><?php echo e($siteName); ?></span>
      <?php endif; ?>
    </a>

    <div>
      <div class="hero-art mb-4" style="max-width:300px">
        <svg viewBox="0 0 400 320" class="w-100" role="img" aria-label="Ilustrasi server hosting">
          <defs><linearGradient id="nhAuthG" x1="0" x2="1"><stop offset="0" stop-color="#22d3c5"/><stop offset="1" stop-color="#5b5bd6"/></linearGradient></defs>
          <circle cx="200" cy="160" r="145" fill="url(#nhAuthG)" opacity=".2"/>
          <ellipse cx="200" cy="160" rx="185" ry="62" fill="none" stroke="#22d3c5" stroke-opacity=".5" stroke-dasharray="4 8"/>
          <?php $__currentLoopData = [50, 130, 210]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $y): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <rect x="90" y="<?php echo e($y); ?>" width="220" height="64" rx="14" fill="#123f4b" stroke="#22d3c5" stroke-opacity=".6"/>
            <circle class="led" cx="118" cy="<?php echo e($y + 32); ?>" r="5" fill="#22d3c5"/>
            <circle class="led" cx="138" cy="<?php echo e($y + 32); ?>" r="5" fill="#f5a524"/>
            <circle class="led" cx="158" cy="<?php echo e($y + 32); ?>" r="5" fill="#ff6f59"/>
            <rect x="190" y="<?php echo e($y + 22); ?>" width="96" height="8" rx="4" fill="#22d3c5" opacity=".55"/>
            <rect x="190" y="<?php echo e($y + 38); ?>" width="60" height="8" rx="4" fill="#fff" opacity=".25"/>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </svg>
      </div>
      <h1 class="display-6 mb-3">Kelola semua layanan hosting Anda di satu tempat</h1>
      <p class="text-white-50 mb-0"><?php echo e($tagline); ?></p>
      <ul class="list-unstyled mt-4 mb-0 text-white-50">
        <li class="mb-2"><i class="bi bi-check-circle-fill text-warning me-2"></i>Kelola layanan hosting &amp; domain</li>
        <li class="mb-2"><i class="bi bi-check-circle-fill text-warning me-2"></i>Lihat dan bayar invoice online</li>
        <li><i class="bi bi-check-circle-fill text-warning me-2"></i>Ajukan tiket support kapan saja</li>
      </ul>
    </div>

    <small class="text-white-50">&copy; <?php echo e(date('Y')); ?> <?php echo e($siteName); ?></small>
  </div>

  
  <div class="col-lg-7 d-flex align-items-center justify-content-center p-4" style="overflow-y:auto">
    <div class="w-100 py-4" style="max-width:440px">
      <a href="<?php echo e(route('home')); ?>" class="d-lg-none navbar-brand text-body d-flex align-items-center gap-2 mb-4">
        <?php if($loginLogo): ?>
          <img src="<?php echo e(route('branding.file', $loginLogo)); ?>" alt="<?php echo e($siteName); ?>" style="height:40px;width:auto;object-fit:contain">
        <?php else: ?>
          <span class="brand-mark"><i class="bi bi-hdd-network"></i></span>
          <span><?php echo e($siteName); ?></span>
        <?php endif; ?>
      </a>

      <?php if(session('success')): ?>
        <div class="alert alert-success" role="status"><?php echo e(session('success')); ?></div>
      <?php endif; ?>

      <?php echo $__env->yieldContent('form'); ?>

      <p class="text-center text-body-secondary mt-5 mb-0" style="font-size:12px">&copy; <?php echo e(date('Y')); ?> <?php echo e($siteName); ?></p>
    </div>
  </div>
</div>

<script src="<?php echo e(asset('assets/js/vendor/bootstrap-5.3.8.bundle.min.js')); ?>"></script>
</body>
</html>
<?php /**PATH /home/runner/workspace/hosting-billing/resources/views/themes/client-themes/namahost/client/auth/layout.blade.php ENDPATH**/ ?>