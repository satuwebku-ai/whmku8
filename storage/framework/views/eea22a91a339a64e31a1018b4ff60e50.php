<?php $__env->startSection('title', 'Submenu / Subnav'); ?>

<?php $__env->startSection('content'); ?>

  <?php echo $__env->make('admin.pages._nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

  <?php $selectedParent = request()->integer('parent'); ?>

  <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
    <div>
      <h1 class="h4 fw-bold text-dark mb-1">Submenu / Subnav</h1>
      <p class="small text-muted mb-0">Atur item dropdown yang berada di bawah setiap Menu Utama.</p>
    </div>
    <a href="<?php echo e(route('admin.nav-submenu.add.page', $selectedParent ? ['parent_id' => $selectedParent] : [])); ?>" class="btn btn-primary">
      <i class="fa-solid fa-plus" style="font-size:12px"></i> Tambah Submenu
    </a>
  </div>

  <div class="card border rounded-4 p-4 mb-3" style="background:#f8fafc">
    <div class="row g-3 align-items-end">
      <div class="col-md-8">
        <label class="form-label small fw-medium mb-1">Tampilkan submenu dari Menu Utama</label>
        <select class="form-select form-select-sm" data-navigate-on-change>
          <option value="<?php echo e(route('admin.nav-submenus')); ?>">Semua Menu Utama</option>
          <?php $__currentLoopData = $mainMenus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $main): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e(route('admin.nav-submenus', ['parent' => $main->id])); ?>" <?php if($selectedParent === $main->id): echo 'selected'; endif; ?>>
              <?php echo e($main->label); ?> (<?php echo e($main->all_children_count); ?> submenu)
            </option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
      </div>
      <div class="col-md-4 small text-muted">
        <i class="fa-solid fa-circle-info"></i>
        Submenu hanya boleh satu tingkat. Tidak ada submenu di dalam submenu.
      </div>
    </div>
  </div>

  <?php
    $groups = $selectedParent
      ? $mainMenus->where('id', $selectedParent)
      : $mainMenus;
  ?>

  <?php $__empty_1 = true; $__currentLoopData = $groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $main): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <div class="card border rounded-4 overflow-hidden mb-3">
      <div class="d-flex align-items-center justify-content-between gap-2 px-4 py-3 border-bottom" style="background:#f8fafc">
        <div>
          <div class="small fw-bold text-dark"><i class="fa-solid fa-bars-staggered text-primary me-1"></i><?php echo e($main->label); ?></div>
          <div class="text-muted" style="font-size:11px">Menu Utama</div>
        </div>
        <a href="<?php echo e(route('admin.nav-submenu.add.page', ['parent_id' => $main->id])); ?>" class="btn btn-outline-primary btn-sm">
          <i class="fa-solid fa-plus"></i> Tambah Submenu
        </a>
      </div>

      <?php $__empty_2 = true; $__currentLoopData = $main->allChildren; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
        <div class="d-flex align-items-center gap-3 px-4 py-3 border-bottom <?php echo e($child->is_active ? '' : 'opacity-50'); ?>">
          <div class="d-flex flex-column flex-shrink-0" style="gap:2px">
            <form method="POST" action="<?php echo e(route('admin.nav-menu.move', $child)); ?>">
              <?php echo csrf_field(); ?>
              <input type="hidden" name="direction" value="up">
              <button type="submit" class="btn btn-link p-0 text-muted" style="width:24px;height:20px" title="Naikkan">
                <i class="fa-solid fa-chevron-up" style="font-size:10px"></i>
              </button>
            </form>
            <form method="POST" action="<?php echo e(route('admin.nav-menu.move', $child)); ?>">
              <?php echo csrf_field(); ?>
              <input type="hidden" name="direction" value="down">
              <button type="submit" class="btn btn-link p-0 text-muted" style="width:24px;height:20px" title="Turunkan">
                <i class="fa-solid fa-chevron-down" style="font-size:10px"></i>
              </button>
            </form>
          </div>

          <div class="flex-grow-1 min-w-0">
            <p class="small fw-medium text-dark mb-0"><?php echo e($child->label); ?></p>
            <p class="text-muted text-truncate mb-0" style="font-size:12px">
              <?php switch($child->type):
                case ('route'): ?>
                  <i class="fa-solid fa-house" style="font-size:10px"></i>
                  <?php echo e(\App\Models\NavMenu::BUILTIN_ROUTES[$child->route_name] ?? $child->route_name); ?>

                  <?php break; ?>
                <?php case ('page'): ?>
                  <i class="fa-regular fa-file" style="font-size:10px"></i>
                  <?php echo e($child->page->title ?? '(halaman terhapus)'); ?>

                  <?php break; ?>
                <?php default: ?>
                  <i class="fa-solid fa-link" style="font-size:10px"></i> <?php echo e($child->url); ?>

              <?php endswitch; ?>
            </p>
          </div>

          <?php if(! $child->resolved_url): ?>
            <span class="badge badge-soft-danger flex-shrink-0">Tautan rusak</span>
          <?php endif; ?>

          <div class="d-flex align-items-center gap-2 flex-shrink-0">
            <form method="POST" action="<?php echo e(route('admin.nav-menu.status')); ?>">
              <?php echo csrf_field(); ?>
              <input type="hidden" name="nav_menu_id" value="<?php echo e($child->id); ?>">
              <button type="submit" class="btn btn-outline-secondary btn-sm" style="width:32px;height:32px;padding:0" title="<?php echo e($child->is_active ? 'Sembunyikan' : 'Tampilkan'); ?>">
                <i class="fa-solid <?php echo e($child->is_active ? 'fa-eye' : 'fa-eye-slash'); ?>" style="font-size:12px"></i>
              </button>
            </form>
            <a href="<?php echo e(route('admin.nav-submenu.edit.page', $child)); ?>" class="btn btn-outline-secondary btn-sm" style="width:32px;height:32px;padding:0">
              <i class="fa-regular fa-pen-to-square" style="font-size:12px"></i>
            </a>
            <form method="POST" action="<?php echo e(route('admin.nav-menu.delete', $child)); ?>"
                  data-confirm="Hapus submenu &quot;<?php echo e($child->label); ?>&quot;?"
                  data-confirm-title="Hapus Submenu" data-confirm-style="danger" data-confirm-label="Ya, Hapus">
              <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
              <button type="submit" class="btn btn-outline-danger btn-sm" style="width:32px;height:32px;padding:0">
                <i class="fa-regular fa-trash-can" style="font-size:12px"></i>
              </button>
            </form>
          </div>
        </div>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
        <div class="text-center py-4">
          <p class="small text-muted mb-2">Belum ada submenu untuk <b><?php echo e($main->label); ?></b>.</p>
          <a href="<?php echo e(route('admin.nav-submenu.add.page', ['parent_id' => $main->id])); ?>" class="btn btn-outline-primary btn-sm">+ Tambah Submenu</a>
        </div>
      <?php endif; ?>
    </div>
  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <div class="card border rounded-4 text-center py-5">
      <p class="small text-dark mb-1">Belum ada Menu Utama.</p>
      <p class="small text-muted mb-3">Buat Menu Utama terlebih dahulu sebelum membuat Submenu.</p>
      <a href="<?php echo e(route('admin.nav-menu.add.page')); ?>" class="btn btn-primary btn-sm">+ Buat Menu Utama</a>
    </div>
  <?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/admin/nav-menus/submenus.blade.php ENDPATH**/ ?>