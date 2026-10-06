<?php $__env->startSection('title', $banner->exists ? 'Edit Banner Promo' : 'Tambah Banner Promo'); ?>

<?php $__env->startSection('content'); ?>

  <a href="<?php echo e(route('admin.promo-banners.index')); ?>" class="text-decoration-none text-muted" style="font-size:12px"><i class="fa-solid fa-arrow-left"></i> Kembali ke Banner Promo</a>
  <h1 class="h4 fw-bold text-dark mt-1 mb-4"><?php echo e($banner->exists ? 'Edit Banner Promo' : 'Tambah Banner Promo'); ?></h1>

  <form method="POST" action="<?php echo e($banner->exists ? route('admin.promo-banners.update', $banner) : route('admin.promo-banners.store')); ?>"
        enctype="multipart/form-data" style="max-width:42rem">
    <?php echo csrf_field(); ?>

    <div class="card border rounded-4 p-4 mb-3">
      <div class="mb-3">
        <label class="form-label small fw-medium text-dark">Judul (opsional)</label>
        <input type="text" name="title" value="<?php echo e(old('title', $banner->title)); ?>" class="form-control form-control-sm" placeholder="Promo Hosting Diskon 30%">
        <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        <p class="text-muted mt-1 mb-0" style="font-size:11px">Kosongkan kalau gambar bannernya sudah punya judul sendiri di dalam gambar -- teks judul di sini akan ditampilkan sebagai overlay di atas gambar.</p>
      </div>
      <div>
        <label class="form-label small fw-medium text-dark">Subjudul (opsional)</label>
        <input type="text" name="subtitle" value="<?php echo e(old('subtitle', $banner->subtitle)); ?>" class="form-control form-control-sm" placeholder="Berlaku sampai akhir bulan ini">
        <?php $__errorArgs = ['subtitle'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      </div>
    </div>

    <div class="card border rounded-4 p-4 mb-3">
      <label class="form-label small fw-medium text-dark">Gambar Banner</label>
      <?php if($banner->image): ?>
        <img src="<?php echo e(route('banner.file', $banner->image)); ?>" alt="<?php echo e($banner->title); ?>" class="w-100 rounded-3 border mb-3" style="max-width:28rem">
      <?php endif; ?>
      <input type="file" name="image" accept="image/*" class="form-control form-control-sm">
      <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      <p class="text-muted mt-1 mb-0" style="font-size:11px">Maksimal 2 MB. Gambar ditampilkan apa adanya sesuai rasio aslinya di halaman publik (tidak dipotong) -- disarankan rasio lebar seperti 16:5 atau 3:1 supaya pas dengan tampilan carousel.</p>
    </div>

    <div class="card border rounded-4 p-4 mb-3">
      <div class="mb-3">
        <label class="form-label small fw-medium text-dark">Tautan Tujuan (opsional)</label>
        <input type="text" name="link_url" value="<?php echo e(old('link_url', $banner->link_url)); ?>" class="form-control form-control-sm" placeholder="/hosting atau https://...">
        <?php $__errorArgs = ['link_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      </div>
      <div class="mb-3">
        <label class="form-label small fw-medium text-dark">Teks Tombol (opsional)</label>
        <input type="text" name="button_text" value="<?php echo e(old('button_text', $banner->button_text)); ?>" class="form-control form-control-sm" placeholder="Lihat Paket">
        <?php $__errorArgs = ['button_text'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      </div>
      <label class="d-flex align-items-center gap-2 small text-dark mb-0">
        <input type="checkbox" name="open_in_new_tab" value="1" <?php if(old('open_in_new_tab', $banner->open_in_new_tab)): echo 'checked'; endif; ?> class="form-check-input" style="margin-top:0">
        Buka di tab baru
      </label>
    </div>

    <div class="card border rounded-4 p-4 mb-3">
      <label class="form-label small fw-medium text-dark mb-2">Jadwal Tayang (opsional — kosongkan supaya tayang terus)</label>
      <div class="row g-3">
        <div class="col-sm-6">
          <label class="text-muted mb-1 d-block" style="font-size:11px">Mulai</label>
          <input type="date" name="starts_at" value="<?php echo e(old('starts_at', $banner->starts_at?->format('Y-m-d'))); ?>" class="form-control form-control-sm">
          <?php $__errorArgs = ['starts_at'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        <div class="col-sm-6">
          <label class="text-muted mb-1 d-block" style="font-size:11px">Sampai</label>
          <input type="date" name="ends_at" value="<?php echo e(old('ends_at', $banner->ends_at?->format('Y-m-d'))); ?>" class="form-control form-control-sm">
          <?php $__errorArgs = ['ends_at'];
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
      <label class="form-label small fw-medium text-dark">Tampil di Halaman</label>
      <select name="display_page" class="form-select" style="padding:.25rem .6rem;font-size:.875rem;border-radius:.375rem;max-width:16rem">
        <?php $__currentLoopData = \App\Models\PromoBanner::PAGES; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <option value="<?php echo e($key); ?>" <?php if(old('display_page', $banner->display_page ?? 'all') === $key): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </select>
      <p class="text-muted mt-1 mb-0" style="font-size:11px">Banner cuma tampil di halaman yang dipilih (atau semua halaman <em>web</em> kalau "Semua Halaman" dipilih). Khusus "Email Transaksional" dan "PDF Invoice" harus dipilih sendiri -- tidak otomatis ikut kalau "Semua Halaman" yang dipilih, supaya banner buat website tidak tiba-tiba nongol di invoice/email.</p>
    </div>

    <div class="card border rounded-4 p-4 mb-3">
      <label class="d-flex align-items-center gap-2 small text-dark mb-0">
        <input type="checkbox" name="is_active" value="1" <?php if(old('is_active', $banner->is_active ?? true)): echo 'checked'; endif; ?> class="form-check-input" style="margin-top:0">
        Aktif (tampil di situs publik)
      </label>
    </div>

    <div class="d-flex align-items-center gap-2">
      <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
      <a href="<?php echo e(route('admin.promo-banners.index')); ?>" class="btn btn-outline-secondary btn-sm">Batal</a>
    </div>
  </form>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/admin/promo-banners/form.blade.php ENDPATH**/ ?>