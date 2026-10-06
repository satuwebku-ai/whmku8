<?php $__env->startSection('title', 'Commission Rules Affiliate'); ?>

<?php $__env->startSection('content'); ?>
  <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <div>
      <h1 class="h4 fw-bold text-dark mb-1">Commission Rules</h1>
      <p class="small text-muted mb-0">Atur komisi per produk dan jenis pembayaran tanpa mengubah source code.</p>
    </div>
    <a href="<?php echo e(route('admin.affiliate.index')); ?>" class="btn btn-outline-secondary btn-sm">&larr; Program Affiliate</a>
  </div>

  <div class="card border rounded-4 p-4 mb-4">
    <form method="POST" action="<?php echo e(route('admin.affiliate.rules.store')); ?>">
      <?php echo csrf_field(); ?>
      <div class="row g-3">
        <div class="col-md-3"><label class="form-label small">Nama</label><input name="name" class="form-control form-control-sm" required placeholder="Hosting renewal"></div>
        <div class="col-md-2"><label class="form-label small">Produk</label><select name="product_type" class="form-select form-select-sm"><option value="">Semua produk</option><?php $__currentLoopData = ['domain','hosting','vps','reseller_hosting','reseller_domain','ssl','addon','mixed']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($type); ?>"><?php echo e(strtoupper(str_replace('_',' ', $type))); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
        <div class="col-md-2"><label class="form-label small">Event</label><select name="event_type" class="form-select form-select-sm"><?php $__currentLoopData = ['first_order','first_payment','every_payment','renewal','upgrade']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($type); ?>"><?php echo e(ucwords(str_replace('_',' ', $type))); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
        <div class="col-md-2"><label class="form-label small">Tipe</label><select name="commission_type" class="form-select form-select-sm"><option value="percentage">Persentase</option><option value="fixed">Nominal tetap</option></select></div>
        <div class="col-md-2"><label class="form-label small">Nilai</label><input name="commission_value" type="number" step="0.01" min="0" class="form-control form-control-sm" required></div>
        <div class="col-md-1"><label class="form-label small">Prioritas</label><input name="priority" type="number" min="1" value="100" class="form-control form-control-sm" required></div>
      </div>
      <div class="d-flex gap-2 align-items-center mt-3">
        <input name="duration_days" type="number" min="1" max="3650" class="form-control form-control-sm" style="max-width:14rem" placeholder="Durasi hari (opsional)">
        <button class="btn btn-primary btn-sm">Tambah Rule</button>
      </div>
    </form>
  </div>

  <div class="card border rounded-4 overflow-hidden">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead><tr class="small text-uppercase text-muted" style="background:#f8fafc"><th class="px-4 py-3">Rule</th><th>Produk</th><th>Event</th><th>Tarif</th><th>Status</th><th class="text-end px-4">Aksi</th></tr></thead>
        <tbody>
          <?php $__empty_1 = true; $__currentLoopData = $rules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rule): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
              <td class="px-4 py-3"><b><?php echo e($rule->name); ?></b><br><span class="small text-muted">Prioritas <?php echo e($rule->priority); ?></span></td>
              <td><?php echo e(strtoupper($rule->product_type ?: 'semua')); ?></td>
              <td><?php echo e(ucwords(str_replace('_', ' ', $rule->event_type))); ?></td>
              <td><?php echo e($rule->commission_type === 'percentage' ? $rule->commission_value.'%' : 'Rp '.number_format((float) $rule->commission_value, 0, ',', '.')); ?></td>
              <td><span class="badge <?php echo e($rule->is_active ? 'badge-soft-success' : 'badge-soft-secondary'); ?>"><?php echo e($rule->is_active ? 'Aktif' : 'Nonaktif'); ?></span></td>
              <td class="text-end px-4">
                <form method="POST" action="<?php echo e(route('admin.affiliate.rules.toggle', $rule)); ?>" class="d-inline"><?php echo csrf_field(); ?><button class="btn btn-outline-secondary btn-sm"><?php echo e($rule->is_active ? 'Nonaktifkan' : 'Aktifkan'); ?></button></form>
                <form method="POST" action="<?php echo e(route('admin.affiliate.rules.destroy', $rule)); ?>" class="d-inline" data-confirm="Hapus rule ini?"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?><button class="btn btn-outline-danger btn-sm">Hapus</button></form>
              </td>
            </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr><td colspan="6" class="text-center text-muted py-5">Belum ada rule khusus. Sistem memakai default Affiliate Settings.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/admin/affiliate/rules.blade.php ENDPATH**/ ?>