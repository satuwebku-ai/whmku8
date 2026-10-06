<?php $__env->startSection('title', 'Order'); ?>

<?php $__env->startSection('content'); ?>

  <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <div>
      <h1 class="h4 fw-bold text-dark mb-1">Order</h1>
      <p class="small text-muted mb-0">Riwayat dan status pemesanan layanan.</p>
    </div>
    <a href="<?php echo e(route('admin.order.add.page')); ?>" class="btn btn-primary">
      <i class="fa-solid fa-plus" style="font-size:12px"></i> Buat Order
    </a>
  </div>

  
  <div class="d-flex align-items-center gap-1 mb-3 border-bottom flex-wrap">
    <?php
      // Nilai 'status' di sini HARUS sama dengan string yang dikirim
      // Admin\OrderController ke masing-masing rute tab (lihat
      // OrderStatus::PendingPayment/Completed/Failed->value) --
      // sebelumnya nilainya cuma tebakan lama ('pending','active',
      // 'suspended') yang tidak pernah cocok dengan $activeStatus
      // sungguhan, jadi tab yang aktif tidak pernah ke-highlight.
      $tabs = [
        ['label' => 'Semua', 'route' => 'admin.orders', 'status' => null],
        ['label' => 'Menunggu Bayar', 'route' => 'admin.orders.pending', 'status' => \App\Enums\OrderStatus::PendingPayment->value],
        ['label' => 'Aktif', 'route' => 'admin.orders.active', 'status' => \App\Enums\OrderStatus::Completed->value],
        ['label' => 'Gagal', 'route' => 'admin.orders.suspended', 'status' => \App\Enums\OrderStatus::Failed->value],
        ['label' => 'Cancelled', 'route' => 'admin.orders.cancelled', 'status' => 'cancelled'],
      ];
    ?>
    <?php $__currentLoopData = $tabs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tab): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <a href="<?php echo e(route($tab['route'])); ?>"
         class="px-3 py-2 small fw-medium text-decoration-none border-bottom border-2 <?php echo e($activeStatus === $tab['status'] ? 'border-primary text-accent' : 'border-transparent text-muted'); ?>">
        <?php echo e($tab['label']); ?>

      </a>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </div>

  <div class="card border rounded-4 overflow-hidden">
    <form method="GET" class="px-4 py-3 border-bottom d-flex flex-wrap align-items-center gap-2">
      <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari nomor order / produk..." class="form-control form-control-sm" style="max-width:20rem;flex:1 1 200px">
      <button type="submit" class="btn btn-outline-secondary btn-sm" style="width:fit-content">Cari</button>
      <?php if(request('search')): ?>
        <a href="<?php echo e(url()->current()); ?>" class="btn btn-outline-secondary btn-sm" style="width:fit-content">Reset</a>
      <?php endif; ?>
    </form>

    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead>
          <tr class="small text-uppercase text-muted" style="background:#f8fafc">
            <th class="px-4 py-3">ID Order</th>
            <th class="py-3">Klien</th>
            <th class="py-3">Produk</th>
            <th class="py-3">Status</th>
            <th class="text-end py-3">Total</th>
            <th class="text-end px-4 py-3">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php
            // $order->status di-cast ke enum App\Enums\OrderStatus (lihat
            // Order::casts()) -- kedua peta di bawah HARUS dikunci pakai
            // ->value (string mentah), bukan objek enum-nya langsung.
            // Mengunci array pakai objek enum melempar "Cannot access
            // offset of type App\Enums\OrderStatus on array" -- ini yang
            // kemarin membuat halaman ini 500 begitu Order pertama
            // dibuat (sebelumnya selalu kosong jadi tidak pernah ketahuan).
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
          <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
              <td class="px-4 py-3 fw-medium text-dark">
                <a href="<?php echo e(route('admin.orders.details', $order)); ?>" class="text-decoration-none text-dark">#<?php echo e($order->order_number); ?></a>
              </td>
              <td class="text-muted py-3"><?php echo e($order->client->name ?? '—'); ?></td>
              <td class="text-muted py-3"><?php echo e($order->product_name); ?></td>
              <td class="py-3">
                <span class="badge <?php echo e($statusBadge[$order->status->value] ?? 'badge-soft-secondary'); ?>">
                  <?php echo e($statusLabel[$order->status->value] ?? ucfirst($order->status->value)); ?>

                </span>
              </td>
              <td class="text-end text-dark py-3">Rp <?php echo e(number_format($order->amount, 0, ',', '.')); ?></td>
              <td class="text-end px-4 py-3">
                <div class="d-flex align-items-center justify-content-end gap-2">
                  <a href="<?php echo e(route('admin.orders.details', $order)); ?>" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center justify-content-center" style="width:32px;height:32px;padding:0" title="Detail">
                    <i class="fa-regular fa-eye" style="font-size:12px"></i>
                  </a>
                  <a href="<?php echo e(route('admin.order.edit.page', $order)); ?>" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center justify-content-center" style="width:32px;height:32px;padding:0" title="Edit">
                    <i class="fa-regular fa-pen-to-square" style="font-size:12px"></i>
                  </a>
                  <form method="POST" action="<?php echo e(route('admin.order.delete', $order)); ?>" data-confirm="Hapus order ini?" data-confirm-title="Hapus Data" data-confirm-style="danger" data-confirm-label="Ya, Hapus">
                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-outline-danger btn-sm d-inline-flex align-items-center justify-content-center" style="width:32px;height:32px;padding:0" title="Hapus">
                      <i class="fa-regular fa-trash-can" style="font-size:12px"></i>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr><td colspan="6" class="text-center text-muted py-5">Tidak ada order di kategori ini.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <?php if($orders->hasPages()): ?>
      <div class="px-4 py-3 border-top"><?php echo e($orders->links('pagination.bootstrap')); ?></div>
    <?php endif; ?>
  </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/admin/orders/index.blade.php ENDPATH**/ ?>