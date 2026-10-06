<?php $__env->startSection('title', 'Kupon Diskon'); ?>

<?php $__env->startSection('content'); ?>

  <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <div>
      <h1 class="h4 fw-bold text-dark mb-1">Kupon Diskon</h1>
      <p class="small text-muted mb-0">Kode promo yang bisa dipakai klien saat checkout.</p>
    </div>
    <a href="<?php echo e(route('admin.coupon.add.page')); ?>" class="btn btn-primary">
      <i class="fa-solid fa-plus" style="font-size:12px"></i> Tambah Kupon
    </a>
  </div>

  <div class="card border rounded-4 overflow-hidden">
    <form method="GET" class="px-4 py-3 border-bottom d-flex flex-wrap align-items-center gap-2">
      <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari kode kupon..." class="form-control form-control-sm" style="max-width:16rem;flex:1 1 180px">
      <button type="submit" class="btn btn-outline-secondary btn-sm" style="width:fit-content">Cari</button>
      <?php if(request('search')): ?>
        <a href="<?php echo e(route('admin.coupons')); ?>" class="btn btn-outline-secondary btn-sm" style="width:fit-content">Reset</a>
      <?php endif; ?>
    </form>

    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead>
          <tr class="small text-uppercase text-muted" style="background:#f8fafc">
            <th class="px-4 py-3">Kode</th>
            <th class="py-3">Nilai</th>
            <th class="py-3">Berlaku Untuk</th>
            <th class="py-3">Min. Transaksi</th>
            <th class="text-center py-3">Terpakai</th>
            <th class="py-3">Berlaku Sampai</th>
            <th class="py-3">Status</th>
            <th class="text-end px-4 py-3">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php $__empty_1 = true; $__currentLoopData = $coupons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $coupon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
              <td class="px-4 py-3 fw-bold text-dark" style="font-family:monospace"><?php echo e($coupon->code); ?></td>
              <td class="text-muted py-3"><?php echo e($coupon->value_label); ?></td>
              <td class="py-3">
                <?php if($coupon->applies_to === 'all'): ?>
                  <span class="badge badge-soft-success">Semua Produk</span>
                <?php else: ?>
                  <span class="badge badge-soft-warning">Terbatas</span>
                <?php endif; ?>
              </td>
              <td class="text-muted py-3">
                <?php echo e($coupon->min_order > 0 ? 'Rp ' . number_format($coupon->min_order, 0, ',', '.') : '—'); ?>

              </td>
              <td class="text-center text-muted py-3">
                <?php echo e($coupon->invoices_count); ?><?php echo e($coupon->usage_limit ? ' / ' . $coupon->usage_limit : ''); ?>

              </td>
              <td class="text-muted py-3"><?php echo e($coupon->expires_at?->format('d M Y') ?? 'Tanpa batas'); ?></td>
              <td class="py-3">
                <span class="badge <?php echo e($coupon->is_active ? 'badge-soft-success' : 'badge-soft-secondary'); ?>"><?php echo e($coupon->is_active ? 'Aktif' : 'Nonaktif'); ?></span>
              </td>
              <td class="text-end px-4 py-3">
                <div class="d-flex align-items-center justify-content-end gap-2">
                  <form method="POST" action="<?php echo e(route('admin.coupon.status')); ?>">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="coupon_id" value="<?php echo e($coupon->id); ?>">
                    <button type="submit" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center justify-content-center" style="width:32px;height:32px;padding:0" title="<?php echo e($coupon->is_active ? 'Nonaktifkan' : 'Aktifkan'); ?>">
                      <i class="fa-solid <?php echo e($coupon->is_active ? 'fa-toggle-on' : 'fa-toggle-off'); ?>" style="font-size:12px"></i>
                    </button>
                  </form>
                  <a href="<?php echo e(route('admin.coupon.edit.page', $coupon)); ?>" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center justify-content-center" style="width:32px;height:32px;padding:0" title="Edit">
                    <i class="fa-regular fa-pen-to-square" style="font-size:12px"></i>
                  </a>
                  <form method="POST" action="<?php echo e(route('admin.coupon.delete', $coupon)); ?>" data-confirm="Hapus kupon <?php echo e($coupon->code); ?>?" data-confirm-title="Hapus Kupon" data-confirm-style="danger" data-confirm-label="Ya, Hapus">
                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-outline-danger btn-sm d-inline-flex align-items-center justify-content-center" style="width:32px;height:32px;padding:0" title="Hapus">
                      <i class="fa-regular fa-trash-can" style="font-size:12px"></i>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr><td colspan="8" class="text-center text-muted py-5">Belum ada kupon.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <?php if($coupons->hasPages()): ?>
      <div class="px-4 py-3 border-top"><?php echo e($coupons->links('pagination.bootstrap')); ?></div>
    <?php endif; ?>
  </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/admin/coupons/index.blade.php ENDPATH**/ ?>