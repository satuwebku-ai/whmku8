<?php $__env->startSection('title', $category->exists ? 'Edit Kategori' : 'Tambah Kategori'); ?>

<?php $__env->startSection('content'); ?>

  <h1 class="h4 fw-bold text-dark mb-4"><?php echo e($category->exists ? 'Edit Kategori' : 'Tambah Kategori Produk'); ?></h1>

  <form method="POST" action="<?php echo e($category->exists ? route('admin.product-categories.update', $category) : route('admin.product-categories.store')); ?>" class="card border rounded-4 p-4" style="max-width:36rem">
    <?php echo csrf_field(); ?>
    <?php if($category->exists): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>

    <div class="mb-3">
      <label class="form-label small fw-medium text-dark">Nama Kategori</label>
      <input type="text" name="name" id="nameInput" value="<?php echo e(old('name', $category->name)); ?>" class="form-control form-control-sm" required placeholder="Shared Hosting">
      <?php $__errorArgs = ['name'];
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
      <input type="text" name="slug" id="slugInput" value="<?php echo e(old('slug', $category->slug)); ?>" class="form-control form-control-sm" placeholder="otomatis dari nama">
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
      <label class="form-label small fw-medium text-dark">Jenis Produk di Kategori Ini</label>
      <select name="type" class="form-select form-select-sm">
        <option value="hosting" <?php if(old('type', $category->type ?? 'hosting') === 'hosting'): echo 'selected'; endif; ?>>Hosting (cPanel/WHM)</option>
        <option value="vps" <?php if(old('type', $category->type) === 'vps'): echo 'selected'; endif; ?>>VPS / Cloud Server</option>
      </select>
      <?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      <p class="text-muted mt-1 mb-0" style="font-size:11px">
        Menentukan isian yang muncul saat membuat produk di kategori ini, server mana yang boleh dipilih, dan awalan URL katalog (/hosting/... atau /vps/...).
        Domain, Lisensi/SSL, dan Addon punya menu sendiri, bukan bagian kategori produk.
        <?php if($category->exists && $category->products()->exists()): ?>
          Jenis hanya bisa diubah jika semua produk di kategori ini cocok dengan jenis barunya.
        <?php endif; ?>
      </p>
    </div>

    <div class="mb-3">
      <label class="form-label small fw-medium text-dark">Deskripsi Singkat</label>
      <textarea name="description" rows="2" maxlength="500" class="form-control form-control-sm" placeholder="Tampil di halaman katalog"><?php echo e(old('description', $category->description)); ?></textarea>
    </div>

    <div class="mb-3">
      <label class="form-label small fw-medium text-dark">Ikon Font Awesome <span class="text-muted fw-normal">(opsional)</span></label>
      <input type="text" name="icon" value="<?php echo e(old('icon', $category->icon)); ?>" class="form-control form-control-sm" placeholder="fa-server">
      <p class="text-muted mt-1 mb-0" style="font-size:11px">Nama class tanpa "fa-solid", mis. <code>fa-server</code>, <code>fa-globe</code>, <code>fa-rocket</code>.</p>
    </div>

    <div class="mb-3">
      <label class="form-label small fw-medium text-dark">Urutan Tampil</label>
      <input type="number" name="sort_order" value="<?php echo e(old('sort_order', $category->sort_order ?? 0)); ?>" class="form-control form-control-sm">
    </div>

    <label class="d-flex align-items-center gap-2 small text-dark mb-3">
      <input type="checkbox" name="is_active" value="1" <?php if(old('is_active', $category->is_active ?? true)): echo 'checked'; endif; ?> class="form-check-input" style="margin-top:0">
      Aktif (tampil di katalog publik)
    </label>

    <div class="d-flex align-items-center gap-2 pt-2 border-top">
      <button type="submit" class="btn btn-primary btn-sm mt-2"><i class="fa-solid fa-check" style="font-size:11px"></i> Simpan</button>
      <a href="<?php echo e(route('admin.product-categories.index')); ?>" class="btn btn-outline-secondary btn-sm mt-2">Batal</a>
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

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/admin/product-categories/form.blade.php ENDPATH**/ ?>