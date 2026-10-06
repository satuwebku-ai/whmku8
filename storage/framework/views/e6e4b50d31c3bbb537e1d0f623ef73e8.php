<?php $__env->startSection('title', 'Paket Server'); ?>

<?php $__env->startSection('content'); ?>
  <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <div>
      <h1 class="h4 fw-bold text-dark mb-1">Paket Server</h1>
      <p class="small text-muted mb-0"><?php echo e($server->name); ?> · <?php echo e($server->hostname); ?></p>
    </div>
    <div class="d-flex gap-2">
      <a href="<?php echo e(route('admin.servers.index')); ?>" class="btn btn-outline-secondary">Kembali</a>
      <a href="<?php echo e(route('admin.servers.packages.create', $server)); ?>" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Tambah Paket</a>
    </div>
  </div>

  <div class="card border rounded-4 overflow-hidden">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead>
          <tr class="small text-uppercase text-muted" style="background:#f8fafc">
            <th class="px-4 py-3">Paket</th>
            <th class="py-3">Batas Resource</th>
            <th class="py-3">Harga Acuan</th>
            <th class="py-3">Tautan</th>
            <th class="py-3">Status</th>
            <th class="text-end px-4 py-3">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php $__empty_1 = true; $__currentLoopData = $packages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $package): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
              <td class="px-4 py-3 fw-medium"><?php echo e($package->name); ?></td>
              <td class="small text-muted">
                Disk: <?php echo e($package->disk_limit ?? '∞'); ?> GB · Bandwidth: <?php echo e($package->bandwidth_limit ?? '∞'); ?> GB<br>
                CPU: <?php echo e($package->cpu_limit ?? '—'); ?> · RAM: <?php echo e($package->ram_limit ?? '—'); ?> MB
              </td>
              <td>Rp <?php echo e(number_format((float) $package->price, 0, ',', '.')); ?></td>
              <td class="small text-muted"><?php echo e($package->products_count); ?> produk · <?php echo e($package->hosting_accounts_count); ?> layanan</td>
              <td><span class="badge <?php echo e($package->status === 'active' ? 'badge-soft-success' : 'badge-soft-secondary'); ?>"><?php echo e($package->status === 'active' ? 'Aktif' : 'Nonaktif'); ?></span></td>
              <td class="text-end px-4">
                <div class="d-inline-flex gap-2">
                  <a href="<?php echo e(route('admin.servers.packages.edit', [$server, $package])); ?>" class="btn btn-outline-secondary btn-sm">Edit</a>
                  <form method="POST" action="<?php echo e(route('admin.servers.packages.destroy', [$server, $package])); ?>" data-confirm="Hapus paket server ini?" data-confirm-title="Hapus Paket" data-confirm-style="danger" data-confirm-label="Ya, Hapus">
                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                    <button class="btn btn-outline-danger btn-sm" type="submit" <?php if($package->products_count > 0 || $package->hosting_accounts_count > 0): echo 'disabled'; endif; ?>>Hapus</button>
                  </form>
                </div>
              </td>
            </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr><td colspan="6" class="text-center text-muted py-5">Belum ada inventaris paket pada server ini.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
    <?php if($packages->hasPages()): ?><div class="px-4 py-3 border-top"><?php echo e($packages->links('pagination.bootstrap')); ?></div><?php endif; ?>
  </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/admin/server-packages/index.blade.php ENDPATH**/ ?>