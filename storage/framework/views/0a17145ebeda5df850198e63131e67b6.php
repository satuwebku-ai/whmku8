<?php $__env->startSection('title', 'Fraud Review Affiliate'); ?>

<?php $__env->startSection('content'); ?>
  <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <div>
      <h1 class="h4 fw-bold text-dark mb-1">Fraud Review Affiliate</h1>
      <p class="small text-muted mb-0">Tinjau referral dan konversi yang memiliki sinyal risiko sebelum komisi dicairkan.</p>
    </div>
    <a href="<?php echo e(route('admin.affiliate.index')); ?>" class="btn btn-outline-secondary btn-sm">&larr; Program Affiliate</a>
  </div>

  <div class="card border rounded-4 overflow-hidden">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead>
          <tr class="small text-uppercase text-muted" style="background:#f8fafc">
            <th class="px-4 py-3">Affiliate</th>
            <th class="py-3">Client</th>
            <th class="py-3">Invoice</th>
            <th class="py-3">Alasan / Sinyal</th>
            <th class="py-3">Status</th>
            <th class="text-end px-4 py-3">Review</th>
          </tr>
        </thead>
        <tbody>
          <?php $__empty_1 = true; $__currentLoopData = $flags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $flag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
              <td class="px-4 py-3">
                <a href="<?php echo e(route('admin.affiliate.show', $flag->affiliate)); ?>" style="font-family:monospace"><?php echo e($flag->affiliate->code); ?></a>
                <br><span class="small text-muted"><?php echo e($flag->affiliate->client?->name); ?></span>
              </td>
              <td class="py-3"><?php echo e($flag->client?->name); ?><br><span class="small text-muted"><?php echo e($flag->client?->email); ?></span></td>
              <td class="py-3"><?php echo e($flag->invoice?->invoice_number ?? $flag->conversion?->invoice?->invoice_number ?? '—'); ?></td>
              <td class="py-3 small">
                <div><?php echo e($flag->reason); ?></div>
                <span class="text-muted"><?php echo e(collect($flag->signals ?? [])->keys()->implode(', ')); ?></span>
              </td>
              <td class="py-3"><span class="badge badge-soft-warning"><?php echo e(ucfirst($flag->status)); ?></span></td>
              <td class="text-end px-4 py-3">
                <form method="POST" action="<?php echo e(route('admin.affiliate.fraud.review', $flag)); ?>" class="d-inline-flex gap-1">
                  <?php echo csrf_field(); ?>
                  <input type="hidden" name="status" value="approved">
                  <button type="submit" class="btn btn-success btn-sm">Approve</button>
                </form>
                <form method="POST" action="<?php echo e(route('admin.affiliate.fraud.review', $flag)); ?>" class="d-inline-flex gap-1">
                  <?php echo csrf_field(); ?>
                  <input type="hidden" name="status" value="rejected">
                  <button type="submit" class="btn btn-outline-danger btn-sm">Reject</button>
                </form>
                <form method="POST" action="<?php echo e(route('admin.affiliate.fraud.review', $flag)); ?>" class="d-inline-flex gap-1">
                  <?php echo csrf_field(); ?>
                  <input type="hidden" name="status" value="blocked">
                  <button type="submit" class="btn btn-outline-dark btn-sm">Block</button>
                </form>
              </td>
            </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr><td colspan="6" class="text-center text-muted py-5">Tidak ada item fraud yang menunggu review.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
    <?php if($flags->hasPages()): ?>
      <div class="px-4 py-3 border-top"><?php echo e($flags->links('pagination.bootstrap')); ?></div>
    <?php endif; ?>
  </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/admin/affiliate/fraud.blade.php ENDPATH**/ ?>