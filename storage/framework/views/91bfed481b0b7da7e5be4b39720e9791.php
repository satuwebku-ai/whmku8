<?php $__env->startSection('title', 'Detail Invoice ' . $invoice->invoice_number); ?>

<?php $__env->startSection('content'); ?>

  <?php $displayStatus = $invoice->is_overdue ? 'overdue' : $invoice->status; ?>

  <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <div>
      <a href="<?php echo e(route('admin.invoices')); ?>" class="text-decoration-none text-muted" style="font-size:12px"><i class="fa-solid fa-arrow-left"></i> Kembali ke Invoice</a>
      <h1 class="h4 fw-bold text-dark mt-1 mb-0"><?php echo e($invoice->invoice_number); ?></h1>
    </div>
    <?php
      $badgeMap = ['paid' => 'badge-soft-success', 'unpaid' => 'badge-soft-warning', 'overdue' => 'badge-soft-danger', 'cancelled' => 'badge-soft-secondary'];
    ?>
    <span class="badge <?php echo e($badgeMap[$displayStatus] ?? 'badge-soft-secondary'); ?>" style="font-size:13px;padding:.4rem .8rem">
      <?php echo e($displayStatus === 'overdue' ? 'Overdue' : ucfirst($displayStatus)); ?>

    </span>
    <a href="<?php echo e(route('admin.invoices.pdf', $invoice)); ?>" class="btn btn-outline-secondary btn-sm">
      <i class="fa-solid fa-file-pdf"></i> Unduh PDF
    </a>
  </div>

  <div class="row g-3">
    <div class="col-12 col-lg-8">

      <div class="card border rounded-4 p-4 mb-3">
        <h2 class="small fw-bold text-dark mb-3">Rincian Invoice</h2>
        <div class="row g-3 small mb-3">
          <div class="col-sm-6">
            <p class="text-muted mb-1" style="font-size:11px">KLIEN</p>
            <p class="fw-medium text-dark mb-0"><?php echo e($invoice->client->name ?? '—'); ?></p>
          </div>
          <div class="col-sm-6">
            <p class="text-muted mb-1" style="font-size:11px">ORDER TERKAIT</p>
            <p class="fw-medium text-dark mb-0">
              <?php if($invoice->order): ?>
                <a href="<?php echo e(route('admin.orders.details', $invoice->order)); ?>" class="text-decoration-none text-accent">#<?php echo e($invoice->order->order_number); ?></a>
              <?php else: ?>
                —
              <?php endif; ?>
            </p>
          </div>
          <div class="col-sm-6">
            <p class="text-muted mb-1" style="font-size:11px">TANGGAL TERBIT</p>
            <p class="fw-medium text-dark mb-0"><?php echo e($invoice->issue_date->format('d M Y')); ?></p>
          </div>
          <div class="col-sm-6">
            <p class="text-muted mb-1" style="font-size:11px">JATUH TEMPO</p>
            <p class="fw-medium text-dark mb-0"><?php echo e($invoice->due_date->format('d M Y')); ?></p>
          </div>
          <div class="col-sm-6">
            <p class="text-muted mb-1" style="font-size:11px">METODE PEMBAYARAN</p>
            <p class="fw-medium text-dark mb-0"><?php echo e($invoice->payment_method ?? '—'); ?></p>
          </div>
          <div class="col-sm-6">
            <p class="text-muted mb-1" style="font-size:11px">DIBAYAR PADA</p>
            <p class="fw-medium text-dark mb-0"><?php echo e($invoice->paid_at?->format('d M Y') ?? '—'); ?></p>
          </div>
        </div>

        <div class="border-top pt-3 small">
          <div class="d-flex justify-content-between mb-1"><span class="text-muted">Subtotal</span><span class="text-dark">Rp <?php echo e(number_format($invoice->amount, 0, ',', '.')); ?></span></div>
          <div class="d-flex justify-content-between mb-2"><span class="text-muted">Pajak</span><span class="text-dark">Rp <?php echo e(number_format($invoice->tax, 0, ',', '.')); ?></span></div>
          <div class="d-flex justify-content-between fw-bold text-dark border-top pt-2" style="font-size:15px"><span>Total</span><span>Rp <?php echo e(number_format($invoice->total, 0, ',', '.')); ?></span></div>
        </div>
      </div>

      <?php if($invoice->items->isNotEmpty()): ?>
        <div class="card border rounded-4 p-4 mb-3">
          <h2 class="small fw-bold text-dark mb-2">Item Pesanan</h2>
          <?php $__currentLoopData = $invoice->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lineItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="d-flex align-items-center justify-content-between py-2 small border-bottom">
              <div>
                <p class="text-dark mb-0"><?php echo e($lineItem->description); ?></p>
                <?php if($lineItem->order): ?>
                  <a href="<?php echo e(route('admin.orders.details', $lineItem->order)); ?>" class="text-decoration-none text-accent" style="font-size:11px">
                    #<?php echo e($lineItem->order->order_number); ?> · <?php echo e(ucfirst(str_replace('_', ' ', $lineItem->order->status->value))); ?>

                  </a>
                <?php endif; ?>
              </div>
              <span class="fw-medium text-dark">Rp <?php echo e(number_format($lineItem->amount, 0, ',', '.')); ?></span>
            </div>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
      <?php endif; ?>

      <div class="card border rounded-4 p-4">
        <h2 class="small fw-bold text-dark mb-2">Catatan</h2>
        <form method="POST" action="<?php echo e(route('admin.invoice.notes')); ?>">
          <?php echo csrf_field(); ?>
          <input type="hidden" name="invoice_id" value="<?php echo e($invoice->id); ?>">
          <textarea name="notes" rows="4" class="form-control form-control-sm" placeholder="Catatan tentang invoice ini..."><?php echo e(old('notes', $invoice->notes)); ?></textarea>
          <button type="submit" class="btn btn-outline-secondary btn-sm mt-2"><i class="fa-solid fa-floppy-disk" style="font-size:11px"></i> Simpan Catatan</button>
        </form>
      </div>
    </div>

    <div class="col-12 col-lg-4">
      <div class="card border rounded-4 p-4">
        <h2 class="small fw-bold text-dark mb-2">Aksi</h2>
        <div class="d-flex flex-column gap-2">
          <?php if($invoice->status !== 'paid'): ?>
            <form method="POST" action="<?php echo e(route('admin.invoice.mark.paid')); ?>">
              <?php echo csrf_field(); ?>
              <input type="hidden" name="invoice_id" value="<?php echo e($invoice->id); ?>">
              <button type="submit" class="btn btn-primary btn-sm w-100 text-start"><i class="fa-solid fa-check" style="font-size:11px"></i> Tandai Lunas</button>
            </form>
          <?php else: ?>
            <form method="POST" action="<?php echo e(route('admin.invoice.mark.unpaid')); ?>">
              <?php echo csrf_field(); ?>
              <input type="hidden" name="invoice_id" value="<?php echo e($invoice->id); ?>">
              <button type="submit" class="btn btn-outline-secondary btn-sm w-100 text-start"><i class="fa-solid fa-rotate-left" style="font-size:11px"></i> Batalkan Status Lunas</button>
            </form>
          <?php endif; ?>
          <?php if($invoice->status !== 'cancelled'): ?>
            <form method="POST" action="<?php echo e(route('admin.invoice.cancel')); ?>" data-confirm="Batalkan invoice ini?" data-confirm-title="Batalkan" data-confirm-style="warn" data-confirm-label="Ya, Batalkan">
              <?php echo csrf_field(); ?>
              <input type="hidden" name="invoice_id" value="<?php echo e($invoice->id); ?>">
              <button type="submit" class="btn btn-outline-danger btn-sm w-100 text-start"><i class="fa-solid fa-xmark" style="font-size:11px"></i> Batalkan Invoice</button>
            </form>
          <?php endif; ?>
          <a href="<?php echo e(route('admin.invoice.edit.page', $invoice)); ?>" class="btn btn-outline-secondary btn-sm w-100 text-start">
            <i class="fa-regular fa-pen-to-square" style="font-size:11px"></i> Edit Data Invoice
          </a>
        </div>
      </div>
    </div>
  </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/admin/invoices/details.blade.php ENDPATH**/ ?>