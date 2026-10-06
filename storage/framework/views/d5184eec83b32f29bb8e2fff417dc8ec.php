<?php $__env->startSection('title', 'Pengaturan SEO'); ?>
<?php $__env->startSection('content'); ?>
  <?php echo $__env->make('admin.settings._nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

  <?php use App\Models\Setting; ?>

  <div class="mb-4">
    <h1 class="h4 fw-bold text-dark mb-1">Pengaturan SEO</h1>
    <p class="small text-muted mb-0">Nilai default untuk halaman yang belum punya meta sendiri.</p>
  </div>

  <?php if(Setting::get('seo_noindex_site') === '1'): ?>
    <div class="rounded-3 px-3 py-2 mb-3" style="max-width:42rem;background:#fef2f2;border:1px solid #fecaca;font-size:12px;color:#b91c1c">
      <i class="fa-solid fa-triangle-exclamation"></i>
      <b>Situs sedang disembunyikan dari mesin pencari.</b> Jangan lupa matikan opsi ini
      setelah situs siap diluncurkan, kalau tidak halamanmu tidak akan pernah muncul di Google.
    </div>
  <?php endif; ?>

  <form method="POST" action="<?php echo e(route('admin.settings.seo.update')); ?>" class="card border rounded-4 p-4" style="max-width:42rem">
    <?php echo csrf_field(); ?>

    <div class="mb-3">
      <label class="form-label small fw-medium text-dark">Meta Title Default</label>
      <input type="text" name="seo_title" maxlength="70" value="<?php echo e(old('seo_title', Setting::get('seo_title'))); ?>" class="form-control form-control-sm" placeholder="Hosting Murah & Domain Indonesia">
      <?php $__errorArgs = ['seo_title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      <p class="text-muted mt-1 mb-0" style="font-size:11px">Maks. 70 karakter, idealnya di bawah 60.</p>
    </div>

    <div class="mb-3">
      <label class="form-label small fw-medium text-dark">Meta Description Default</label>
      <textarea name="seo_description" rows="3" maxlength="170" class="form-control form-control-sm" placeholder="Layanan hosting cepat dengan dukungan 24/7..."><?php echo e(old('seo_description', Setting::get('seo_description'))); ?></textarea>
      <?php $__errorArgs = ['seo_description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      <p class="text-muted mt-1 mb-0" style="font-size:11px">Idealnya 120–155 karakter.</p>
    </div>

    <div class="row g-3 mb-3">
      <div class="col-sm-6">
        <label class="form-label small fw-medium text-dark">Keywords Default</label>
        <input type="text" name="seo_keywords" value="<?php echo e(old('seo_keywords', Setting::get('seo_keywords'))); ?>" class="form-control form-control-sm" placeholder="hosting indonesia, domain .id">
      </div>
      <div class="col-sm-6">
        <label class="form-label small fw-medium text-dark">OG Image Default (URL)</label>
        <input type="text" name="seo_og_image" value="<?php echo e(old('seo_og_image', Setting::get('seo_og_image'))); ?>" class="form-control form-control-sm" placeholder="https://.../og-image.jpg">
        <p class="text-muted mt-1 mb-0" style="font-size:11px">Ukuran ideal 1200×630 px.</p>
      </div>
    </div>

    <div class="mb-3">
      <label class="form-label small fw-medium text-dark">Canonical URL Situs (opsional)</label>
      <input type="url" name="seo_canonical" value="<?php echo e(old('seo_canonical', Setting::get('seo_canonical'))); ?>" class="form-control form-control-sm" placeholder="https://contohhosting.com">
      <?php $__errorArgs = ['seo_canonical'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    <div class="mb-3">
      <label class="form-label small fw-medium text-dark">Isi robots.txt (opsional)</label>
      <textarea name="seo_robots" rows="5" class="form-control form-control-sm" style="font-family:monospace;font-size:12px" placeholder="User-agent: *&#10;Disallow: /admin&#10;Sitemap: https://contohhosting.com/sitemap.xml"><?php echo e(old('seo_robots', Setting::get('seo_robots'))); ?></textarea>
      <p class="text-muted mt-1 mb-0" style="font-size:11px">
        Catatan: field ini menyimpan teksnya saja. Untuk benar-benar dipakai, isi file
        <code>public/robots.txt</code> di server dengan konten ini, atau buat route khusus.
      </p>
    </div>

    <div class="pt-3 border-top mb-3">
      <label class="d-flex align-items-start gap-2 small text-dark">
        <input type="checkbox" name="seo_noindex_site" value="1" <?php if(old('seo_noindex_site', Setting::get('seo_noindex_site') === '1')): echo 'checked'; endif; ?> class="form-check-input flex-shrink-0" style="margin-top:2px">
        <span>
          Sembunyikan seluruh situs dari mesin pencari
          <span class="d-block text-muted" style="font-size:11px">Pakai saat situs masih dikembangkan. Wajib dimatikan sebelum peluncuran.</span>
        </span>
      </label>
    </div>

    <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-check" style="font-size:11px"></i> Simpan Pengaturan</button>
  </form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/admin/settings/seo.blade.php ENDPATH**/ ?>