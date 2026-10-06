<?php $__env->startSection('title', 'Menu Utama'); ?>

<?php $__env->startSection('content'); ?>

  <?php echo $__env->make('admin.pages._nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

  <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
    <div>
      <h1 class="h4 fw-bold text-dark mb-1">Menu Utama</h1>
      <p class="small text-muted mb-0">Atur menu yang tampil langsung di navbar situs publik.</p>
    </div>
    <a href="<?php echo e(route('admin.nav-menu.add.page')); ?>" class="btn btn-primary">
      <i class="fa-solid fa-plus" style="font-size:12px"></i> Tambah Menu Utama
    </a>
  </div>

  <div class="card border rounded-4 p-4 mb-3" style="background:#f8fafc">
    <div class="d-flex align-items-start gap-3">
      <div class="text-primary pt-1"><i class="fa-solid fa-circle-info"></i></div>
      <div class="small text-muted">
        <div class="fw-bold text-dark mb-1">Alur navigasi CMS</div>
        <div>1. Buat <b>Menu Utama</b> di halaman ini.</div>
        <div>2. Buka <b>Submenu / Subnav</b> untuk membuat dropdown di bawah menu utama.</div>
        <div>3. Menu utama dan submenu memiliki urutan, status aktif, serta pengaturan masing-masing.</div>
      </div>
    </div>
  </div>

  <div class="card border rounded-4 overflow-hidden">
    <div class="px-4 py-2 border-bottom text-muted" style="font-size:11px;background:#f8fafc">
      <i class="fa-solid fa-bars"></i> Hanya menu level pertama yang ditampilkan di sini.
      Submenu dikelola di halaman <a href="<?php echo e(route('admin.nav-submenus')); ?>">Submenu / Subnav</a>.
    </div>

    <?php $__empty_1 = true; $__currentLoopData = $menus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
      <?php $hasValidChild = $menu->children->contains(fn ($c) => (bool) $c->resolved_url); ?>
      <div class="d-flex align-items-center gap-3 px-4 py-3 border-bottom <?php echo e($menu->is_active ? '' : 'opacity-50'); ?>">
        <div class="d-flex flex-column flex-shrink-0" style="gap:2px">
          <form method="POST" action="<?php echo e(route('admin.nav-menu.move', $menu)); ?>">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="direction" value="up">
            <button type="submit" class="btn btn-link p-0 text-muted" style="width:24px;height:20px" title="Naikkan">
              <i class="fa-solid fa-chevron-up" style="font-size:10px"></i>
            </button>
          </form>
          <form method="POST" action="<?php echo e(route('admin.nav-menu.move', $menu)); ?>">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="direction" value="down">
            <button type="submit" class="btn btn-link p-0 text-muted" style="width:24px;height:20px" title="Turunkan">
              <i class="fa-solid fa-chevron-down" style="font-size:10px"></i>
            </button>
          </form>
        </div>

        <div class="flex-grow-1 min-w-0">
          <div class="d-flex align-items-center gap-2 flex-wrap">
            <p class="small fw-bold text-dark mb-0"><?php echo e($menu->label); ?></p>
            <span class="badge bg-light text-dark border" style="font-size:10px">
              <?php echo e($menu->all_children_count); ?> submenu
            </span>
          </div>
          <p class="text-muted text-truncate mb-0" style="font-size:12px">
            <?php if($menu->default_child_id): ?>
              <i class="fa-solid fa-arrow-turn-up fa-rotate-90" style="font-size:10px"></i>
              Langsung ke Subnav — <?php echo e($menu->defaultChild->label ?? '(subnav terhapus)'); ?>

            <?php else: ?>
              <?php switch($menu->type):
                case ('route'): ?>
                  <i class="fa-solid fa-house" style="font-size:10px"></i>
                  Halaman bawaan — <?php echo e(\App\Models\NavMenu::BUILTIN_ROUTES[$menu->route_name] ?? $menu->route_name); ?>

                  <?php break; ?>
                <?php case ('page'): ?>
                  <i class="fa-regular fa-file" style="font-size:10px"></i>
                  Halaman — <?php echo e($menu->page->title ?? '(halaman terhapus)'); ?>

                  <?php break; ?>
                <?php default: ?>
                  <i class="fa-solid fa-link" style="font-size:10px"></i> <?php echo e($menu->url); ?>

              <?php endswitch; ?>
            <?php endif; ?>
          </p>
        </div>

        <?php if(! $menu->resolved_url && ! $hasValidChild): ?>
          <span class="badge badge-soft-danger flex-shrink-0">Tautan rusak</span>
        <?php endif; ?>

        <div class="d-flex align-items-center gap-2 flex-shrink-0">
          <a href="<?php echo e(route('admin.nav-submenus', ['parent' => $menu->id])); ?>" class="btn btn-outline-primary btn-sm d-inline-flex align-items-center gap-1">
            <i class="fa-solid fa-list" style="font-size:11px"></i>
            <span class="d-none d-md-inline">Subnav</span>
          </a>
          <form method="POST" action="<?php echo e(route('admin.nav-menu.status')); ?>">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="nav_menu_id" value="<?php echo e($menu->id); ?>">
            <button type="submit" class="btn btn-outline-secondary btn-sm" style="width:32px;height:32px;padding:0" title="<?php echo e($menu->is_active ? 'Sembunyikan' : 'Tampilkan'); ?>">
              <i class="fa-solid <?php echo e($menu->is_active ? 'fa-eye' : 'fa-eye-slash'); ?>" style="font-size:12px"></i>
            </button>
          </form>
          <a href="<?php echo e(route('admin.nav-menu.edit.page', $menu)); ?>" class="btn btn-outline-secondary btn-sm" style="width:32px;height:32px;padding:0">
            <i class="fa-regular fa-pen-to-square" style="font-size:12px"></i>
          </a>
          <form method="POST" action="<?php echo e(route('admin.nav-menu.delete', $menu)); ?>"
                data-confirm="Hapus menu utama &quot;<?php echo e($menu->label); ?>&quot;? Semua submenu di bawahnya ikut terhapus."
                data-confirm-title="Hapus Menu Utama" data-confirm-style="danger" data-confirm-label="Ya, Hapus">
            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
            <button type="submit" class="btn btn-outline-danger btn-sm" style="width:32px;height:32px;padding:0">
              <i class="fa-regular fa-trash-can" style="font-size:12px"></i>
            </button>
          </form>
        </div>
      </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
      <div class="text-center py-5">
        <p class="small text-dark mb-1">Belum ada menu utama.</p>
        <p class="text-muted mb-3" style="font-size:12px">Mulai dengan membuat menu utama pertama.</p>
        <a href="<?php echo e(route('admin.nav-menu.add.page')); ?>" class="btn btn-primary btn-sm">+ Tambah Menu Utama</a>
      </div>
    <?php endif; ?>
  </div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/admin/nav-menus/index.blade.php ENDPATH**/ ?>