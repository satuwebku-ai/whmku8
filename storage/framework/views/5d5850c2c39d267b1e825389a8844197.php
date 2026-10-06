<?php $__env->startSection('title', 'Branding cPanel — ' . $server->name); ?>
<?php $__env->startSection('content'); ?>
  <?php
    use App\Models\Setting;
    $siteLogo = Setting::get('site_logo');
  ?>

  <div class="mb-4">
    <a href="<?php echo e(route('admin.servers.index')); ?>" class="text-decoration-none text-muted" style="font-size:12px"><i class="fa-solid fa-arrow-left"></i> Kembali ke Server</a>
    <h1 class="h4 fw-bold text-dark mt-1 mb-1">Branding cPanel</h1>
    <p class="small text-muted mb-0">Ganti logo cPanel (tema Jupiter) di server <b><?php echo e($server->name); ?></b> langsung dari sini, tanpa membuka WHM.</p>
  </div>

  <div class="row g-3">
    <div class="col-12 col-lg-7">
      <form method="POST" action="<?php echo e(route('admin.servers.branding.apply', $server)); ?>" enctype="multipart/form-data" class="card border rounded-4 p-4">
        <?php echo csrf_field(); ?>

        <p class="small fw-medium text-dark mb-2">Sumber logo</p>

        <label class="d-flex align-items-start gap-2 mb-2" style="cursor:pointer">
          <input type="radio" name="source" value="site_logo" class="form-check-input mt-1" <?php if(old('source', 'site_logo') === 'site_logo'): echo 'checked'; endif; ?>>
          <span class="small">
            <b>Pakai logo situs</b> (Pengaturan &rarr; Umum)
            <span class="d-block text-muted" style="font-size:11px">Logo PNG/JPG otomatis dibungkus jadi SVG karena cPanel hanya menerima SVG.</span>
            <?php if($siteLogo): ?>
              <img src="<?php echo e(route('branding.file', $siteLogo)); ?>" alt="Logo situs" style="height:36px;width:auto;margin-top:6px;background:#f1f5f9;padding:4px;border-radius:6px">
            <?php else: ?>
              <span class="d-block text-danger" style="font-size:11px">Logo situs belum diatur.</span>
            <?php endif; ?>
          </span>
        </label>

        <label class="d-flex align-items-start gap-2 mb-3" style="cursor:pointer">
          <input type="radio" name="source" value="upload" class="form-check-input mt-1" <?php if(old('source') === 'upload'): echo 'checked'; endif; ?>>
          <span class="small"><b>Upload file SVG sendiri</b></span>
        </label>

        <div class="ps-4 mb-3">
          <label class="form-label small fw-medium text-dark mb-1">SVG untuk latar terang</label>
          <input type="file" name="logo_light" accept=".svg,image/svg+xml" class="form-control form-control-sm mb-2">
          <label class="form-label small fw-medium text-dark mb-1">SVG untuk latar gelap (opsional)</label>
          <input type="file" name="logo_dark" accept=".svg,image/svg+xml" class="form-control form-control-sm">
          <p class="text-muted mt-1 mb-0" style="font-size:11px">Ukuran anjuran 200&times;100 px. Tentukan width/height di dalam file SVG, kalau tidak logo bisa tampil salah. Maksimal 512 KB.</p>
          <?php $__errorArgs = ['logo_light'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:11px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          <?php $__errorArgs = ['logo_dark'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:11px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="mb-3">
          <label class="form-label small fw-medium text-dark mb-1">Deskripsi logo</label>
          <input type="text" name="description" maxlength="100" value="<?php echo e(old('description', Setting::get('site_name', config('app.name')))); ?>" class="form-control form-control-sm">
          <p class="text-muted mt-1 mb-0" style="font-size:11px">Dibaca screen reader; biasanya nama perusahaan.</p>
        </div>

        <div class="d-flex gap-2">
          <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-cloud-arrow-up" style="font-size:11px"></i> Kirim ke WHM</button>
        </div>
      </form>

      <form method="POST" action="<?php echo e(route('admin.servers.branding.reset', $server)); ?>" class="mt-2"
            data-confirm="Hapus logo kustom dan kembali ke logo cPanel bawaan?" data-confirm-title="Kembalikan Logo Bawaan" data-confirm-style="warn" data-confirm-label="Ya, Kembalikan">
        <?php echo csrf_field(); ?>
        <button type="submit" class="btn btn-outline-secondary btn-sm">Kembalikan ke logo bawaan</button>
      </form>
    </div>

    <div class="col-12 col-lg-5">
      <div class="card border rounded-4 p-4" style="background:#f8fafc">
        <p class="small fw-bold text-dark mb-2"><i class="fa-solid fa-circle-info text-accent"></i> Cara manual di WHM</p>
        <ol class="small text-muted ps-3 mb-3" style="line-height:1.7">
          <li>Login WHM (root atau reseller).</li>
          <li>Cari <b>Customization</b> di menu kiri (bagian cPanel).</li>
          <li>Pilih tema <b>Jupiter</b>.</li>
          <li>Tab <b>Logos</b>: upload logo latar terang &amp; gelap, isi deskripsi, klik <b>Update Logos</b>.</li>
          <li>Tab Colors, Favicon (.ico 32&times;32), dan Links untuk warna menu, ikon tab, dan link bantuan.</li>
        </ol>
        <p class="fw-bold text-dark small mb-1">Perlu diketahui</p>
        <ul class="small text-muted ps-3 mb-0" style="line-height:1.7">
          <li>Hanya tema <b>Jupiter</b>. Akun yang masih memakai tema lain tidak berubah; ganti tema di package akunnya.</li>
          <li>Logo harus <b>SVG</b>.</li>
          <li>Ini mengubah tampilan cPanel, bukan WHM.</li>
          <li>Branding reseller yang sudah ada tidak ikut berubah oleh pengaturan root.</li>
          <li>Tombol di sini memakai API token server; token perlu hak akses root/reseller.</li>
        </ul>
      </div>
    </div>
  </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/admin/servers/branding.blade.php ENDPATH**/ ?>