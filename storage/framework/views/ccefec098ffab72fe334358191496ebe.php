<?php $__env->startSection('title', 'Kategori Produk'); ?>

<?php $__env->startSection('content'); ?>

  <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <div>
      <h1 class="h4 fw-bold text-dark mb-1">Kategori Produk</h1>
      <p class="small text-muted mb-0">Kelompok produk di katalog, mis. Shared Hosting, WordPress Hosting, VPS.</p>
    </div>
    <div class="d-flex align-items-center gap-2">
      <a href="<?php echo e(route('admin.products.index')); ?>" class="btn btn-outline-secondary btn-sm">
        <i class="fa-solid fa-box" style="font-size:11px"></i> Lihat Produk
      </a>
      <a href="<?php echo e(route('admin.product-categories.create')); ?>" class="btn btn-primary btn-sm">
        <i class="fa-solid fa-plus" style="font-size:11px"></i> Tambah Kategori
      </a>
    </div>
  </div>

  <div class="card border rounded-4 overflow-hidden">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead>
          <tr class="small text-uppercase text-muted" style="background:#f8fafc">
            <th class="px-4 py-3">Nama</th>
            <th class="py-3">Slug</th>
            <th class="text-center py-3">Produk</th>
            <th class="py-3">Status</th>
            <th class="text-end px-4 py-3">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php $__empty_1 = true; $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
              <td class="px-4 py-3 fw-medium text-dark">
                <?php if($category->icon): ?><i class="fa-solid <?php echo e($category->icon); ?> text-muted me-1"></i><?php endif; ?>
                <?php echo e($category->name); ?>

              </td>
              <td class="text-muted py-3" style="font-size:12px">
                /<?php echo e($category->urlSection()); ?>/<?php echo e($category->slug); ?>

                <span class="badge <?php echo e(($category->type ?? 'hosting') === 'vps' ? 'badge-soft-success' : 'badge-soft-secondary'); ?> ms-1" style="font-size:9px">
                  <?php echo e(($category->type ?? 'hosting') === 'vps' ? 'VPS' : 'Hosting'); ?>

                </span>
              </td>
              <td class="text-center text-muted py-3"><?php echo e($category->products_count); ?></td>
              <td class="py-3">
                <span class="badge <?php echo e($category->is_active ? 'badge-soft-success' : 'badge-soft-secondary'); ?>"><?php echo e($category->is_active ? 'Aktif' : 'Nonaktif'); ?></span>
              </td>
              <td class="text-end px-4 py-3">
                <div class="d-flex align-items-center justify-content-end gap-2">
                  <a href="<?php echo e(route('admin.product-categories.edit', $category)); ?>" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center justify-content-center" style="width:32px;height:32px;padding:0" title="Edit">
                    <i class="fa-regular fa-pen-to-square" style="font-size:12px"></i>
                  </a>
                  <form method="POST" action="<?php echo e(route('admin.product-categories.destroy', $category)); ?>"
                        data-confirm="Hapus kategori <?php echo e($category->name); ?>?" data-confirm-title="Hapus Kategori"
                        data-confirm-style="danger" data-confirm-label="Ya, Hapus">
                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-outline-danger btn-sm d-inline-flex align-items-center justify-content-center" style="width:32px;height:32px;padding:0" title="Hapus">
                      <i class="fa-regular fa-trash-can" style="font-size:12px"></i>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr><td colspan="5" class="text-center text-muted py-5">Belum ada kategori produk.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
    <?php if($categories->hasPages()): ?>
      <div class="px-4 py-3 border-top"><?php echo e($categories->links('pagination.bootstrap')); ?></div>
    <?php endif; ?>
  </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/admin/product-categories/index.blade.php ENDPATH**/ ?>