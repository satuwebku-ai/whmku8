<?php $__env->startSection('title', 'Halaman'); ?>

<?php $__env->startSection('content'); ?>

  <?php echo $__env->make('admin.pages._nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

  <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <div>
      <h1 class="h4 fw-bold text-dark mb-1">Halaman Statis</h1>
      <p class="small text-muted mb-0">Kelola halaman seperti Tentang Kami, Syarat & Ketentuan, Kebijakan Privasi.</p>
    </div>
    <a href="<?php echo e(route('admin.page.add.page')); ?>" class="btn btn-primary">
      <i class="fa-solid fa-plus" style="font-size:12px"></i> Tambah Halaman
    </a>
  </div>

  <div class="card border rounded-4 overflow-hidden">
    <form method="GET" class="px-4 py-3 border-bottom d-flex flex-wrap align-items-center gap-2">
      <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari judul halaman..." class="form-control form-control-sm" style="max-width:16rem;flex:1 1 180px">
      <button type="submit" class="btn btn-outline-secondary btn-sm" style="width:fit-content">Cari</button>
      <?php if(request('search')): ?>
        <a href="<?php echo e(url()->current()); ?>" class="btn btn-outline-secondary btn-sm" style="width:fit-content">Reset</a>
      <?php endif; ?>
    </form>

    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead>
          <tr class="small text-uppercase text-muted" style="background:#f8fafc">
            <th class="px-4 py-3">Judul</th>
            <th class="py-3">URL</th>
            <th class="py-3">SEO</th>
            <th class="py-3">Footer</th>
            <th class="py-3">Status</th>
            <th class="text-end px-4 py-3">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php $__empty_1 = true; $__currentLoopData = $pages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
              <td class="px-4 py-3 fw-medium text-dark"><?php echo e($page->title); ?></td>
              <td class="text-muted py-3" style="font-size:12px">
                <a href="<?php echo e(route('page.show', $page->slug)); ?>" target="_blank" class="text-decoration-none text-muted">/<?php echo e($page->slug); ?></a>
              </td>
              <td class="py-3">
                <?php if($page->meta_description): ?>
                  <span class="badge badge-soft-success">Lengkap</span>
                <?php else: ?>
                  <span class="badge badge-soft-warning">Belum diisi</span>
                <?php endif; ?>
                <?php if($page->noindex): ?>
                  <span class="badge badge-soft-danger ms-1">noindex</span>
                <?php endif; ?>
              </td>
              <td class="text-muted py-3"><?php echo e($page->show_in_footer ? 'Ya' : '—'); ?></td>
              <td class="py-3">
                <span class="badge <?php echo e($page->is_published ? 'badge-soft-success' : 'badge-soft-secondary'); ?>"><?php echo e($page->is_published ? 'Terbit' : 'Draf'); ?></span>
              </td>
              <td class="text-end px-4 py-3">
                <div class="d-flex align-items-center justify-content-end gap-2">
                  <form method="POST" action="<?php echo e(route('admin.page.status')); ?>">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="page_id" value="<?php echo e($page->id); ?>">
                    <button type="submit" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center justify-content-center" style="width:32px;height:32px;padding:0" title="<?php echo e($page->is_published ? 'Jadikan draf' : 'Terbitkan'); ?>">
                      <i class="fa-solid <?php echo e($page->is_published ? 'fa-toggle-on' : 'fa-toggle-off'); ?>" style="font-size:12px"></i>
                    </button>
                  </form>
                  <a href="<?php echo e(route('admin.page.edit.page', $page)); ?>" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center justify-content-center" style="width:32px;height:32px;padding:0" title="Edit">
                    <i class="fa-regular fa-pen-to-square" style="font-size:12px"></i>
                  </a>
                  <form method="POST" action="<?php echo e(route('admin.page.delete', $page)); ?>" data-confirm="Hapus halaman ini?" data-confirm-title="Hapus Data" data-confirm-style="danger" data-confirm-label="Ya, Hapus">
                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-outline-danger btn-sm d-inline-flex align-items-center justify-content-center" style="width:32px;height:32px;padding:0" title="Hapus">
                      <i class="fa-regular fa-trash-can" style="font-size:12px"></i>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr><td colspan="6" class="text-center text-muted py-5">Belum ada halaman.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <?php if($pages->hasPages()): ?>
      <div class="px-4 py-3 border-top"><?php echo e($pages->links('pagination.bootstrap')); ?></div>
    <?php endif; ?>
  </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/admin/pages/index.blade.php ENDPATH**/ ?>