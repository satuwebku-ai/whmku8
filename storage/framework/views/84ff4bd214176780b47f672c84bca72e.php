<?php $__env->startSection('title', 'Server'); ?>

<?php $__env->startSection('content'); ?>

  <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <div>
      <h1 class="h4 fw-bold text-dark mb-1">Server</h1>
      <p class="small text-muted mb-0">Kelola server cPanel/WHM, DirectAdmin, Plesk, dan provider VM/VPS yang terhubung.</p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
      <a href="<?php echo e(route('admin.server-groups.index')); ?>" class="btn btn-outline-secondary">
        <i class="fa-solid fa-layer-group" style="font-size:12px"></i> Grup Server
      </a>
      <a href="<?php echo e(route('admin.servers.create')); ?>" class="btn btn-primary">
        <i class="fa-solid fa-plus" style="font-size:12px"></i> Tambah Server
      </a>
    </div>
  </div>

  <div class="card border rounded-4 overflow-hidden">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead>
          <tr class="small text-uppercase text-muted" style="background:#f8fafc">
            <th class="px-4 py-3">Nama</th>
            <th class="py-3">Grup</th>
            <th class="py-3">Hostname</th>
            <th class="py-3">Panel</th>
            <th class="text-center py-3">Akun</th>
            <th class="py-3">Cek Terakhir</th>
            <th class="py-3">Status</th>
            <th class="text-end px-4 py-3">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php $__empty_1 = true; $__currentLoopData = $servers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $server): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
              <td class="px-4 py-3 fw-medium text-dark"><?php echo e($server->name); ?></td>
              <td class="py-3">
                <?php if($server->group): ?>
                  <span class="fw-medium"><?php echo e($server->group->name); ?></span>
                  <?php if($server->group->location): ?><br><span class="small text-muted"><?php echo e($server->group->location); ?></span><?php endif; ?>
                <?php else: ?>
                  <span class="text-muted">—</span>
                <?php endif; ?>
              </td>
              <td class="text-muted py-3">
                <?php if($server->isCloud()): ?>
                  <?php echo e($server->hostname ?: ($server->vpsDriver() === 'idcloudhost' ? 'Lokasi default' : 'API provider')); ?>

                <?php else: ?>
                  <?php echo e($server->hostname . ':' . $server->port); ?>

                <?php endif; ?>
              </td>
              <td class="text-muted text-capitalize py-3">
                <?php if($server->isCloud()): ?>
                  VM / VPS · <?php echo e($server->vpsLabel()); ?>

                <?php else: ?>
                  <?php echo e($server->panel === 'cpanel' ? 'cPanel / WHM' : $server->panel); ?>

                <?php endif; ?>
              </td>
              <td class="text-center text-muted py-3"><?php echo e($server->hosting_accounts_count); ?></td>
              <td class="text-muted py-3" style="font-size:12px">
                <?php if($server->last_checked_at): ?>
                  <?php echo e($server->last_checked_at->diffForHumans()); ?>

                  <br>
                  <span class="<?php echo e($server->last_check_status === 'ok' ? 'text-success' : 'text-danger'); ?>">
                    <?php echo e($server->last_check_status === 'ok' ? 'Terhubung' : \Illuminate\Support\Str::limit($server->last_check_status, 40)); ?>

                  </span>
                <?php else: ?>
                  Belum pernah dicek
                <?php endif; ?>
              </td>
              <td class="py-3">
                <span class="badge <?php echo e($server->is_active ? 'badge-soft-success' : 'badge-soft-secondary'); ?>"><?php echo e($server->is_active ? 'Aktif' : 'Nonaktif'); ?></span>
              </td>
              <td class="text-end px-4 py-3">
                <div class="d-flex align-items-center justify-content-end gap-2">
                  <?php if($server->panel === 'cpanel'): ?>
                    <form method="POST" action="<?php echo e(route('admin.servers.login-whm', $server)); ?>" target="_blank">
                      <?php echo csrf_field(); ?>
                      <button type="submit" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center justify-content-center" style="width:32px;height:32px;padding:0" title="Login sekali klik ke WHM">
                        <i class="fa-solid fa-right-to-bracket" style="font-size:12px"></i>
                      </button>
                    </form>
                  <?php endif; ?>
                  <?php if($server->panel === 'cpanel' && ! $server->isCloud()): ?>
                    <a href="<?php echo e(route('admin.servers.branding', $server)); ?>" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center justify-content-center" style="width:32px;height:32px;padding:0" title="Branding cPanel (logo)">
                      <i class="fa-solid fa-paintbrush" style="font-size:12px"></i>
                    </a>
                  <?php endif; ?>
                  <form method="POST" action="<?php echo e(route('admin.servers.test-connection', $server)); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center justify-content-center" style="width:32px;height:32px;padding:0" title="Tes Koneksi">
                      <i class="fa-solid fa-plug" style="font-size:12px"></i>
                    </button>
                  </form>
                  <?php if(! $server->isCloud() || $server->vpsDriver() === 'idcloudhost'): ?>
                    <a href="<?php echo e(route('admin.servers.diagnostics', $server)); ?>" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center justify-content-center" style="width:32px;height:32px;padding:0" title="Diagnosa">
                      <i class="fa-solid fa-stethoscope" style="font-size:12px"></i>
                    </a>
                  <?php endif; ?>
                  <?php if (! ($server->isCloud())): ?>
                    <a href="<?php echo e(route('admin.servers.packages.index', $server)); ?>" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center justify-content-center" style="width:32px;height:32px;padding:0" title="Kelola paket server">
                      <i class="fa-solid fa-box" style="font-size:12px"></i>
                    </a>
                  <?php endif; ?>
                  <a href="<?php echo e(route('admin.servers.edit', $server)); ?>" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center justify-content-center" style="width:32px;height:32px;padding:0" title="Edit">
                    <i class="fa-regular fa-pen-to-square" style="font-size:12px"></i>
                  </a>
                  <form method="POST" action="<?php echo e(route('admin.servers.destroy', $server)); ?>" data-confirm="Hapus server ini?" data-confirm-title="Hapus Data" data-confirm-style="danger" data-confirm-label="Ya, Hapus">
                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-outline-danger btn-sm d-inline-flex align-items-center justify-content-center" style="width:32px;height:32px;padding:0" title="Hapus">
                      <i class="fa-regular fa-trash-can" style="font-size:12px"></i>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr><td colspan="8" class="text-center text-muted py-5">Belum ada server terhubung.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <?php if($servers->hasPages()): ?>
      <div class="px-4 py-3 border-top"><?php echo e($servers->links('pagination.bootstrap')); ?></div>
    <?php endif; ?>
  </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/admin/servers/index.blade.php ENDPATH**/ ?>