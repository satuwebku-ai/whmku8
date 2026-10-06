<?php $__env->startSection('title', $announcement->exists ? 'Edit Pengumuman' : 'Buat Pengumuman'); ?>

<?php $__env->startSection('content'); ?>

  <?php echo $__env->make('admin.pages._nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

  <div class="mb-4">
    <h1 class="h4 fw-bold text-dark mb-1"><?php echo e($announcement->exists ? 'Edit Pengumuman' : 'Buat Pengumuman'); ?></h1>
    <?php if($announcement->exists): ?>
      <p class="small text-muted mb-0">
        URL: <a href="<?php echo e(route('announcements.show', $announcement->slug)); ?>" target="_blank" class="text-accent"><?php echo e(route('announcements.show', $announcement->slug)); ?></a>
      </p>
    <?php endif; ?>
  </div>

  <form method="POST" action="<?php echo e($announcement->exists ? route('admin.announcement.update', $announcement) : route('admin.announcement.add')); ?>" class="row g-3" style="max-width:70rem">
    <?php echo csrf_field(); ?>

    <div class="col-12 col-lg-8">
      <div class="card border rounded-4 p-4 mb-3">
        <div class="mb-3">
          <label class="form-label small fw-medium text-dark">Judul</label>
          <input type="text" name="title" id="nameInput" value="<?php echo e(old('title', $announcement->title)); ?>" class="form-control form-control-sm" required>
          <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="mb-3">
          <label class="form-label small fw-medium text-dark">Slug URL</label>
          <input type="text" name="slug" id="slugInput" value="<?php echo e(old('slug', $announcement->slug)); ?>" placeholder="otomatis dari judul" class="form-control form-control-sm">
          <?php $__errorArgs = ['slug'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="mb-3">
          <label class="form-label small fw-medium text-dark">Ringkasan (opsional)</label>
          <textarea name="excerpt" rows="2" maxlength="500" class="form-control form-control-sm" placeholder="Ditampilkan di daftar pengumuman"><?php echo e(old('excerpt', $announcement->excerpt)); ?></textarea>
        </div>

        <div>
          <label class="form-label small fw-medium text-dark">Isi Pengumuman</label>
          <textarea name="content" rows="12" class="form-control form-control-sm" style="font-family:monospace;font-size:12px" required><?php echo e(old('content', $announcement->content)); ?></textarea>
          <?php $__errorArgs = ['content'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          <p class="text-muted mt-1 mb-0" style="font-size:11px">Mendukung HTML dasar.</p>
        </div>
      </div>

      <div class="card border rounded-4 p-4">
        <h2 class="small fw-bold text-dark mb-3">SEO (opsional)</h2>
        <div class="mb-3">
          <label class="form-label small fw-medium text-dark">Meta Title</label>
          <input type="text" name="meta_title" maxlength="70" value="<?php echo e(old('meta_title', $announcement->meta_title)); ?>" class="form-control form-control-sm" placeholder="Kosongkan untuk memakai judul">
        </div>
        <div>
          <label class="form-label small fw-medium text-dark">Meta Description</label>
          <textarea name="meta_description" rows="2" maxlength="170" class="form-control form-control-sm" placeholder="Kosongkan untuk memakai ringkasan"><?php echo e(old('meta_description', $announcement->meta_description)); ?></textarea>
        </div>
      </div>
    </div>

    <div class="col-12 col-lg-4">
      <div class="card border rounded-4 p-4">
        <h2 class="small fw-bold text-dark mb-3">Publikasi</h2>

        <div class="mb-3">
          <label class="form-label small fw-medium text-dark">Kategori</label>
          <select name="category" class="form-select" style="padding:.25rem .6rem;font-size:.875rem;border-radius:.375rem">
            <option value="info" <?php if(old('category', $announcement->category ?? 'info') === 'info'): echo 'selected'; endif; ?>>Info</option>
            <option value="promo" <?php if(old('category', $announcement->category) === 'promo'): echo 'selected'; endif; ?>>Promo</option>
            <option value="maintenance" <?php if(old('category', $announcement->category) === 'maintenance'): echo 'selected'; endif; ?>>Maintenance</option>
            <option value="incident" <?php if(old('category', $announcement->category) === 'incident'): echo 'selected'; endif; ?>>Gangguan</option>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label small fw-medium text-dark">Jadwal Terbit</label>
          <input type="datetime-local" name="published_at"
                 value="<?php echo e(old('published_at', optional($announcement->published_at)->format('Y-m-d\TH:i'))); ?>"
                 class="form-control form-control-sm">
          <p class="text-muted mt-1 mb-0" style="font-size:11px">Kosongkan untuk terbit sekarang. Isi tanggal ke depan untuk menjadwalkan.</p>
        </div>

        <label class="d-flex align-items-center gap-2 small text-dark mb-2">
          <input type="checkbox" name="is_published" value="1" <?php if(old('is_published', $announcement->is_published ?? true)): echo 'checked'; endif; ?> class="form-check-input" style="margin-top:0">
          Terbitkan
        </label>

        <label class="d-flex align-items-center gap-2 small text-dark mb-3">
          <input type="checkbox" name="is_pinned" value="1" <?php if(old('is_pinned', $announcement->is_pinned)): echo 'checked'; endif; ?> class="form-check-input" style="margin-top:0">
          Sematkan di atas
        </label>

        <div class="d-flex flex-column gap-2 pt-2 border-top">
          <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-check" style="font-size:11px"></i> Simpan</button>
          <a href="<?php echo e(route('admin.announcements')); ?>" class="btn btn-outline-secondary btn-sm">Batal</a>
        </div>
      </div>
    </div>
  </form>

  <script <?php echo 'nonce="' . e(app(\App\Support\CspNonce::class)->value()) . '"'; ?>>
    (function () {
      const name = document.getElementById('nameInput');
      const slug = document.getElementById('slugInput');

      const slugify = (s) => s.toLowerCase().trim()
        .replace(/[^a-z0-9\s-]/g, '')
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-');

      let slugTouched = slug.value.length > 0;
      slug.addEventListener('input', () => { slugTouched = true; });
      name.addEventListener('input', () => {
        if (!slugTouched) slug.value = slugify(name.value);
      });
    })();
  </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/admin/announcements/form.blade.php ENDPATH**/ ?>