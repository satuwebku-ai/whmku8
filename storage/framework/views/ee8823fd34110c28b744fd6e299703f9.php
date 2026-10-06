<?php $__env->startSection('title', 'Analytics'); ?>
<?php $__env->startSection('content'); ?>
  <?php echo $__env->make('admin.settings._nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

  <?php use App\Models\Setting; ?>

  <div class="mb-4">
    <h1 class="h4 fw-bold text-dark mb-1">Analytics</h1>
    <p class="small text-muted mb-0">Pelacakan pengunjung di halaman publik.</p>
  </div>

  <div class="rounded-3 px-3 py-2 mb-3" style="max-width:42rem;background:#eef2ff;border:1px solid #c7d2fe;font-size:12px;color:#4338ca">
    <i class="fa-solid fa-circle-info"></i>
    Isi <b>ID-nya saja</b>, bukan seluruh kode script. Script-nya dibangun otomatis oleh sistem —
    ini disengaja, karena menempelkan HTML mentah dari database ke halaman publik membuka celah XSS.
  </div>

  <form method="POST" action="<?php echo e(route('admin.settings.analytics.update')); ?>" class="card border rounded-4 p-4" style="max-width:42rem">
    <?php echo csrf_field(); ?>

    <div class="mb-3">
      <label class="form-label small fw-medium text-dark">Google Analytics 4 — Measurement ID</label>
      <input type="text" name="ga_measurement_id" value="<?php echo e(old('ga_measurement_id', Setting::get('ga_measurement_id'))); ?>" class="form-control form-control-sm" placeholder="G-XXXXXXXXXX">
      <?php $__errorArgs = ['ga_measurement_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      <p class="text-muted mt-1 mb-0" style="font-size:11px">Dari Google Analytics » Admin » Data Streams.</p>
    </div>

    <div class="mb-3">
      <label class="form-label small fw-medium text-dark">Google Tag Manager — Container ID</label>
      <input type="text" name="gtm_container_id" value="<?php echo e(old('gtm_container_id', Setting::get('gtm_container_id'))); ?>" class="form-control form-control-sm" placeholder="GTM-XXXXXXX">
      <?php $__errorArgs = ['gtm_container_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    <div class="mb-3">
      <label class="form-label small fw-medium text-dark">Facebook Pixel ID</label>
      <input type="text" name="fb_pixel_id" value="<?php echo e(old('fb_pixel_id', Setting::get('fb_pixel_id'))); ?>" class="form-control form-control-sm" placeholder="1234567890123456">
      <?php $__errorArgs = ['fb_pixel_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-check" style="font-size:11px"></i> Simpan Pengaturan</button>
  </form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/admin/settings/analytics.blade.php ENDPATH**/ ?>