<?php $__env->startSection('title', 'Manajemen Admin'); ?>

<?php $__env->startSection('content'); ?>

  <?php echo $__env->make('admin.admins._nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

  <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <div>
      <h1 class="h4 fw-bold text-dark mb-1">Manajemen Admin</h1>
      <p class="small text-muted mb-0">Kelola akun staf beserta tingkat aksesnya.</p>
    </div>
    <a href="<?php echo e(route('admin.admin.add.page')); ?>" class="btn btn-primary">
      <i class="fa-solid fa-plus" style="font-size:12px"></i> Tambah Admin
    </a>
  </div>

  <div class="card border rounded-4 overflow-hidden">
    <form method="GET" class="px-4 py-3 border-bottom d-flex gap-2">
      <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari nama, username, email..." class="form-control form-control-sm" style="max-width:16rem">
      <button type="submit" class="btn btn-outline-secondary btn-sm" style="width:fit-content">Cari</button>
    </form>

    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead>
          <tr class="small text-uppercase text-muted" style="background:#f8fafc">
            <th class="px-4 py-3">Nama</th>
            <th class="py-3">Username</th>
            <th class="py-3">Peran</th>
            <th class="py-3">Login Terakhir</th>
            <th class="py-3">Status</th>
            <th class="text-end px-4 py-3">Aksi</th>
          </tr>
        </thead>
        <tbody>
           <?php
             $roleBadge = fn ($row) => $row->isSuperadmin() ? 'badge-soft-success' : (in_array($row->role, ['support', 'staff'], true) ? 'badge-soft-secondary' : 'badge-soft-primary');
           ?>
          <?php $__empty_1 = true; $__currentLoopData = $admins; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
              <td class="px-4 py-3">
                <p class="fw-medium text-dark mb-0">
                  <?php echo e($row->name); ?>

                  <?php if($row->id === auth('admin')->id()): ?>
                    <span class="text-accent fw-normal" style="font-size:10px">(Anda)</span>
                  <?php endif; ?>
                </p>
                <p class="text-muted mb-0" style="font-size:12px"><?php echo e($row->email); ?></p>
              </td>
              <td class="text-muted py-3" style="font-family:monospace"><?php echo e($row->username); ?></td>
              <td class="py-3">
                <span class="badge <?php echo e($roleBadge($row)); ?>"><?php echo e($row->role_label); ?></span>
              </td>
              <td class="text-muted py-3" style="font-size:12px">
                <?php echo e($row->last_login_at?->diffForHumans() ?? 'Belum pernah'); ?>

                <?php if($row->last_login_ip): ?>
                  <span class="d-block text-muted"><?php echo e($row->last_login_ip); ?></span>
                <?php endif; ?>
              </td>
              <td class="py-3">
                <span class="badge <?php echo e($row->is_active ? 'badge-soft-success' : 'badge-soft-danger'); ?>">
                  <?php echo e($row->is_active ? 'Aktif' : 'Diblokir'); ?>

                </span>
              </td>
              <td class="text-end px-4 py-3">
                <div class="d-flex align-items-center justify-content-end gap-2">
                  <?php if($row->id !== auth('admin')->id()): ?>
                    <form method="POST" action="<?php echo e(route('admin.admin.status')); ?>"
                          data-confirm="<?php echo e($row->is_active ? 'Blokir' : 'Aktifkan'); ?> akun <?php echo e($row->username); ?>?"
                          data-confirm-title="<?php echo e($row->is_active ? 'Blokir Admin' : 'Aktifkan Admin'); ?>"
                          data-confirm-style="<?php echo e($row->is_active ? 'danger' : 'info'); ?>"
                          data-confirm-label="Ya, Lanjutkan">
                      <?php echo csrf_field(); ?>
                      <input type="hidden" name="admin_id" value="<?php echo e($row->id); ?>">
                      <button type="submit" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center justify-content-center" style="width:32px;height:32px;padding:0"
                              title="<?php echo e($row->is_active ? 'Blokir' : 'Aktifkan'); ?>">
                        <i class="fa-solid <?php echo e($row->is_active ? 'fa-ban' : 'fa-circle-check'); ?>" style="font-size:12px"></i>
                      </button>
                    </form>
                  <?php endif; ?>

                  <a href="<?php echo e(route('admin.admin.edit.page', $row)); ?>" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center justify-content-center" style="width:32px;height:32px;padding:0">
                    <i class="fa-regular fa-pen-to-square" style="font-size:12px"></i>
                  </a>

                  <?php if($row->id !== auth('admin')->id()): ?>
                    <form method="POST" action="<?php echo e(route('admin.admin.delete', $row)); ?>"
                          data-confirm="Hapus admin <?php echo e($row->username); ?>? Tindakan ini tidak bisa dibatalkan."
                          data-confirm-title="Hapus Admin" data-confirm-style="danger" data-confirm-label="Ya, Hapus">
                      <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                      <button type="submit" class="btn btn-outline-danger btn-sm d-inline-flex align-items-center justify-content-center" style="width:32px;height:32px;padding:0">
                        <i class="fa-regular fa-trash-can" style="font-size:12px"></i>
                      </button>
                    </form>
                  <?php endif; ?>
                </div>
              </td>
            </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr><td colspan="6" class="text-center text-muted py-5">Belum ada admin lain.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <?php if($admins->hasPages()): ?>
      <div class="px-4 py-3 border-top"><?php echo e($admins->links('pagination.bootstrap')); ?></div>
    <?php endif; ?>
  </div>

  <div class="card border rounded-4 p-4 mt-3">
    <h2 class="small fw-bold text-dark mb-3">Arti Peran</h2>
     <?php $__currentLoopData = \App\Models\Role::ROLES; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <div class="d-flex gap-2 mb-2 small">
         <span class="badge <?php echo e($key === 'superadmin' ? 'badge-soft-success' : ($key === 'support' ? 'badge-soft-secondary' : 'badge-soft-primary')); ?> flex-shrink-0"><?php echo e($name); ?></span>
         <span class="text-muted"><?php echo e(\App\Models\Role::DESCRIPTIONS[$key] ?? 'Akses sesuai modul yang dipilih.'); ?></span>
      </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/admin/admins/index.blade.php ENDPATH**/ ?>