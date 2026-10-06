<?php $__env->startSection('title', 'Banner Popup'); ?>
<?php $__env->startSection('content'); ?>

  <?php echo $__env->make('admin.pages._nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

  <?php use App\Models\Setting; ?>

  <div class="mb-4">
    <h1 class="h4 fw-bold text-dark mb-1">Banner Popup</h1>
    <p class="small text-muted mb-0">
      Muncul sebagai jendela di atas halaman depan (Beranda) begitu pengunjung membuka situs — beda dari Banner Promo yang tampil sebagai carousel di dalam halaman.
    </p>
  </div>

  <form method="POST" action="<?php echo e(route('admin.popup-banner.update')); ?>" enctype="multipart/form-data" style="max-width:42rem">
    <?php echo csrf_field(); ?>

    <div class="card border rounded-4 p-4 mb-3">
      <label class="d-flex align-items-center gap-2 small fw-medium text-dark mb-1" style="cursor:pointer;width:fit-content">
        <input type="checkbox" name="popup_banner_enabled" value="1" <?php if(Setting::get('popup_banner_enabled', '0') === '1'): echo 'checked'; endif; ?>
               class="form-check-input" style="margin-top:0">
        Aktifkan banner popup
      </label>
      <p class="text-muted mb-0" style="font-size:11px">Kalau dimatikan, tidak ada popup yang muncul di Beranda sama sekali.</p>
    </div>

    <div class="card border rounded-4 p-4 mb-3">
      <div class="mb-3">
        <label class="form-label small fw-medium text-dark">Gambar</label>
        <?php $currentImage = Setting::get('popup_banner_image'); ?>
        <?php if($currentImage): ?>
          <div class="mb-2 d-flex align-items-center gap-3">
            <img src="<?php echo e(route('branding.file', $currentImage)); ?>" alt="Banner popup" class="rounded-3 border" style="height:80px;object-fit:cover">
            <label class="d-flex align-items-center gap-2 text-danger" style="font-size:12px;cursor:pointer">
              <input type="checkbox" name="remove_popup_banner_image" value="1" class="form-check-input" style="margin-top:0">
              Hapus gambar ini
            </label>
          </div>
        <?php endif; ?>
        <input type="file" name="popup_banner_image" accept="image/png,image/jpeg,image/webp" class="form-control form-control-sm">
        <?php $__errorArgs = ['popup_banner_image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        <p class="text-muted mt-1 mb-0" style="font-size:11px">PNG/JPG/WEBP, maksimal 2 MB. Gambar ditampilkan apa adanya sesuai rasio aslinya (tidak dipotong), tinggi dibatasi 70% tinggi layar. Lebar popup diatur di bagian "Ukuran Popup" di bawah; gunakan gambar berlebar minimal 1000 px agar tetap tajam di ukuran Besar/Sangat Besar.</p>
      </div>

      <div class="mb-3">
        <label class="form-label small fw-medium text-dark">Judul</label>
        <input type="text" name="popup_banner_title" value="<?php echo e(old('popup_banner_title', Setting::get('popup_banner_title'))); ?>" class="form-control form-control-sm" placeholder="Promo Spesial Bulan Ini!">
        <?php $__errorArgs = ['popup_banner_title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      </div>

      <div class="mb-3">
        <label class="form-label small fw-medium text-dark">Deskripsi</label>
        <textarea name="popup_banner_description" rows="3" class="form-control form-control-sm" placeholder="Diskon 20% untuk semua paket hosting, berlaku sampai akhir bulan."><?php echo e(old('popup_banner_description', Setting::get('popup_banner_description'))); ?></textarea>
        <?php $__errorArgs = ['popup_banner_description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      </div>

      <div class="row g-3">
        <div class="col-sm-6">
          <label class="form-label small fw-medium text-dark">Teks Tombol</label>
          <input type="text" name="popup_banner_button_text" value="<?php echo e(old('popup_banner_button_text', Setting::get('popup_banner_button_text', 'Lihat Sekarang'))); ?>" class="form-control form-control-sm">
        </div>
        <div class="col-sm-6">
          <label class="form-label small fw-medium text-dark">Tautan Tombol</label>
          <input type="text" name="popup_banner_link_url" value="<?php echo e(old('popup_banner_link_url', Setting::get('popup_banner_link_url'))); ?>" class="form-control form-control-sm" placeholder="/hosting atau https://...">
          <?php $__errorArgs = ['popup_banner_link_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
      </div>
    </div>

    <div class="card border rounded-4 p-4 mb-3">
      <label class="form-label small fw-medium text-dark">Ukuran Popup</label>
      <?php $size = old('popup_banner_size', Setting::get('popup_banner_size', 'medium')); ?>
      <select name="popup_banner_size" class="form-select" style="padding:.25rem .6rem;font-size:.875rem;border-radius:.375rem;max-width:16rem">
        <option value="small" <?php if($size === 'small'): echo 'selected'; endif; ?>>Kecil (± 450 px)</option>
        <option value="medium" <?php if($size === 'medium'): echo 'selected'; endif; ?>>Sedang (± 580 px)</option>
        <option value="large" <?php if($size === 'large'): echo 'selected'; endif; ?>>Besar (± 740 px)</option>
        <option value="xlarge" <?php if($size === 'xlarge'): echo 'selected'; endif; ?>>Sangat Besar (± 960 px)</option>
      </select>
      <?php $__errorArgs = ['popup_banner_size'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      <p class="text-muted mt-1 mb-0" style="font-size:11px">Lebar maksimum popup di desktop. Di ponsel popup otomatis menyesuaikan lebar layar.</p>
    </div>

    <div class="card border rounded-4 p-4 mb-3">
      <label class="form-label small fw-medium text-dark">Seberapa Sering Muncul</label>
      <?php $freq = old('popup_banner_frequency', Setting::get('popup_banner_frequency', 'once_per_day')); ?>
      <select name="popup_banner_frequency" class="form-select" style="padding:.25rem .6rem;font-size:.875rem;border-radius:.375rem;max-width:16rem">
        <option value="every_visit" <?php if($freq === 'every_visit'): echo 'selected'; endif; ?>>Setiap kali buka halaman</option>
        <option value="once_per_session" <?php if($freq === 'once_per_session'): echo 'selected'; endif; ?>>Sekali per kunjungan (sampai browser ditutup)</option>
        <option value="once_per_day" <?php if($freq === 'once_per_day'): echo 'selected'; endif; ?>>Sekali per hari per pengunjung</option>
      </select>
      <p class="text-muted mt-1 mb-0" style="font-size:11px">
        "Setiap kali buka halaman" cukup mengganggu untuk pengunjung yang sering balik — cuma disarankan untuk pengumuman yang benar-benar penting/mendesak.
      </p>
    </div>

    <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-check" style="font-size:11px"></i> Simpan Pengaturan</button>
  </form>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/admin/popup-banner/edit.blade.php ENDPATH**/ ?>