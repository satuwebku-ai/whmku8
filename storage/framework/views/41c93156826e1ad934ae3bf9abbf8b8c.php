<?php $__env->startSection('title', 'Pengaturan Umum'); ?>
<?php $__env->startSection('content'); ?>
  <?php echo $__env->make('admin.settings._nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

  <?php
    use App\Models\Setting;

    $brandingPresetGroups = [
      'logo' => 'Logo Lengkap (ikon + nama)',
      'icon' => 'Ikon Saja',
      'wordmark' => 'Teks Saja (tanpa ikon)',
      'favicon' => 'Favicon',
    ];
    $brandingPresetColors = [
      'indigo' => 'Indigo', 'blue' => 'Biru', 'emerald' => 'Emerald', 'teal' => 'Teal',
      'amber' => 'Amber', 'rose' => 'Rose', 'slate' => 'Slate', 'graywhite' => 'Abu-Putih', 'white' => 'Putih',
    ];
  ?>

  <div class="mb-4">
    <h1 class="h4 fw-bold text-dark mb-1">Pengaturan Umum</h1>
    <p class="small text-muted mb-0">Identitas bisnis yang tampil di halaman publik dan email.</p>
  </div>

  <form method="POST" action="<?php echo e(route('admin.settings.general.update')); ?>" enctype="multipart/form-data" class="card border rounded-4 p-4" style="max-width:42rem">
    <?php echo csrf_field(); ?>
    <div class="row g-3 mb-3">
      <div class="col-sm-6">
        <label class="form-label small fw-medium text-dark">Nama Situs</label>
        <input type="text" name="site_name" value="<?php echo e(old('site_name', Setting::get('site_name', config('app.name')))); ?>" class="form-control form-control-sm" required>
        <?php $__errorArgs = ['site_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      </div>
      <div class="col-sm-6">
        <label class="form-label small fw-medium text-dark">Tagline</label>
        <input type="text" name="site_tagline" value="<?php echo e(old('site_tagline', Setting::get('site_tagline'))); ?>" class="form-control form-control-sm" placeholder="Hosting cepat & terjangkau">
      </div>
    </div>

    <div class="mb-3">
      <label class="form-label small fw-medium text-dark">Nama Perusahaan (untuk invoice)</label>
      <input type="text" name="company_name" value="<?php echo e(old('company_name', Setting::get('company_name'))); ?>" class="form-control form-control-sm" placeholder="PT Contoh Hosting">
    </div>

    <div class="row g-3 mb-3">
      <div class="col-sm-6">
        <label class="form-label small fw-medium text-dark">Email Support</label>
        <input type="email" name="support_email" value="<?php echo e(old('support_email', Setting::get('support_email'))); ?>" class="form-control form-control-sm" placeholder="support@contoh.com">
        <?php $__errorArgs = ['support_email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      </div>
      <div class="col-sm-6">
        <label class="form-label small fw-medium text-dark">Telepon Support</label>
        <input type="text" name="support_phone" value="<?php echo e(old('support_phone', Setting::get('support_phone'))); ?>" class="form-control form-control-sm" placeholder="+62 811 2345 678">
      </div>
    </div>

    <div class="mb-3">
      <label class="form-label small fw-medium text-dark">Alamat Perusahaan</label>
      <textarea name="company_address" rows="2" class="form-control form-control-sm"><?php echo e(old('company_address', Setting::get('company_address'))); ?></textarea>
    </div>

    <div class="mb-3">
      <label class="form-label small fw-medium text-dark">Teks Footer</label>
      <input type="text" name="footer_text" value="<?php echo e(old('footer_text', Setting::get('footer_text'))); ?>" class="form-control form-control-sm" placeholder="© <?php echo e(date('Y')); ?> Nama Perusahaan. Semua hak dilindungi.">
    </div>

    
    <div class="pt-3 border-top">
      <div class="d-flex align-items-center justify-content-between mb-3">
        <h2 class="small fw-bold text-dark mb-0">Identitas Visual</h2>
        <a href="<?php echo e(route('admin.settings.branding-diagnostics')); ?>" target="_blank" class="text-decoration-none text-accent" style="font-size:12px">
          <i class="fa-solid fa-stethoscope" style="font-size:10px"></i> Logo tidak muncul? Cek di sini
        </a>
      </div>

      <?php
        $logo = Setting::get('site_logo');
        $favicon = Setting::get('site_favicon');
      ?>

      
      <div class="mb-4">
        <label class="form-label small fw-medium text-dark">Pilih dari Galeri Logo</label>
        <p class="text-muted mb-2" style="font-size:11px">Klik "Pakai" pada varian yang kamu mau -- langsung aktif, tidak perlu upload file sendiri.</p>

        <?php $__currentLoopData = $brandingPresetGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $groupKey => $groupLabel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <?php
            $defaultTarget = $groupKey === 'favicon' ? 'site_favicon' : ($groupKey === 'icon' ? 'site_icon' : 'site_logo');
          ?>
          <p class="fw-medium text-dark mb-2 mt-3" style="font-size:12px"><?php echo e($groupLabel); ?></p>
          <div class="d-flex flex-wrap gap-2 mb-2">
            <?php $__currentLoopData = $brandingPresetColors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $colorKey => $colorLabel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <div class="border rounded-3 p-2 text-center preset-swatch" style="width:104px">
                <img src="<?php echo e(route('admin.settings.general.preset-image', [$groupKey, $colorKey])); ?>" alt="<?php echo e($colorLabel); ?>" loading="lazy" class="mb-1" style="width:100%;height:36px;object-fit:contain;background:#f8fafc;border-radius:.25rem">
                <p class="text-muted mb-1" style="font-size:9px"><?php echo e($colorLabel); ?></p>
                <select class="form-select form-select-sm preset-target-select mb-1" style="font-size:9px;padding:.1rem .25rem">
                  <option value="site_logo" <?php if($defaultTarget === 'site_logo'): echo 'selected'; endif; ?>>Logo Utama</option>
                  <option value="site_icon" <?php if($defaultTarget === 'site_icon'): echo 'selected'; endif; ?>>Ikon Kecil</option>
                  <option value="site_favicon" <?php if($defaultTarget === 'site_favicon'): echo 'selected'; endif; ?>>Favicon</option>
                </select>
                <button type="button" class="btn btn-outline-secondary w-100 use-preset-btn"
                        style="font-size:9px;padding:.15rem .3rem"
                        data-group="<?php echo e($groupKey); ?>"
                        data-color="<?php echo e($colorKey); ?>"
                        data-preset-label="<?php echo e($groupLabel); ?> - <?php echo e($colorLabel); ?>">
                  Pakai
                </button>
              </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <input type="hidden" id="presetCsrfToken" value="<?php echo e(csrf_token()); ?>">
        <p id="presetStatus" class="mb-0 mt-2" style="font-size:11px;min-height:14px"></p>
      </div>

      <div class="d-flex align-items-center gap-2 my-4">
        <hr class="flex-grow-1 m-0">
        <span class="text-muted" style="font-size:11px">atau upload logo sendiri</span>
        <hr class="flex-grow-1 m-0">
      </div>

      <div class="mb-3">
        <label class="form-label small fw-medium text-dark">Logo</label>
        <?php if($logo): ?>
          <div class="d-flex align-items-center gap-3 mb-2">
            <img src="<?php echo e(route('branding.file', $logo)); ?>" alt="Logo" class="rounded-3" style="height:40px;background:#f1f5f9;padding:6px 12px;object-fit:contain">
            <label class="d-flex align-items-center gap-2 text-danger" style="font-size:12px">
              <input type="checkbox" name="remove_site_logo" value="1" class="form-check-input" style="margin-top:0">
              Hapus logo
            </label>
          </div>
        <?php endif; ?>
        <input type="file" name="site_logo" accept="image/*" class="form-control form-control-sm">
        <?php $__errorArgs = ['site_logo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        <p class="text-muted mt-1 mb-0" style="font-size:11px">
          PNG/SVG dengan latar transparan, tinggi ideal 40–60px, maks 1 MB.
          Kalau kosong, nama situs ditampilkan sebagai teks.
        </p>
      </div>

      <div class="mb-3">
        <label class="form-label small fw-medium text-dark">Tampilan di Sebelah Logo</label>
        <?php $brandingDisplay = Setting::get('branding_display', 'logo_and_text'); ?>
        <div class="row g-2" id="brandingDisplayGroup">
          <?php $__currentLoopData = ['logo_and_text' => 'Logo + Nama', 'logo_only' => 'Logo Saja', 'text_only' => 'Nama Saja']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-4">
              <label class="d-flex align-items-center justify-content-center rounded-3 border px-2 py-2 text-center small fw-medium w-100"
                     style="cursor:pointer;<?php echo e($brandingDisplay === $key ? 'border-color:#4f46e5!important;background:rgba(79,70,229,.06);color:#4338ca' : ''); ?>">
                <input type="radio" name="branding_display" value="<?php echo e($key); ?>" <?php if($brandingDisplay === $key): echo 'checked'; endif; ?> class="d-none" data-branding-radio>
                <?php echo e($label); ?>

              </label>
            </div>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <p class="text-muted mt-1 mb-0" style="font-size:11px">
          Berlaku di panel admin dan halaman login. "Logo Saja" cocok kalau logomu sudah memuat nama merek di dalam gambarnya.
        </p>
      </div>

      <div class="mb-3">
        <label class="form-label small fw-medium text-dark">Favicon</label>
        <?php if($favicon): ?>
          <div class="d-flex align-items-center gap-3 mb-2">
            <img src="<?php echo e(route('branding.file', $favicon)); ?>" alt="Favicon" class="rounded-2" style="width:32px;height:32px;background:#f1f5f9;padding:4px;object-fit:contain">
            <label class="d-flex align-items-center gap-2 text-danger" style="font-size:12px">
              <input type="checkbox" name="remove_site_favicon" value="1" class="form-check-input" style="margin-top:0">
              Hapus favicon
            </label>
          </div>
        <?php endif; ?>
        <input type="file" name="site_favicon" accept="image/*" class="form-control form-control-sm">
        <?php $__errorArgs = ['site_favicon'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        <p class="text-muted mt-1 mb-0" style="font-size:11px">Ikon di tab browser. PNG 32×32 atau 64×64, maks 256 KB.</p>
      </div>

      <div>
        <label class="form-label small fw-medium text-dark">Warna Tema</label>
        <div class="d-flex align-items-center gap-3">
          <input type="color" name="theme_color" value="<?php echo e(old('theme_color', Setting::get('theme_color', '#6366F1'))); ?>"
                 class="rounded-3 border" style="width:56px;height:40px;cursor:pointer;padding:2px">
          <span class="text-muted" style="font-size:12px">Dipakai untuk tombol dan aksen di halaman publik.</span>
        </div>
        <?php $__errorArgs = ['theme_color'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      </div>
    </div>

    
    <div class="pt-3 mt-3 border-top">
      <h2 class="small fw-bold text-dark mb-3">Template Tampilan</h2>

      <div class="row g-3">
        <div class="col-sm-6">
          <label class="form-label small fw-medium text-dark">Template Halaman Publik</label>
          <select name="public_template" class="form-select form-select-sm">
            <?php $__currentLoopData = \App\Support\ThemeRegistry::available('public'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $theme): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <option value="<?php echo e($key); ?>" <?php if(old('public_template', Setting::get('public_template', 'default')) === $key): echo 'selected'; endif; ?>><?php echo e($theme['label']); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </select>
          <?php $__errorArgs = ['public_template'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        <div class="col-sm-6">
          <label class="form-label small fw-medium text-dark">Template Panel Client</label>
          <select name="client_template" class="form-select form-select-sm">
            <?php $__currentLoopData = \App\Support\ThemeRegistry::available('client'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $theme): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <option value="<?php echo e($key); ?>" <?php if(old('client_template', Setting::get('client_template', 'default')) === $key): echo 'selected'; endif; ?>><?php echo e($theme['label']); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </select>
          <?php $__errorArgs = ['client_template'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
      </div>
      <p class="text-muted mt-2 mb-0" style="font-size:11px">Template terdeteksi otomatis dari folder resources/views/themes/*-themes/.</p>
    </div>

    <button type="submit" class="btn btn-primary btn-sm mt-3"><i class="fa-solid fa-check" style="font-size:11px"></i> Simpan Pengaturan</button>
  </form>

  <script <?php echo 'nonce="' . e(app(\App\Support\CspNonce::class)->value()) . '"'; ?>>
    document.querySelectorAll('[data-branding-radio]').forEach(function (radio) {
      radio.addEventListener('change', function () {
        document.querySelectorAll('[data-branding-radio]').forEach(function (r) {
          const label = r.closest('label');
          if (r.checked) {
            label.style.borderColor = '#4f46e5';
            label.style.background = 'rgba(79,70,229,.06)';
            label.style.color = '#4338ca';
          } else {
            label.style.borderColor = '';
            label.style.background = '';
            label.style.color = '';
          }
        });
      });
    });

    document.querySelectorAll('.use-preset-btn').forEach(function (btn) {
      btn.addEventListener('click', function () {
        const status = document.getElementById('presetStatus');
        const swatch = btn.closest('.preset-swatch');
        const targetSelect = swatch.querySelector('.preset-target-select');
        const original = btn.textContent;
        btn.disabled = true;
        btn.textContent = '...';
        status.textContent = '';

        fetch('<?php echo e(route('admin.settings.general.preset-branding')); ?>', {
          method: 'POST',
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.getElementById('presetCsrfToken').value,
          },
          body: new URLSearchParams({
            group: btn.dataset.group,
            color: btn.dataset.color,
            target: targetSelect.value,
          }),
        })
          .then(function (res) {
            return res.json().then(function (body) {
              if (! res.ok) throw new Error(body.message || ('HTTP ' + res.status));
              return body;
            });
          })
          .then(function (body) {
            status.textContent = '✓ ' + (body.message || 'Berhasil disimpan.') + ' Memuat ulang halaman...';
            status.style.color = '#15803d';
            setTimeout(() => window.location.reload(), 700);
          })
          .catch(function (err) {
            status.textContent = 'Gagal: ' + err.message;
            status.style.color = '#b91c1c';
            btn.disabled = false;
            btn.textContent = original;
          });
      });
    });
  </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/admin/settings/general.blade.php ENDPATH**/ ?>