<?php $__env->startSection('title', 'Template Balasan'); ?>

<?php $__env->startSection('content'); ?>
  <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
    <div>
      <h1 class="h5 fw-bold mb-1"><i class="fa-solid fa-bolt text-warning"></i> Template Balasan</h1>
      <p class="text-muted small mb-0">Teks support siap pakai untuk Email dan Live Chat. Muncul sebagai pilihan “⚡ Template” saat Anda membalas.</p>
    </div>
    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="collapse" data-bs-target="#tplNew">
      <i class="fa-solid fa-plus"></i> Template Baru
    </button>
  </div>

  <div class="rounded-3 px-3 py-2 mb-3 small" style="background:#eef2ff;border:1px solid #c7d2fe;color:#4338ca">
    <i class="fa-solid fa-robot"></i>
    <b>Terhubung dengan AI chat.</b>
    Template yang diberi centang <b>Dipakai AI</b> dibaca bot sebagai contoh jawaban resmi
    (<?php echo e($aiTotal); ?> template aktif saat ini), dan juga dipakai tombol “✨ Draf AI” saat Anda membalas di Live Chat. AI tidak dipakai di Email.
    <?php if (! ($aiReady)): ?> Kunci API AI belum diisi di <a href="<?php echo e(route('admin.settings.livechat')); ?>">Pengaturan → Live Chat</a>, jadi fitur AI belum jalan. <?php endif; ?>
    Penanda yang terisi otomatis: <code>{nama}</code> <code>{email}</code> <code>{site}</code> <code>{admin}</code>
  </div>

  
  <div id="tplNew" class="collapse <?php echo e($errors->any() ? 'show' : ''); ?>">
    <form method="POST" action="<?php echo e(route('admin.templates.store')); ?>" class="card border rounded-4 p-3 mb-3">
      <?php echo csrf_field(); ?>
      <?php echo $__env->make('admin.templates._fields', ['t' => null, 'categories' => $categories], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
      <div class="mt-3"><button class="btn btn-primary btn-sm">Simpan Template</button></div>
    </form>
  </div>

  
  <form method="GET" class="d-flex flex-wrap gap-2 mb-3">
    <input type="search" name="q" value="<?php echo e($q); ?>" class="form-control form-control-sm" style="max-width:18rem" placeholder="Cari judul atau isi…">
    <select name="category" class="form-select form-select-sm" style="max-width:12rem" data-auto-submit>
      <option value="">Semua kategori</option>
      <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($c); ?>" <?php if($category === $c): echo 'selected'; endif; ?>><?php echo e($c); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
    <button class="btn btn-outline-secondary btn-sm">Cari</button>
  </form>

  <?php $__empty_1 = true; $__currentLoopData = $templates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <div class="card border rounded-4 mb-2 <?php echo e($t->is_active ? '' : 'opacity-75'); ?>">
      <div class="p-3 d-flex flex-wrap align-items-start justify-content-between gap-2">
        <div class="min-w-0">
          <div class="d-flex flex-wrap align-items-center gap-2">
            <b><?php echo e($t->title); ?></b>
            <?php if($t->category): ?><span class="badge text-bg-light border"><?php echo e($t->category); ?></span><?php endif; ?>
            <?php if (! ($t->is_active)): ?><span class="badge text-bg-secondary">Nonaktif</span><?php endif; ?>
            <?php if($t->use_for_ai): ?><span class="badge text-bg-primary">Dipakai AI</span><?php endif; ?>
          </div>
          <p class="text-muted small mb-0 mt-1" style="white-space:pre-line"><?php echo e(\Illuminate\Support\Str::limit($t->body, 180)); ?></p>
        </div>
        <div class="d-flex flex-wrap gap-1">
          <form method="POST" action="<?php echo e(route('admin.templates.toggle', $t)); ?>"><?php echo csrf_field(); ?><input type="hidden" name="field" value="is_active">
            <button class="btn btn-outline-secondary btn-sm"><?php echo e($t->is_active ? 'Nonaktifkan' : 'Aktifkan'); ?></button></form>
          <form method="POST" action="<?php echo e(route('admin.templates.toggle', $t)); ?>"><?php echo csrf_field(); ?><input type="hidden" name="field" value="use_for_ai">
            <button class="btn btn-outline-secondary btn-sm"><?php echo e($t->use_for_ai ? 'Lepas dari AI' : 'Pakai di AI'); ?></button></form>
          <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="collapse" data-bs-target="#tplEdit<?php echo e($t->id); ?>">Ubah</button>
          <form method="POST" action="<?php echo e(route('admin.templates.duplicate', $t)); ?>"><?php echo csrf_field(); ?><button class="btn btn-outline-secondary btn-sm" title="Duplikat"><i class="fa-regular fa-copy"></i></button></form>
          <form method="POST" action="<?php echo e(route('admin.templates.delete', $t)); ?>"
                data-confirm="Hapus template “<?php echo e($t->title); ?>”?" data-confirm-title="Hapus Template" data-confirm-style="danger" data-confirm-label="Ya, Hapus">
            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
            <button class="btn btn-outline-danger btn-sm" title="Hapus"><i class="fa-solid fa-trash"></i></button>
          </form>
        </div>
      </div>
      <div id="tplEdit<?php echo e($t->id); ?>" class="collapse border-top">
        <form method="POST" action="<?php echo e(route('admin.templates.update', $t)); ?>" class="p-3">
          <?php echo csrf_field(); ?>
          <?php echo $__env->make('admin.templates._fields', ['t' => $t, 'categories' => $categories], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
          <div class="mt-3"><button class="btn btn-primary btn-sm">Simpan Perubahan</button></div>
        </form>
      </div>
    </div>
  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <div class="text-center text-muted py-5 small">Belum ada template<?php echo e($q || $category ? ' yang cocok dengan pencarian' : ''); ?>.</div>
  <?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/admin/templates/index.blade.php ENDPATH**/ ?>