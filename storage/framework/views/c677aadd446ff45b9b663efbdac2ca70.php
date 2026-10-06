<?php $__env->startSection('title', 'Payout Affiliate'); ?>

<?php $__env->startSection('content'); ?>

  <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <div>
      <h1 class="h4 fw-bold text-dark mb-1">Payout Affiliate</h1>
      <p class="small text-muted mb-0">Cairkan permintaan payout ke rekening affiliate.</p>
    </div>
    <a href="<?php echo e(route('admin.affiliate.index')); ?>" class="btn btn-outline-secondary btn-sm">&larr; Program Affiliate</a>
  </div>

  <div class="card border rounded-4 overflow-hidden">
    <form method="GET" class="px-4 py-3 border-bottom d-flex flex-wrap align-items-center gap-2">
      <select name="status" class="form-select form-select-sm" style="max-width:12rem" data-auto-submit>
        <option value="">Semua Status</option>
        <?php $__currentLoopData = ['pending' => 'Pending', 'approved' => 'Disetujui', 'processing' => 'Processing', 'paid' => 'Sudah Dibayar', 'failed' => 'Gagal', 'rejected' => 'Ditolak']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <option value="<?php echo e($val); ?>" <?php if(request('status') === $val): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </select>
    </form>

    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead>
          <tr class="small text-uppercase text-muted" style="background:#f8fafc">
            <th class="px-4 py-3">#</th>
            <th class="py-3">Affiliate</th>
            <th class="py-3">Nominal</th>
            <th class="py-3">Rekening</th>
            <th class="py-3">Status</th>
            <th class="text-end px-4 py-3">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php $__empty_1 = true; $__currentLoopData = $payouts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
              <td class="px-4 py-3"><?php echo e($p->id); ?></td>
              <td class="py-3">
                <a href="<?php echo e(route('admin.affiliate.show', $p->affiliate)); ?>" style="font-family:monospace"><?php echo e($p->affiliate->code); ?></a>
                <br><span class="small text-muted"><?php echo e($p->affiliate->client?->name); ?></span>
              </td>
              <td class="py-3">Rp <?php echo e(number_format($p->amount, 0, ',', '.')); ?></td>
              <td class="py-3 small text-muted"><?php echo e($p->bank_name); ?> — <?php echo e($p->bank_account_number); ?><br>a.n. <?php echo e($p->bank_account_name); ?></td>
              <td class="py-3">
                <?php $pBadge = ['pending' => 'badge-soft-warning', 'approved' => 'badge-soft-info', 'processing' => 'badge-soft-info', 'paid' => 'badge-soft-success', 'failed' => 'badge-soft-danger', 'rejected' => 'badge-soft-danger']; ?>
                <span class="badge <?php echo e($pBadge[$p->status] ?? 'badge-soft-secondary'); ?>"><?php echo e(ucfirst($p->status)); ?></span>
              </td>
              <td class="text-end px-4 py-3">
                <?php if($p->status === 'pending'): ?>
                  <form method="POST" action="<?php echo e(route('admin.affiliate.payouts.approve', $p)); ?>" class="d-inline">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-success btn-sm">Setujui</button>
                  </form>
                  <form method="POST" action="<?php echo e(route('admin.affiliate.payouts.reject', $p)); ?>" class="d-inline" data-confirm="Tolak payout ini?">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="reason" value="Ditolak oleh admin">
                    <button type="submit" class="btn btn-outline-danger btn-sm">Tolak</button>
                  </form>
                <?php elseif($p->status === 'approved'): ?>
                  <form method="POST" action="<?php echo e(route('admin.affiliate.payouts.process', $p)); ?>" class="d-inline">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-outline-primary btn-sm">Process</button>
                  </form>
                  <form method="POST" action="<?php echo e(route('admin.affiliate.payouts.paid', $p)); ?>" class="d-inline">
                    <?php echo csrf_field(); ?>
                    <input type="text" name="transaction_reference" class="form-control form-control-sm d-inline-block" style="width:10rem" placeholder="Ref transfer">
                    <button type="submit" class="btn btn-outline-success btn-sm">Tandai Dibayar</button>
                  </form>
                <?php elseif($p->status === 'processing'): ?>
                  <form method="POST" action="<?php echo e(route('admin.affiliate.payouts.paid', $p)); ?>" class="d-inline">
                    <?php echo csrf_field(); ?>
                    <input type="text" name="transaction_reference" class="form-control form-control-sm d-inline-block" style="width:10rem" placeholder="Ref transfer">
                    <button type="submit" class="btn btn-outline-success btn-sm">Paid</button>
                  </form>
                  <form method="POST" action="<?php echo e(route('admin.affiliate.payouts.failed', $p)); ?>" class="d-inline" data-confirm="Tandai payout gagal dan kembalikan saldo?">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="reason" value="Transfer gagal">
                    <button type="submit" class="btn btn-outline-danger btn-sm">Gagal</button>
                  </form>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr><td colspan="6" class="text-center text-muted py-5">Belum ada permintaan payout.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <?php if($payouts->hasPages()): ?>
      <div class="px-4 py-3 border-top"><?php echo e($payouts->links('pagination.bootstrap')); ?></div>
    <?php endif; ?>
  </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/admin/affiliate/payouts.blade.php ENDPATH**/ ?>