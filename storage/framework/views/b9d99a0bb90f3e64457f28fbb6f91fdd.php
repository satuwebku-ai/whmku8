<?php $__env->startSection('title', 'Detail Order #' . $order->order_number); ?>

<?php $__env->startSection('content'); ?>

  <?php
    // Sama seperti admin.orders.index -- $order->status di-cast ke enum
    // OrderStatus, jadi peta ini HARUS dikunci pakai ->value (string).
    $statusBadge = [
      'draft' => 'badge-soft-secondary',
      'requirements_pending' => 'badge-soft-warning',
      'requirements_review' => 'badge-soft-warning',
      'requirements_rejected' => 'badge-soft-danger',
      'requirements_approved' => 'badge-soft-info',
      'pending' => 'badge-soft-warning',
      'pending_payment' => 'badge-soft-warning',
      'paid' => 'badge-soft-info',
      'provisioning' => 'badge-soft-info',
      'completed' => 'badge-soft-success',
      'failed' => 'badge-soft-danger',
      'cancelled' => 'badge-soft-secondary',
      'expired' => 'badge-soft-secondary',
    ];
    $statusLabel = [
      'draft' => 'Draft',
      'requirements_pending' => 'Menunggu Syarat',
      'requirements_review' => 'Ditinjau Admin',
      'requirements_rejected' => 'Ditolak',
      'requirements_approved' => 'Disetujui',
      'pending' => 'Pending',
      'pending_payment' => 'Menunggu Bayar',
      'paid' => 'Lunas',
      'provisioning' => 'Diproses',
      'completed' => 'Aktif',
      'failed' => 'Gagal',
      'cancelled' => 'Dibatalkan',
      'expired' => 'Kadaluarsa',
    ];
  ?>

  <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <div>
      <a href="<?php echo e(route('admin.orders')); ?>" class="text-decoration-none text-muted" style="font-size:12px"><i class="fa-solid fa-arrow-left"></i> Kembali ke Order</a>
      <h1 class="h4 fw-bold text-dark mt-1 mb-0">Order #<?php echo e($order->order_number); ?></h1>
    </div>
    <span class="badge <?php echo e($statusBadge[$order->status->value] ?? 'badge-soft-secondary'); ?>" style="font-size:13px;padding:.4rem .8rem">
      <?php echo e($statusLabel[$order->status->value] ?? ucfirst($order->status->value)); ?>

    </span>
  </div>

  <div class="row g-3">
    <div class="col-12 col-lg-8">

      <div class="card border rounded-4 p-4 mb-3">
        <h2 class="small fw-bold text-dark mb-3">Informasi Order</h2>
        <div class="row g-3 small">
          <div class="col-sm-6">
            <p class="text-muted mb-1" style="font-size:11px">KLIEN</p>
            <p class="fw-medium text-dark mb-0"><?php echo e($order->client->name ?? '—'); ?></p>
          </div>
          <div class="col-sm-6">
            <p class="text-muted mb-1" style="font-size:11px">PRODUK</p>
            <p class="fw-medium text-dark mb-0"><?php echo e($order->product_name); ?></p>
          </div>
          <?php if($order->license_ip): ?>
            <div class="col-sm-6">
              <p class="text-muted mb-1" style="font-size:11px">IP SERVER LISENSI</p>
              <p class="fw-medium text-dark mb-0"><code><?php echo e($order->license_ip); ?></code></p>
            </div>
          <?php endif; ?>
          <div class="col-sm-6">
            <p class="text-muted mb-1" style="font-size:11px">TIPE</p>
            <p class="fw-medium text-dark mb-0 text-capitalize"><?php echo e($order->order_type); ?></p>
          </div>
          <div class="col-sm-6">
            <p class="text-muted mb-1" style="font-size:11px">JUMLAH</p>
            <p class="fw-medium text-dark mb-0">Rp <?php echo e(number_format($order->amount, 0, ',', '.')); ?></p>
          </div>
          <div class="col-sm-6">
            <p class="text-muted mb-1" style="font-size:11px">HOSTING ACCOUNT TERKAIT</p>
            <p class="fw-medium text-dark mb-0">
              <?php if($order->hostingAccount): ?>
                <a href="<?php echo e(route('admin.hosting-accounts.details', $order->hostingAccount)); ?>" class="text-decoration-none text-accent"><?php echo e($order->hostingAccount->domain); ?></a>
              <?php else: ?>
                —
              <?php endif; ?>
            </p>
          </div>
          <div class="col-sm-6">
            <p class="text-muted mb-1" style="font-size:11px">DOMAIN TERKAIT</p>
            <p class="fw-medium text-dark mb-0">
              <?php if($order->domain): ?>
                <a href="<?php echo e(route('admin.domains.details', $order->domain)); ?>" class="text-decoration-none text-accent"><?php echo e($order->domain->domain_name); ?></a>
                <?php
                  $domainBadge = $order->domain->provision_status === 'registered' ? 'badge-soft-success' : ($order->domain->provision_status === 'failed' ? 'badge-soft-danger' : 'badge-soft-warning');
                ?>
                <span class="badge <?php echo e($domainBadge); ?> ms-1"><?php echo e($order->domain->provision_status); ?></span>
              <?php else: ?>
                —
              <?php endif; ?>
            </p>
          </div>
          <div class="col-sm-6">
            <p class="text-muted mb-1" style="font-size:11px">INVOICE TERKAIT</p>
            <p class="fw-medium text-dark mb-0">
              <?php $orderInvoice = $order->resolvedInvoice(); ?>
              <?php if($orderInvoice): ?>
                <a href="<?php echo e(route('admin.invoices.details', $orderInvoice)); ?>" class="text-decoration-none text-accent"><?php echo e($orderInvoice->invoice_number); ?></a>
              <?php else: ?>
                —
              <?php endif; ?>
            </p>
          </div>
          <div class="col-sm-6">
            <p class="text-muted mb-1" style="font-size:11px">DIBUAT</p>
            <p class="fw-medium text-dark mb-0"><?php echo e($order->created_at->format('d M Y H:i')); ?></p>
          </div>
        </div>
      </div>

      <div class="card border rounded-4 p-4">
        <h2 class="small fw-bold text-dark mb-2">Catatan Internal</h2>
        <form method="POST" action="<?php echo e(route('admin.order.notes')); ?>">
          <?php echo csrf_field(); ?>
          <input type="hidden" name="order_id" value="<?php echo e($order->id); ?>">
          <textarea name="internal_notes" rows="4" class="form-control form-control-sm" placeholder="Catatan staf tentang order ini (tidak terlihat klien)..."><?php echo e(old('internal_notes', $order->internal_notes)); ?></textarea>
          <button type="submit" class="btn btn-outline-secondary btn-sm mt-2"><i class="fa-solid fa-floppy-disk" style="font-size:11px"></i> Simpan Catatan</button>
        </form>
      </div>
    </div>

    <div class="col-12 col-lg-4">
      <div class="card border rounded-4 p-4">
        <h2 class="small fw-bold text-dark mb-2">Aksi</h2>
        <div class="d-flex flex-column gap-2">
          <?php if($order->status !== \App\Enums\OrderStatus::Completed): ?>
            <form method="POST" action="<?php echo e(route('admin.order.accept')); ?>">
              <?php echo csrf_field(); ?>
              <input type="hidden" name="order_id" value="<?php echo e($order->id); ?>">
              <button type="submit" class="btn btn-primary btn-sm w-100 text-start"><i class="fa-solid fa-check" style="font-size:11px"></i> Terima & Aktifkan</button>
            </form>
          <?php endif; ?>
          <?php if($order->status !== \App\Enums\OrderStatus::PendingPayment): ?>
            <form method="POST" action="<?php echo e(route('admin.order.mark.pending')); ?>">
              <?php echo csrf_field(); ?>
              <input type="hidden" name="order_id" value="<?php echo e($order->id); ?>">
              <button type="submit" class="btn btn-outline-secondary btn-sm w-100 text-start"><i class="fa-solid fa-clock" style="font-size:11px"></i> Kembalikan ke Pending</button>
            </form>
          <?php endif; ?>
          <?php if($order->status !== \App\Enums\OrderStatus::Cancelled): ?>
            <form method="POST" action="<?php echo e(route('admin.order.cancel')); ?>" data-confirm="Batalkan order ini?" data-confirm-title="Batalkan" data-confirm-style="warn" data-confirm-label="Ya, Batalkan">
              <?php echo csrf_field(); ?>
              <input type="hidden" name="order_id" value="<?php echo e($order->id); ?>">
              <button type="submit" class="btn btn-outline-danger btn-sm w-100 text-start"><i class="fa-solid fa-xmark" style="font-size:11px"></i> Batalkan Order</button>
            </form>
          <?php endif; ?>
          <a href="<?php echo e(route('admin.order.edit.page', $order)); ?>" class="btn btn-outline-secondary btn-sm w-100 text-start">
            <i class="fa-regular fa-pen-to-square" style="font-size:11px"></i> Edit Data Order
          </a>
        </div>
      </div>
    </div>
  </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/admin/orders/details.blade.php ENDPATH**/ ?>