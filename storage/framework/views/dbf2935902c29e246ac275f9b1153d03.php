<?php $__env->startSection('title', 'Klien'); ?>

<?php $__env->startSection('content'); ?>

  <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <div>
      <h1 class="h4 fw-bold text-dark mb-1">Klien</h1>
      <p class="small text-muted mb-0">Kelola data pelanggan hosting Anda.</p>
    </div>
    <a href="<?php echo e(route('admin.client.add.page')); ?>" class="btn btn-primary">
      <i class="fa-solid fa-plus" style="font-size:12px"></i> Tambah Klien
    </a>
  </div>

  
  <div class="d-flex align-items-center gap-1 mb-3 border-bottom">
    <?php
      $tabs = [
        ['label' => 'Semua', 'route' => 'admin.clients', 'status' => null],
        ['label' => 'Aktif', 'route' => 'admin.clients.active', 'status' => 'active'],
        ['label' => 'Nonaktif', 'route' => 'admin.clients.inactive', 'status' => 'inactive'],
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
      <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari nama, email, atau perusahaan..." class="form-control form-control-sm" style="max-width:20rem;flex:1 1 200px">
      <button type="submit" class="btn btn-outline-secondary btn-sm" style="width:fit-content">Cari</button>
      <?php if(request('search')): ?>
        <a href="<?php echo e(url()->current()); ?>" class="btn btn-outline-secondary btn-sm" style="width:fit-content">Reset</a>
      <?php endif; ?>
    </form>

    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead>
          <tr class="small text-uppercase text-muted" style="background:#f8fafc">
            <th class="px-4 py-3">Klien</th>
            <th class="py-3">Kontak</th>
            <th class="text-center py-3">Layanan</th>
            <th class="text-center py-3">Order</th>
            <th class="text-center py-3">Invoice</th>
            <th class="py-3">Status</th>
            <th class="text-end px-4 py-3">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php $__empty_1 = true; $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
              <td class="px-4 py-3">
                <a href="<?php echo e(route('admin.clients.details', $client)); ?>" class="d-flex align-items-center gap-3 text-decoration-none">
                  <span class="rounded-circle bg-primary bg-opacity-10 text-accent fw-bold d-flex align-items-center justify-content-center flex-shrink-0" style="width:32px;height:32px;font-size:12px">
                    <?php echo e($client->initials); ?>

                  </span>
                  <div>
                    <p class="fw-medium text-dark mb-0"><?php echo e($client->name); ?></p>
                    <?php if($client->company): ?>
                      <p class="text-muted mb-0" style="font-size:12px"><?php echo e($client->company); ?></p>
                    <?php endif; ?>
                  </div>
                </a>
              </td>
              <td class="text-muted py-3">
                <p class="mb-0"><?php echo e($client->email); ?></p>
                <?php if($client->phone): ?>
                  <p class="text-muted mb-0" style="font-size:12px"><?php echo e($client->phone); ?></p>
                <?php endif; ?>
              </td>
              <td class="text-center text-muted py-3"><?php echo e($client->hosting_accounts_count); ?></td>
              <td class="text-center text-muted py-3"><?php echo e($client->orders_count); ?></td>
              <td class="text-center text-muted py-3"><?php echo e($client->invoices_count); ?></td>
              <td class="py-3">
                <span class="badge <?php echo e($client->status === 'active' ? 'badge-soft-success' : 'badge-soft-secondary'); ?>">
                  <?php echo e($client->status === 'active' ? 'Aktif' : 'Nonaktif'); ?>

                </span>
              </td>
              <td class="text-end px-4 py-3">
                <div class="d-flex align-items-center justify-content-end gap-2">
                  <a href="<?php echo e(route('admin.clients.details', $client)); ?>" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center justify-content-center" style="width:32px;height:32px;padding:0" title="Detail">
                    <i class="fa-regular fa-eye" style="font-size:12px"></i>
                  </a>
                  <a href="<?php echo e(route('admin.client.edit.page', $client)); ?>" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center justify-content-center" style="width:32px;height:32px;padding:0" title="Edit">
                    <i class="fa-regular fa-pen-to-square" style="font-size:12px"></i>
                  </a>
                  <form method="POST" action="<?php echo e(route('admin.client.delete', $client)); ?>" data-confirm="Hapus klien ini? Semua layanan, order, dan invoice terkait juga akan terhapus." data-confirm-title="Hapus Data" data-confirm-style="danger" data-confirm-label="Ya, Hapus">
                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-outline-danger btn-sm d-inline-flex align-items-center justify-content-center" style="width:32px;height:32px;padding:0" title="Hapus">
                      <i class="fa-regular fa-trash-can" style="font-size:12px"></i>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr><td colspan="7" class="text-center text-muted py-5">Tidak ada klien di kategori ini.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <?php if($clients->hasPages()): ?>
      <div class="px-4 py-3 border-top"><?php echo e($clients->links('pagination.bootstrap')); ?></div>
    <?php endif; ?>
  </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/admin/clients/index.blade.php ENDPATH**/ ?>