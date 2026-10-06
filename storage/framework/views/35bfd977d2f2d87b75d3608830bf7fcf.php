<?php $__env->startSection('title', $page->exists ? 'Edit Halaman' : 'Tambah Halaman'); ?>

<?php $__env->startSection('content'); ?>

  <?php echo $__env->make('admin.pages._nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

  <div class="mb-4">
    <h1 class="h4 fw-bold text-dark mb-1"><?php echo e($page->exists ? 'Edit Halaman' : 'Tambah Halaman'); ?></h1>
    <?php if($page->exists): ?>
      <p class="small text-muted mb-0">
        URL: <a href="<?php echo e(route('page.show', $page->slug)); ?>" target="_blank" class="text-accent"><?php echo e($page->url); ?></a>
      </p>
    <?php endif; ?>
  </div>

  <form method="POST" action="<?php echo e($page->exists ? route('admin.page.update', $page) : route('admin.page.add')); ?>" class="row g-3" style="max-width:70rem">
    <?php echo csrf_field(); ?>

    <div class="col-12 col-lg-8">
      <div class="card border rounded-4 p-4 mb-3">
        <div class="mb-3">
          <label class="form-label small fw-medium text-dark">Judul Halaman</label>
          <input type="text" name="title" id="titleInput" value="<?php echo e(old('title', $page->title)); ?>" class="form-control form-control-sm" required>
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
          <div class="d-flex align-items-center gap-2">
            <span class="text-muted flex-shrink-0" style="font-size:12px"><?php echo e(url('/')); ?>/</span>
            <input type="text" name="slug" id="slugInput" value="<?php echo e(old('slug', $page->slug)); ?>" placeholder="otomatis dari judul" class="form-control form-control-sm">
          </div>
          <?php $__errorArgs = ['slug'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          <p class="text-muted mt-1 mb-0" style="font-size:11px">
            Kosongkan untuk dibuat otomatis dari judul. Hati-hati mengubah slug halaman yang sudah terbit — link lama akan mati.
            Beberapa kata seperti "admin", "hosting", "keranjang" tidak bisa dipakai karena sudah menjadi alamat fitur sistem.
          </p>
        </div>

        <div>
          <label class="form-label small fw-medium text-dark">Konten</label>
          <textarea name="content" rows="16" class="form-control form-control-sm" style="font-family:monospace;font-size:12px"><?php echo e(old('content', $page->content)); ?></textarea>
          <?php $__errorArgs = ['content'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          <p class="text-muted mt-1 mb-0" style="font-size:11px">
            Mendukung HTML (<code>&lt;h2&gt;</code>, <code>&lt;p&gt;</code>, <code>&lt;ul&gt;</code>, <code>&lt;a&gt;</code>, dsb).
            Konten ditampilkan apa adanya ke pengunjung, jadi jangan tempel HTML dari sumber yang tidak dipercaya.
          </p>
        </div>
      </div>

      <div class="card border rounded-4 p-4">
        <h2 class="small fw-bold text-dark mb-3">Pengaturan SEO</h2>

        <div class="mb-3">
          <label class="form-label small fw-medium text-dark">Meta Title</label>
          <input type="text" name="meta_title" id="metaTitle" maxlength="70" value="<?php echo e(old('meta_title', $page->meta_title)); ?>" class="form-control form-control-sm" placeholder="Kosongkan untuk memakai judul halaman">
          <p class="text-muted mt-1 mb-0" style="font-size:11px"><span id="metaTitleCount">0</span>/70 karakter — idealnya di bawah 60 supaya tidak terpotong di Google.</p>
          <?php $__errorArgs = ['meta_title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="mb-3">
          <label class="form-label small fw-medium text-dark">Meta Description</label>
          <textarea name="meta_description" id="metaDesc" rows="3" maxlength="170" class="form-control form-control-sm" placeholder="Ringkasan singkat isi halaman untuk hasil pencarian"><?php echo e(old('meta_description', $page->meta_description)); ?></textarea>
          <p class="text-muted mt-1 mb-0" style="font-size:11px"><span id="metaDescCount">0</span>/170 karakter — idealnya 120–155.</p>
          <?php $__errorArgs = ['meta_description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="row g-3 mb-3">
          <div class="col-sm-6">
            <label class="form-label small fw-medium text-dark">Meta Keywords (opsional)</label>
            <input type="text" name="meta_keywords" value="<?php echo e(old('meta_keywords', $page->meta_keywords)); ?>" class="form-control form-control-sm" placeholder="hosting murah, domain id">
            <p class="text-muted mt-1 mb-0" style="font-size:11px">Google mengabaikan tag ini sejak lama; isi hanya kalau butuh untuk mesin pencari lain.</p>
          </div>
          <div class="col-sm-6">
            <label class="form-label small fw-medium text-dark">OG Image URL (opsional)</label>
            <input type="text" name="og_image" value="<?php echo e(old('og_image', $page->og_image)); ?>" class="form-control form-control-sm" placeholder="https://...">
            <p class="text-muted mt-1 mb-0" style="font-size:11px">Gambar saat link dibagikan ke media sosial. Ukuran ideal 1200×630.</p>
          </div>
        </div>

        
        <div class="rounded-3 border p-3" style="background:#f8fafc">
          <p class="fw-bold text-muted mb-2" style="font-size:11px;text-transform:uppercase;letter-spacing:.03em">Pratinjau di Google</p>
          <p class="text-truncate mb-0" style="font-size:13px;color:#15803d"><?php echo e(url('/')); ?>/<span id="previewSlug"><?php echo e($page->slug ?: 'slug-halaman'); ?></span></p>
          <p class="text-truncate mb-0" style="font-size:18px;color:#1a0dab;line-height:1.3" id="previewTitle"><?php echo e($page->seo_title ?: 'Judul Halaman'); ?></p>
          <p class="mb-0" style="font-size:13px;color:#475569;line-height:1.4" id="previewDesc"><?php echo e($page->seo_description ?: 'Deskripsi halaman akan muncul di sini.'); ?></p>
        </div>
      </div>
    </div>

    <div class="col-12 col-lg-4">
      <div class="card border rounded-4 p-4">
        <h2 class="small fw-bold text-dark mb-3">Publikasi</h2>

        <label class="d-flex align-items-center gap-2 small text-dark mb-2">
          <input type="checkbox" name="is_published" value="1" <?php if(old('is_published', $page->is_published ?? true)): echo 'checked'; endif; ?> class="form-check-input" style="margin-top:0">
          Terbitkan halaman
        </label>

        <label class="d-flex align-items-center gap-2 small text-dark mb-2">
          <input type="checkbox" name="show_in_footer" value="1" <?php if(old('show_in_footer', $page->show_in_footer)): echo 'checked'; endif; ?> class="form-check-input" style="margin-top:0">
          Tampilkan link di footer
        </label>

        <label class="d-flex align-items-center gap-2 small text-dark mb-3">
          <input type="checkbox" name="noindex" value="1" <?php if(old('noindex', $page->noindex)): echo 'checked'; endif; ?> class="form-check-input" style="margin-top:0">
          Sembunyikan dari mesin pencari (noindex)
        </label>

        <div class="mb-3">
          <label class="form-label small fw-medium text-dark">Urutan di Footer</label>
          <input type="number" name="sort_order" value="<?php echo e(old('sort_order', $page->sort_order ?? 0)); ?>" class="form-control form-control-sm">
        </div>

        <div class="d-flex flex-column gap-2 pt-2 border-top">
          <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-check" style="font-size:11px"></i> Simpan Halaman</button>
          <a href="<?php echo e(route('admin.pages')); ?>" class="btn btn-outline-secondary btn-sm">Batal</a>
        </div>
      </div>
    </div>
  </form>

  <script <?php echo 'nonce="' . e(app(\App\Support\CspNonce::class)->value()) . '"'; ?>>
    (function () {
      const title     = document.getElementById('titleInput');
      const slug      = document.getElementById('slugInput');
      const metaTitle = document.getElementById('metaTitle');
      const metaDesc  = document.getElementById('metaDesc');

      const pvSlug  = document.getElementById('previewSlug');
      const pvTitle = document.getElementById('previewTitle');
      const pvDesc  = document.getElementById('previewDesc');
      const ctTitle = document.getElementById('metaTitleCount');
      const ctDesc  = document.getElementById('metaDescCount');

      const slugify = (s) => s.toLowerCase().trim()
        .replace(/[^a-z0-9\s-]/g, '')
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-');

      // Slug hanya diisi otomatis kalau halaman baru dan belum disentuh manual,
      // supaya slug halaman lama tidak berubah tanpa sengaja.
      let slugTouched = slug.value.length > 0;
      slug.addEventListener('input', () => { slugTouched = true; });

      function sync() {
        if (!slugTouched) slug.value = slugify(title.value);

        pvSlug.textContent  = slug.value || 'slug-halaman';
        pvTitle.textContent = metaTitle.value || title.value || 'Judul Halaman';
        pvDesc.textContent  = metaDesc.value || 'Deskripsi halaman akan muncul di sini.';
        ctTitle.textContent = metaTitle.value.length;
        ctDesc.textContent  = metaDesc.value.length;
      }

      [title, slug, metaTitle, metaDesc].forEach(el => el.addEventListener('input', sync));
      sync();
    })();
  </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/admin/pages/form.blade.php ENDPATH**/ ?>