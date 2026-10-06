<?php $__env->startSection('title', 'Pembayaran'); ?>

<?php $__env->startSection('content'); ?>

  
  <div class="d-flex align-items-center gap-1 mb-3 border-bottom flex-wrap">
    <?php
      $topTabs = [
        ['label' => 'Transaksi', 'route' => 'admin.payments'],
        ['label' => 'Gateway', 'route' => 'admin.gateways'],
      ];
    ?>
    <?php $__currentLoopData = $topTabs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tab): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <a href="<?php echo e(route($tab['route'])); ?>"
         class="px-3 py-2 small fw-medium text-decoration-none border-bottom border-2 <?php echo e(request()->routeIs(str_replace('.bootstrap-preview', '', $tab['route']) . '*') ? 'border-primary text-accent' : 'border-transparent text-muted'); ?>">
        <?php echo e($tab['label']); ?>

      </a>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </div>

  <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
    <div>
      <h1 class="h4 fw-bold text-dark mb-1">Transaksi Pembayaran</h1>
      <p class="small text-muted mb-0">Riwayat pembayaran invoice dari semua gateway.</p>
    </div>
    <a href="<?php echo e(route('admin.payment.add.page')); ?>" class="btn btn-primary">
      <i class="fa-solid fa-plus" style="font-size:12px"></i> Buat Pembayaran
    </a>
  </div>

  
  <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
    <?php
      $statusTabs = [
        ['label' => 'Semua', 'route' => 'admin.payments', 'status' => null],
        ['label' => 'Menunggu Bayar', 'route' => 'admin.payments.initiated', 'status' => 'initiated'],
        ['label' => 'Perlu Verifikasi', 'route' => 'admin.payments.pending', 'status' => 'pending'],
        ['label' => 'Lunas', 'route' => 'admin.payments.paid', 'status' => 'paid'],
        ['label' => 'Gagal', 'route' => 'admin.payments.failed', 'status' => 'failed'],
        ['label' => 'Refund', 'route' => 'admin.payments.refunded', 'status' => 'refunded'],
      ];
    ?>
    <?php $__currentLoopData = $statusTabs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tab): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <a href="<?php echo e(route($tab['route'])); ?>"
         class="px-3 py-2 small fw-medium text-decoration-none rounded-pill <?php echo e($activeStatus === $tab['status'] ? 'text-white' : 'text-muted'); ?>"
         style="<?php echo e($activeStatus === $tab['status'] ? 'background:#4f46e5' : 'background:#f1f5f9'); ?>">
        <?php echo e($tab['label']); ?>

      </a>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </div>

  <div class="card border rounded-4 overflow-hidden">
    <form method="GET" class="px-4 py-3 border-bottom d-flex flex-wrap align-items-center gap-2">
      <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari nomor referensi..." class="form-control form-control-sm" style="max-width:16rem;flex:1 1 180px">
      <button type="submit" class="btn btn-outline-secondary btn-sm" style="width:fit-content">Cari</button>
      <?php if(request('search')): ?>
        <a href="<?php echo e(url()->current()); ?>" class="btn btn-outline-secondary btn-sm" style="width:fit-content">Reset</a>
      <?php endif; ?>
    </form>

    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead>
          <tr class="small text-uppercase text-muted" style="background:#f8fafc">
            <th class="px-4 py-3">Referensi</th>
            <th class="py-3">Klien</th>
            <th class="py-3">Invoice</th>
            <th class="py-3">Gateway</th>
            <th class="py-3">Status</th>
            <th class="text-end py-3">Total</th>
            <th class="text-end px-4 py-3">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php
            $badgeMap = ['paid' => 'badge-soft-success', 'pending' => 'badge-soft-warning', 'inactive' => 'badge-soft-secondary', 'suspended' => 'badge-soft-danger'];
          ?>
          <?php $__empty_1 = true; $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
              <td class="px-4 py-3 fw-medium text-dark">
                <a href="<?php echo e(route('admin.payments.details', $payment)); ?>" class="text-decoration-none text-dark"><?php echo e($payment->reference); ?></a>
                <?php if($payment->proof_path): ?>
                  <span class="badge badge-soft-success ms-1" style="font-size:10px" title="Bukti transfer sudah diunggah klien">
                    <i class="fa-solid fa-receipt"></i> Ada Bukti
                  </span>
                <?php endif; ?>
              </td>
              <td class="text-muted py-3"><?php echo e($payment->client->name ?? '—'); ?></td>
              <td class="text-muted py-3">
                <?php if($payment->invoice): ?>
                  <a href="<?php echo e(route('admin.invoices.details', $payment->invoice)); ?>" class="text-decoration-none text-accent"><?php echo e($payment->invoice->invoice_number); ?></a>
                <?php else: ?>
                  —
                <?php endif; ?>
              </td>
              <td class="text-muted py-3"><?php echo e($payment->gateway->name ?? '—'); ?></td>
              <td class="py-3"><span class="badge <?php echo e($badgeMap[$payment->status_badge] ?? 'badge-soft-secondary'); ?>"><?php echo e(ucfirst($payment->status)); ?></span></td>
              <td class="text-end text-dark py-3">Rp <?php echo e(number_format($payment->total, 0, ',', '.')); ?></td>
              <td class="text-end px-4 py-3">
                <div class="d-flex align-items-center justify-content-end gap-2">
                  <a href="<?php echo e(route('admin.payments.details', $payment)); ?>" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center justify-content-center" style="width:32px;height:32px;padding:0" title="Detail">
                    <i class="fa-regular fa-eye" style="font-size:12px"></i>
                  </a>
                  <form method="POST" action="<?php echo e(route('admin.payment.delete', $payment)); ?>" data-confirm="Hapus data pembayaran ini?" data-confirm-title="Hapus Data" data-confirm-style="danger" data-confirm-label="Ya, Hapus">
                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-outline-danger btn-sm d-inline-flex align-items-center justify-content-center" style="width:32px;height:32px;padding:0" title="Hapus">
                      <i class="fa-regular fa-trash-can" style="font-size:12px"></i>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr><td colspan="7" class="text-center text-muted py-5">Tidak ada pembayaran di kategori ini.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <?php if($payments->hasPages()): ?>
      <div class="px-4 py-3 border-top"><?php echo e($payments->links('pagination.bootstrap')); ?></div>
    <?php endif; ?>
  </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/admin/payments/index.blade.php ENDPATH**/ ?>