<?php $__env->startSection('title', 'Persyaratan Berkas'); ?>
<?php $__env->startSection('content'); ?>
  <?php echo $__env->make('admin.settings._nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

  <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <div>
      <h1 class="h4 fw-bold text-dark mb-1">Persyaratan Berkas</h1>
      <p class="small text-muted mb-0">
        Jenis berkas yang bisa diwajibkan saat klien memesan domain — mis. KTP, NIB, surat permohonan.
        Pemetaan ke domainnya diatur di <a href="<?php echo e(route('admin.settings.requirements.domains')); ?>" class="text-accent">Persyaratan per Domain</a>.
      </p>
    </div>
    <div class="d-flex align-items-center gap-2">
      <a href="<?php echo e(route('admin.settings.requirements.domains')); ?>" class="btn btn-outline-secondary btn-sm">
        <i class="fa-solid fa-globe" style="font-size:11px"></i> Persyaratan per Domain
      </a>
      <a href="<?php echo e(route('admin.settings.requirements.create')); ?>" class="btn btn-primary btn-sm">
        <i class="fa-solid fa-plus" style="font-size:11px"></i> Tambah Persyaratan
      </a>
    </div>
  </div>

  <div class="card border rounded-4 overflow-hidden">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead>
          <tr class="small text-uppercase text-muted" style="background:#f8fafc">
            <th class="px-4 py-3">Nama Berkas</th>
            <th class="text-center py-3">Wajib</th>
            <th class="text-center py-3">Status</th>
            <th class="py-3">Dipakai di Domain</th>
            <th class="text-end px-4 py-3">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php $__empty_1 = true; $__currentLoopData = $requirements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $req): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
              <td class="px-4 py-3">
                <span class="fw-medium text-dark"><?php echo e($req->name); ?></span>
                <?php if($req->description): ?>
                  <span class="d-block text-muted" style="font-size:11px"><?php echo e($req->description); ?></span>
                <?php endif; ?>
              </td>
              <td class="text-center py-3">
                <?php if($req->is_required): ?>
                  <span class="badge badge-soft-success" style="font-size:10px">Wajib</span>
                <?php else: ?>
                  <span class="badge badge-soft-secondary" style="font-size:10px">Opsional</span>
                <?php endif; ?>
              </td>
              <td class="text-center py-3">
                <span class="badge <?php echo e($req->is_active ? 'badge-soft-success' : 'badge-soft-secondary'); ?>" style="font-size:10px">
                  <?php echo e($req->is_active ? 'Aktif' : 'Nonaktif'); ?>

                </span>
              </td>
              <td class="py-3">
                <?php if($req->tld_links_count > 0): ?>
                  <div class="d-flex flex-wrap gap-1">
                    <?php $__currentLoopData = $req->tldLinks->take(6); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                      <span class="badge badge-soft-secondary" style="font-size:10px"><?php echo e($link->extension); ?></span>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php if($req->tld_links_count > 6): ?>
                      <span class="text-muted" style="font-size:10px">+<?php echo e($req->tld_links_count - 6); ?> lagi</span>
                    <?php endif; ?>
                  </div>
                <?php else: ?>
                  <span class="text-muted" style="font-size:11px">Belum dipetakan ke domain mana pun</span>
                <?php endif; ?>
              </td>
              <td class="text-end px-4 py-3">
                <div class="d-flex align-items-center justify-content-end gap-2">
                  <a href="<?php echo e(route('admin.settings.requirements.edit', $req)); ?>"
                     class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center justify-content-center"
                     style="width:32px;height:32px;padding:0" title="Edit">
                    <i class="fa-regular fa-pen-to-square" style="font-size:11px"></i>
                  </a>
                  <form method="POST" action="<?php echo e(route('admin.settings.requirements.destroy', $req)); ?>"
                        data-confirm="Hapus persyaratan <?php echo e($req->name); ?>?" data-confirm-title="Hapus Persyaratan"
                        data-confirm-style="danger" data-confirm-label="Ya, Hapus">
                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-outline-danger btn-sm d-inline-flex align-items-center justify-content-center"
                            style="width:32px;height:32px;padding:0" title="Hapus">
                      <i class="fa-regular fa-trash-can" style="font-size:11px"></i>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
              <td colspan="5" class="text-center py-5">
                <p class="text-muted mb-1" style="font-size:14px">Belum ada persyaratan.</p>
                <p class="text-muted mb-0" style="font-size:11px">Tambahkan dulu jenis berkasnya, lalu petakan ke domain.</p>
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/admin/settings/requirements/index.blade.php ENDPATH**/ ?>