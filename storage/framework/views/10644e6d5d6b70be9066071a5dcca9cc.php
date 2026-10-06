<?php $__env->startSection('title', 'Pengaturan Halaman Depan'); ?>
<?php $__env->startSection('content'); ?>
  <?php echo $__env->make('admin.settings._nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

  <?php use App\Models\Setting; ?>

  <div class="mb-4 d-flex align-items-center justify-content-between flex-wrap gap-2">
    <div>
      <h1 class="h4 fw-bold text-dark mb-1">Pengaturan Halaman Depan</h1>
      <p class="small text-muted mb-0">Atur berapa banyak item dan section mana saja yang tampil di beranda situs publik.</p>
    </div>
    <a href="<?php echo e(route('home')); ?>" target="_blank" class="btn btn-outline-secondary btn-sm">
      <i class="fa-solid fa-arrow-up-right-from-square" style="font-size:11px"></i> Lihat Beranda
    </a>
  </div>

  <form method="POST" action="<?php echo e(route('admin.settings.homepage.update')); ?>" class="card border rounded-4 p-4" style="max-width:42rem">
    <?php echo csrf_field(); ?>

    <h2 class="small fw-bold text-dark mb-1">Susunan Beranda</h2>
    <p class="text-muted mb-3" style="font-size:12px">
      Seret <i class="fa-solid fa-grip-vertical" style="font-size:10px"></i> untuk mengubah urutan,
      matikan sakelar untuk menyembunyikan. Section yang datanya masih kosong otomatis tidak tampil
      walau sakelarnya menyala.
    </p>

    <input type="hidden" name="section_order" id="sectionOrder" value="<?php echo e(implode(',', $order)); ?>">

    <div id="sectionList" class="d-flex flex-column gap-2 mb-4">
      <?php $__currentLoopData = $order; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php $meta = $sectionMeta[$key]; ?>
        <div class="d-flex align-items-center gap-2 rounded-3 border px-3 py-2 bg-white" draggable="true" data-key="<?php echo e($key); ?>">
          <span class="text-muted" style="cursor:grab;font-size:12px"><i class="fa-solid fa-grip-vertical"></i></span>
          <span class="badge badge-soft-secondary" style="font-size:10px;min-width:1.5rem" data-pos><?php echo e($loop->iteration); ?></span>
          <span class="flex-grow-1 min-w-0">
            <span class="d-block fw-medium text-dark" style="font-size:13px"><?php echo e($meta['label']); ?></span>
            <span class="d-block text-muted" style="font-size:11px">
              <?php echo e($meta['desc']); ?>

              <?php if($meta['empty']): ?>
                <span class="d-block" style="font-size:10px;color:#94a3b8">Tersembunyi otomatis kalau <?php echo e($meta['empty']); ?>.</span>
              <?php endif; ?>
            </span>
          </span>
          <div class="form-check form-switch m-0">
            <input type="checkbox" role="switch" class="form-check-input"
                   name="home_show_<?php echo e($key); ?>" value="1"
                   <?php if(Setting::get('home_show_' . $key, '1') === '1'): echo 'checked'; endif; ?>>
          </div>
        </div>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <h2 class="small fw-bold text-dark mb-3 pt-3 border-top">Jumlah Item per Section</h2>

    <div class="row g-3 mb-4">
      <div class="col-sm-3">
        <label class="form-label small fw-medium text-dark">Paket Hosting</label>
        <input type="number" name="home_featured_limit" min="1" max="12"
               value="<?php echo e(old('home_featured_limit', Setting::get('home_featured_limit', 3))); ?>" class="form-control form-control-sm">
      </div>
      <div class="col-sm-3">
        <label class="form-label small fw-medium text-dark">Paket VPS</label>
        <input type="number" name="home_vps_limit" min="1" max="12"
               value="<?php echo e(old('home_vps_limit', Setting::get('home_vps_limit', 3))); ?>" class="form-control form-control-sm">
      </div>
      <div class="col-sm-3">
        <label class="form-label small fw-medium text-dark">Kategori Layanan</label>
        <input type="number" name="home_categories_limit" min="0" max="24"
               value="<?php echo e(old('home_categories_limit', Setting::get('home_categories_limit', 6))); ?>" class="form-control form-control-sm">
        <p class="text-muted mt-1 mb-0" style="font-size:10px">0 = tanpa batas</p>
      </div>
      <div class="col-sm-3">
        <label class="form-label small fw-medium text-dark">Kabar Terbaru</label>
        <input type="number" name="home_announcements_limit" min="1" max="12"
               value="<?php echo e(old('home_announcements_limit', Setting::get('home_announcements_limit', 3))); ?>" class="form-control form-control-sm">
      </div>
    </div>

    <div class="rounded-3 p-3 mb-3" style="background:#f8fafc;border:1px solid #e2e8f0">
      <div class="d-flex align-items-center justify-content-between mb-2 flex-wrap gap-2">
        <h3 class="fw-bold text-dark mb-0" style="font-size:13px">Banner Promo di Beranda</h3>
        <a href="<?php echo e(route('admin.promo-banners.index')); ?>" class="text-accent text-decoration-none" style="font-size:11px">
          Kelola Banner <i class="fa-solid fa-arrow-right" style="font-size:9px"></i>
        </a>
      </div>

      <?php $tampil = $banners->where('shows_on_home', true); ?>

      <?php if($banners->isEmpty()): ?>
        <p class="text-muted mb-0" style="font-size:11px">
          Belum ada banner sama sekali. Tambahkan lewat <a href="<?php echo e(route('admin.promo-banners.create')); ?>" class="text-accent">Konten &rarr; Banner Promo</a>.
        </p>
      <?php else: ?>
        <?php if($tampil->isEmpty()): ?>
          <p class="mb-2" style="font-size:11px;color:#b45309">
            <i class="fa-solid fa-triangle-exclamation"></i>
            <b>Tidak ada banner yang tampil di beranda saat ini.</b> Alasannya per banner ada di bawah.
          </p>
        <?php else: ?>
          <p class="mb-2" style="font-size:11px;color:#047857">
            <i class="fa-solid fa-circle-check"></i>
            <?php echo e($tampil->count()); ?> banner sedang tampil di beranda.
          </p>
        <?php endif; ?>

        <div class="d-flex flex-column gap-1">
          <?php $__currentLoopData = $banners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="d-flex align-items-start justify-content-between gap-2 rounded-2 px-2 py-1" style="background:#fff;border:1px solid #e2e8f0">
              <div class="min-w-0">
                <span class="fw-medium text-dark" style="font-size:12px"><?php echo e($b['title']); ?></span>
                <span class="text-muted" style="font-size:10px"> &middot; <?php echo e($b['page']); ?></span>
                <?php if($b['reasons']): ?>
                  <span class="d-block" style="font-size:10px;color:#b45309"><?php echo e(implode(' &middot; ', $b['reasons'])); ?></span>
                <?php endif; ?>
              </div>
              <span class="badge flex-shrink-0" style="font-size:9px;background:<?php echo e($b['shows_on_home'] ? '#d1fae5' : '#f1f5f9'); ?>;color:<?php echo e($b['shows_on_home'] ? '#047857' : '#64748b'); ?>">
                <?php echo e($b['shows_on_home'] ? 'Tampil' : 'Tidak tampil'); ?>

              </span>
            </div>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
      <?php endif; ?>
    </div>

    <button type="submit" class="btn btn-primary btn-sm" style="width:fit-content"><i class="fa-solid fa-check" style="font-size:11px"></i> Simpan Pengaturan</button>
  </form>

  <script <?php echo 'nonce="' . e(app(\App\Support\CspNonce::class)->value()) . '"'; ?>>
    // Drag-and-drop urutan section. Urutan final ditulis ke input
    // tersembunyi #sectionOrder sebagai daftar dipisah koma, jadi ikut
    // terkirim dalam submit form biasa -- tidak butuh AJAX.
    (function () {
      const list = document.getElementById('sectionList');
      const orderInput = document.getElementById('sectionOrder');

      if (! list || ! orderInput) return;

      let dragged = null;

      function sync() {
        const keys = Array.from(list.children).map(function (el, i) {
          const pos = el.querySelector('[data-pos]');
          if (pos) pos.textContent = i + 1;
          return el.dataset.key;
        });
        orderInput.value = keys.join(',');
      }

      list.querySelectorAll('[draggable="true"]').forEach(function (row) {
        row.addEventListener('dragstart', function () {
          dragged = row;
          row.style.opacity = '.4';
        });

        row.addEventListener('dragend', function () {
          row.style.opacity = '';
          dragged = null;
          sync();
        });

        row.addEventListener('dragover', function (e) {
          e.preventDefault();

          if (! dragged || dragged === row) return;

          const box = row.getBoundingClientRect();
          const after = (e.clientY - box.top) > (box.height / 2);

          list.insertBefore(dragged, after ? row.nextSibling : row);
        });
      });

      sync();
    })();
  </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/admin/settings/homepage.blade.php ENDPATH**/ ?>