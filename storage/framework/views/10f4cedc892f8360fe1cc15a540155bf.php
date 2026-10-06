<?php $__env->startSection('title', 'Banner Promo'); ?>

<?php $__env->startSection('content'); ?>

  <?php echo $__env->make('admin.pages._nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

  <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <div>
      <h1 class="h4 fw-bold text-dark mb-1">Banner Promo</h1>
      <p class="small text-muted mb-0">Tampil di halaman utama situs publik — bisa lebih dari satu, bergantian.</p>
    </div>
    <a href="<?php echo e(route('admin.promo-banners.create')); ?>" class="btn btn-primary">
      <i class="fa-solid fa-plus" style="font-size:12px"></i> Tambah Banner
    </a>
  </div>

  <div class="card border rounded-4 overflow-hidden">
    <div>
      <?php $__empty_1 = true; $__currentLoopData = $banners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $banner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="d-flex align-items-center gap-3 px-4 py-3 border-bottom <?php echo e($banner->is_active ? '' : 'opacity-50'); ?>">

          <div class="d-flex flex-column flex-shrink-0" style="gap:2px">
            <form method="POST" action="<?php echo e(route('admin.promo-banners.move', $banner)); ?>">
              <?php echo csrf_field(); ?>
              <input type="hidden" name="direction" value="up">
              <button type="submit" class="btn btn-link p-0 text-muted d-flex align-items-center justify-content-center" style="width:24px;height:20px" title="Naikkan">
                <i class="fa-solid fa-chevron-up" style="font-size:10px"></i>
              </button>
            </form>
            <form method="POST" action="<?php echo e(route('admin.promo-banners.move', $banner)); ?>">
              <?php echo csrf_field(); ?>
              <input type="hidden" name="direction" value="down">
              <button type="submit" class="btn btn-link p-0 text-muted d-flex align-items-center justify-content-center" style="width:24px;height:20px" title="Turunkan">
                <i class="fa-solid fa-chevron-down" style="font-size:10px"></i>
              </button>
            </form>
          </div>

          <img src="<?php echo e(route('banner.file', $banner->image)); ?>" alt="<?php echo e($banner->title); ?>" class="rounded-3 border flex-shrink-0" style="width:96px;height:56px;object-fit:cover">

          <div class="flex-grow-1 min-w-0">
            <p class="small fw-medium text-dark mb-0"><?php echo e($banner->title); ?></p>
            <p class="text-muted text-truncate mb-0" style="font-size:12px">
              <?php echo e($banner->subtitle ?: '—'); ?>

              <?php if($banner->link_url): ?>
                · <i class="fa-solid fa-link" style="font-size:9px"></i> <?php echo e($banner->link_url); ?>

              <?php endif; ?>
            </p>
            <?php if($banner->starts_at || $banner->ends_at): ?>
              <p class="text-muted mb-0 mt-1" style="font-size:11px">
                <i class="fa-regular fa-calendar" style="font-size:9px"></i>
                Tayang: <?php echo e($banner->starts_at?->format('d M Y') ?? 'sekarang'); ?> — <?php echo e($banner->ends_at?->format('d M Y') ?? 'tanpa batas'); ?>

              </p>
            <?php endif; ?>
          </div>

          <div class="d-flex align-items-center gap-2 flex-shrink-0">
            <form method="POST" action="<?php echo e(route('admin.promo-banners.status')); ?>">
              <?php echo csrf_field(); ?>
              <input type="hidden" name="banner_id" value="<?php echo e($banner->id); ?>">
              <button type="submit" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center justify-content-center" style="width:32px;height:32px;padding:0"
                      title="<?php echo e($banner->is_active ? 'Sembunyikan' : 'Tampilkan'); ?>">
                <i class="fa-solid <?php echo e($banner->is_active ? 'fa-eye' : 'fa-eye-slash'); ?>" style="font-size:12px"></i>
              </button>
            </form>
            <a href="<?php echo e(route('admin.promo-banners.edit', $banner)); ?>" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center justify-content-center" style="width:32px;height:32px;padding:0">
              <i class="fa-regular fa-pen-to-square" style="font-size:12px"></i>
            </a>
            <form method="POST" action="<?php echo e(route('admin.promo-banners.destroy', $banner)); ?>"
                  data-confirm="Hapus banner &quot;<?php echo e($banner->title); ?>&quot;?" data-confirm-title="Hapus Banner" data-confirm-style="danger" data-confirm-label="Ya, Hapus">
              <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
              <button type="submit" class="btn btn-outline-danger btn-sm d-inline-flex align-items-center justify-content-center" style="width:32px;height:32px;padding:0">
                <i class="fa-regular fa-trash-can" style="font-size:12px"></i>
              </button>
            </form>
          </div>
        </div>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="text-center py-5">
          <p class="small text-dark mb-1">Belum ada banner promo.</p>
          <p class="text-muted mb-0" style="font-size:12px">Halaman utama situs publik akan tampil tanpa banner sampai kamu menambahkan satu.</p>
        </div>
      <?php endif; ?>
    </div>
  </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/admin/promo-banners/index.blade.php ENDPATH**/ ?>