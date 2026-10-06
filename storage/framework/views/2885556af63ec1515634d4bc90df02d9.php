<?php $__env->startSection('title', 'Invoice'); ?>

<?php $__env->startSection('content'); ?>
  <?php
    $badgeMap = ['unpaid' => 'badge-soft-warning', 'paid' => 'badge-soft-success', 'overdue' => 'badge-soft-danger', 'cancelled' => 'badge-soft-secondary'];
  ?>

  <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
    <div>
      <h1 class="h4 fw-bold text-dark mb-1">Invoice</h1>
      <p class="text-muted mb-0">Riwayat tagihan dan pembayaran Anda.</p>
    </div>
    <form method="GET">
      <select name="status" class="form-select form-select-sm" data-auto-submit>
        <option value="">Semua Status</option>
        <option value="unpaid" <?php if(request('status') === 'unpaid'): echo 'selected'; endif; ?>>Belum Bayar</option>
        <option value="paid" <?php if(request('status') === 'paid'): echo 'selected'; endif; ?>>Lunas</option>
        <option value="overdue" <?php if(request('status') === 'overdue'): echo 'selected'; endif; ?>>Terlambat</option>
        <option value="cancelled" <?php if(request('status') === 'cancelled'): echo 'selected'; endif; ?>>Dibatalkan</option>
      </select>
    </form>
  </div>

  <div class="d-flex flex-column gap-3">
    <?php $__empty_1 = true; $__currentLoopData = $invoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
      <div class="dash-card dash-card-hover p-4 d-flex align-items-center justify-content-between gap-3 flex-wrap">
        <div class="d-flex align-items-center gap-3 min-w-0">
          <span class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width:40px;height:40px;background:<?php echo e(in_array($invoice->status, ['unpaid','overdue']) ? 'rgba(180,83,9,.1)' : 'rgba(21,128,61,.1)'); ?>;color:<?php echo e(in_array($invoice->status, ['unpaid','overdue']) ? '#b45309' : '#15803d'); ?>">
            <i class="fa-solid fa-file-invoice" style="font-size:15px"></i>
          </span>
          <div class="min-w-0">
            <a href="<?php echo e(route('client.invoices.show', $invoice)); ?>" class="fw-semibold text-dark text-decoration-none" style="font-size:15px">
              <?php echo e($invoice->invoice_number); ?>

            </a>
            <p class="text-muted mt-1 mb-0" style="font-size:11px">
              Terbit <?php echo e($invoice->issue_date->format('d M Y')); ?> ·
              Jatuh tempo <?php echo e($invoice->due_date->format('d M Y')); ?>

            </p>
          </div>
        </div>

        <div class="d-flex align-items-center gap-3">
          <div class="text-end">
            <p class="fw-bold text-dark mb-0" style="font-size:15px">Rp <?php echo e(number_format($invoice->total, 0, ',', '.')); ?></p>
            <span class="badge <?php echo e($badgeMap[$invoice->is_overdue ? 'overdue' : $invoice->status] ?? 'badge-soft-secondary'); ?>">
              <?php echo e($invoice->is_overdue ? 'Terlambat' : ucfirst($invoice->status)); ?>

            </span>
          </div>

          <?php if(in_array($invoice->status, ['unpaid', 'overdue'])): ?>
            <a href="<?php echo e(route('client.invoices.show', $invoice)); ?>" class="btn btn-theme">
              <i class="fa-solid fa-credit-card" style="font-size:11px"></i> Bayar
            </a>
          <?php else: ?>
            <a href="<?php echo e(route('client.invoices.show', $invoice)); ?>" class="btn btn-outline-secondary">Detail</a>
          <?php endif; ?>
        </div>
      </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
      <div class="dash-card p-5 text-center">
        <span class="rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width:44px;height:44px;background:#f1f5f9;color:#94a3b8">
          <i class="fa-solid fa-file-invoice"></i>
        </span>
        <p class="text-muted mb-0" style="font-size:14px">Belum ada invoice.</p>
      </div>
    <?php endif; ?>
  </div>

  <?php if($invoices->hasPages()): ?>
    <div class="mt-4"><?php echo e($invoices->links('pagination.bootstrap')); ?></div>
  <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('client.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/themes/client-themes/modern/client/invoices/index.blade.php ENDPATH**/ ?>