<?php
  use App\Models\Setting;
  $siteName = Setting::get('site_name', config('app.name', 'Lumora Hosting'));
  $tagline = Setting::get('site_tagline', 'Hosting & Domain Terpercaya');
  $themeColor = Setting::get('theme_color', '#6366F1');
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
<title><?php echo $__env->yieldContent('title'); ?> — <?php echo e($siteName); ?></title>

<link rel="stylesheet" href="<?php echo e(asset('assets/css/vendor/bootstrap-5.3.8.min.css')); ?>?v=<?php echo e(@filemtime(public_path('assets/css/vendor/bootstrap-5.3.8.min.css')) ?: time()); ?>">
<link rel="stylesheet" href="<?php echo e(asset('assets/css/lumora-public.css')); ?>?v=<?php echo e(@filemtime(public_path('assets/css/lumora-public.css')) ?: time()); ?>">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>
  :root{ --lumora-theme: <?php echo e($themeColor); ?>; }
  html, body{ height:100%; font-family:'Inter', sans-serif; }
  .bg-auth{ background:linear-gradient(135deg,#1e1b4b 0%,#312e81 35%,#4c1d95 70%,#1e1b4b 100%); }
  .bg-auth-dots{ background-image:radial-gradient(circle at 20% 20%, white 1px, transparent 1px); background-size:32px 32px; opacity:.2; }
</style>
</head>
<body>

<div class="d-flex" style="min-height:100vh">

  <div class="d-none d-lg-flex bg-auth position-relative overflow-hidden align-items-center justify-content-center p-5" style="width:50%">
    <div class="position-absolute top-0 start-0 end-0 bottom-0 bg-auth-dots"></div>
    <div class="position-relative text-center" style="max-width:26rem">
      <?php
        $loginLogo = \App\Models\Setting::get('site_logo');
        $brandingDisplay = \App\Models\Setting::get('branding_display', 'logo_and_text');
      ?>
      <?php if($brandingDisplay !== 'text_only'): ?>
        <?php if($loginLogo): ?>
          <img src="<?php echo e(route('branding.file', $loginLogo)); ?>" alt="<?php echo e($siteName); ?>" class="mb-4" style="height:80px;width:auto;object-fit:contain">
        <?php else: ?>
          <div class="rounded-4 d-flex align-items-center justify-content-center mx-auto mb-4" style="width:64px;height:64px;background:rgba(255,255,255,.1)">
            <svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="#fff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2 3 14h7l-1 8 11-12h-7l1-8z"/></svg>
          </div>
        <?php endif; ?>
      <?php endif; ?>
      <?php if($brandingDisplay !== 'logo_only'): ?>
        <h1 class="text-white fw-bold mb-3" style="font-size:1.6rem"><?php echo e($siteName); ?></h1>
      <?php endif; ?>
      <p class="mb-0" style="color:rgba(255,255,255,.6);font-size:14px;line-height:1.7"><?php echo e($tagline); ?></p>

      <div class="mt-4 d-flex flex-column gap-3 text-start">
        <?php $__currentLoopData = ['Kelola layanan hosting & domain', 'Lihat dan bayar invoice online', 'Ajukan tiket support kapan saja']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <div class="d-flex align-items-center gap-3" style="color:rgba(255,255,255,.7);font-size:14px">
            <span class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:20px;height:20px;background:rgba(255,255,255,.15);font-size:10px">&check;</span>
            <?php echo e($feature); ?>

          </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </div>
    </div>
  </div>

  <div class="flex-grow-1 d-flex align-items-center justify-content-center p-4" style="background:#f8fafc;overflow-y:auto">
    <div class="w-100 py-4" style="max-width:26rem">
      <div class="d-lg-none d-flex align-items-center gap-3 mb-4 justify-content-center">
        <?php if($loginLogo): ?>
          <img src="<?php echo e(route('branding.file', $loginLogo)); ?>" alt="<?php echo e($siteName); ?>" style="height:44px;width:auto;object-fit:contain">
        <?php else: ?>
          <div class="rounded-3 d-flex align-items-center justify-content-center" style="width:36px;height:36px;background:var(--lumora-theme)">
            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="#fff" stroke-width="2.2"><path d="M13 2 3 14h7l-1 8 11-12h-7l1-8z"/></svg>
          </div>
          <span class="fw-bold text-dark" style="font-size:1.1rem"><?php echo e($siteName); ?></span>
        <?php endif; ?>
      </div>

      <?php if(session('success')): ?>
        <div class="rounded-3 px-3 py-2 mb-4" style="background:#f0fdf4;border:1px solid #bbf7d0;font-size:14px;color:#15803d">
          <?php echo e(session('success')); ?>

        </div>
      <?php endif; ?>

      <?php echo $__env->yieldContent('form'); ?>

      <p class="text-center text-muted mt-5 mb-0" style="font-size:12px">
        &copy; <?php echo e(date('Y')); ?> <?php echo e($siteName); ?>

      </p>
    </div>
  </div>
</div>

</body>
</html>
<?php /**PATH /home/runner/workspace/hosting-billing/resources/views/themes/client-themes/default/client/auth/layout.blade.php ENDPATH**/ ?>