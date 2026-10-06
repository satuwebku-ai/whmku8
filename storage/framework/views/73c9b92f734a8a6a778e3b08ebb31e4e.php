<?php $__env->startSection('title', 'Produk'); ?>

<?php $__env->startSection('content'); ?>
  <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <div>
      <h1 class="h4 fw-bold text-dark mb-1">Produk</h1>
      <p class="small text-muted mb-0">Katalog paket hosting/layanan yang dijual di halaman publik.</p>
    </div>
    <div class="d-flex align-items-center gap-2">
      <a href="<?php echo e(route('admin.product-categories.index')); ?>" class="btn btn-outline-secondary btn-sm"><i class="fa-solid fa-folder" style="font-size:11px"></i> Kategori</a>
      <a href="<?php echo e(route('admin.addons.index')); ?>" class="btn btn-outline-secondary btn-sm"><i class="fa-solid fa-puzzle-piece" style="font-size:11px"></i> Addons</a>
      <a href="<?php echo e(route('admin.products.create')); ?>" class="btn btn-primary btn-sm"><i class="fa-solid fa-plus" style="font-size:11px"></i> Tambah Produk</a>
    </div>
  </div>

  
  <?php $t = request('type'); ?>
  <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
    <a href="<?php echo e(route('admin.products.index')); ?>" class="px-3 py-2 rounded-pill small fw-medium text-decoration-none <?php echo e(! $t ? 'text-white' : 'text-muted'); ?>" style="<?php echo e(! $t ? 'background:#4f46e5' : 'background:#f1f5f9'); ?>">
      Semua (<?php echo e($counts['all']); ?>)
    </a>
    <a href="<?php echo e(route('admin.products.index', ['type' => 'hosting'])); ?>" class="px-3 py-2 rounded-pill small fw-medium text-decoration-none <?php echo e($t === 'hosting' ? 'text-white' : 'text-muted'); ?>" style="<?php echo e($t === 'hosting' ? 'background:#4f46e5' : 'background:#f1f5f9'); ?>">
      <i class="fa-solid fa-server" style="font-size:10px"></i> Hosting (<?php echo e($counts['hosting']); ?>)
    </a>
    <a href="<?php echo e(route('admin.products.index', ['type' => 'vps'])); ?>" class="px-3 py-2 rounded-pill small fw-medium text-decoration-none <?php echo e($t === 'vps' ? 'text-white' : 'text-muted'); ?>" style="<?php echo e($t === 'vps' ? 'background:#059669' : 'background:#f1f5f9'); ?>">
      <i class="fa-solid fa-cloud" style="font-size:10px"></i> VPS / Cloud (<?php echo e($counts['vps']); ?>)
    </a>
  </div>

  <div class="card border rounded-4 overflow-hidden">
    <form method="GET" class="px-4 py-3 border-bottom d-flex flex-wrap align-items-center gap-2">
      <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari nama produk..." class="form-control form-control-sm" style="max-width:16rem;flex:1 1 180px">
      <select name="category_id" class="form-select" style="padding:.25rem .6rem;font-size:.875rem;border-radius:.375rem;max-width:12rem" data-auto-submit>
        <option value="">Semua Kategori</option>
        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <option value="<?php echo e($cat->id); ?>" <?php if(request('category_id') == $cat->id): echo 'selected'; endif; ?>><?php echo e($cat->name); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </select>
      <button type="submit" class="btn btn-outline-secondary btn-sm" style="width:fit-content">Cari</button>
      <?php if(request('search') || request('category_id')): ?>
        <a href="<?php echo e(route('admin.products.index')); ?>" class="btn btn-outline-secondary btn-sm" style="width:fit-content">Reset</a>
      <?php endif; ?>
    </form>

    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead>
          <tr class="small text-uppercase text-muted" style="background:#f8fafc">
            <th class="px-4 py-3">Produk</th>
            <th class="py-3">Kategori</th>
            <th class="text-end py-3">Mulai Dari</th>
            <th class="py-3">Domain</th>
            <th class="py-3">Status</th>
            <th class="text-end px-4 py-3">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
              <td class="px-4 py-3">
                <?php
                  $isVps = $product->server_id && $cloudServerIds->contains($product->server_id);
                  $spec = $isVps ? json_decode((string) $product->panel_package, true) : null;
                ?>
                <p class="fw-medium text-dark mb-0">
                  <?php echo e($product->name); ?>

                  <?php if($isVps): ?>
                    <span class="badge badge-soft-success ms-1" style="font-size:9px"><i class="fa-solid fa-cloud"></i> VPS</span>
                  <?php endif; ?>
                  <?php if($product->is_featured): ?>
                    <i class="fa-solid fa-star text-warning ms-1" style="font-size:10px" title="Unggulan"></i>
                  <?php endif; ?>
                </p>
                <?php if($isVps && is_array($spec) && isset($spec['vcpu'])): ?>
                  <p class="text-muted mb-0" style="font-size:11px">
                    <?php echo e($spec['vcpu']); ?> vCPU · <?php echo e($spec['ram']); ?> MB · <?php echo e($spec['disk']); ?> GB · <?php echo e($spec['os_name'] ?? ''); ?>

                  </p>
                <?php elseif($isVps): ?>
                  <p class="mb-0" style="font-size:11px;color:#b45309">
                    <i class="fa-solid fa-triangle-exclamation"></i> Spesifikasi VPS belum diisi
                  </p>
                <?php elseif($product->tagline): ?>
                  <p class="text-muted mb-0" style="font-size:12px"><?php echo e($product->tagline); ?></p>
                <?php endif; ?>
              </td>
              <td class="text-muted py-3"><?php echo e($product->category->name ?? '—'); ?></td>
              <td class="text-end text-dark py-3">
                <?php if($product->starting_price !== null): ?>
                  Rp <?php echo e(number_format($product->starting_price, 0, ',', '.')); ?>

                <?php else: ?>
                  <span class="text-danger" style="font-size:12px">Belum ada harga</span>
                <?php endif; ?>
              </td>
              <td class="text-muted text-capitalize py-3" style="font-size:12px"><?php echo e($product->domain_option); ?></td>
              <td class="py-3">
                <span class="badge <?php echo e($product->is_active ? 'badge-soft-success' : 'badge-soft-secondary'); ?>"><?php echo e($product->is_active ? 'Aktif' : 'Nonaktif'); ?></span>
              </td>
              <td class="text-end px-4 py-3">
                <div class="d-flex align-items-center justify-content-end gap-2">
                  <form method="POST" action="<?php echo e(route('admin.product.status')); ?>">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="product_id" value="<?php echo e($product->id); ?>">
                    <button type="submit" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center justify-content-center" style="width:32px;height:32px;padding:0" title="<?php echo e($product->is_active ? 'Nonaktifkan' : 'Aktifkan'); ?>">
                      <i class="fa-solid <?php echo e($product->is_active ? 'fa-toggle-on' : 'fa-toggle-off'); ?>" style="font-size:12px"></i>
                    </button>
                  </form>
                  <?php if($product->is_active && $product->category): ?>
                    <a href="<?php echo e($product->category->productUrl($product)); ?>" target="_blank" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center justify-content-center" style="width:32px;height:32px;padding:0" title="Lihat di katalog">
                      <i class="fa-solid fa-arrow-up-right-from-square" style="font-size:12px"></i>
                    </a>
                  <?php endif; ?>
                  <a href="<?php echo e(route('admin.products.edit', $product)); ?>" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center justify-content-center" style="width:32px;height:32px;padding:0" title="Edit">
                    <i class="fa-regular fa-pen-to-square" style="font-size:12px"></i>
                  </a>
                  <form method="POST" action="<?php echo e(route('admin.products.destroy', $product)); ?>"
                        data-confirm="Hapus produk <?php echo e($product->name); ?>?" data-confirm-title="Hapus Produk"
                        data-confirm-style="danger" data-confirm-label="Ya, Hapus">
                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-outline-danger btn-sm d-inline-flex align-items-center justify-content-center" style="width:32px;height:32px;padding:0" title="Hapus">
                      <i class="fa-regular fa-trash-can" style="font-size:12px"></i>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr><td colspan="6" class="text-center text-muted py-5">Belum ada produk. Buat kategori dulu kalau belum ada.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
    <?php if($products->hasPages()): ?>
      <div class="px-4 py-3 border-top"><?php echo e($products->links('pagination.bootstrap')); ?></div>
    <?php endif; ?>
  </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/admin/products/index.blade.php ENDPATH**/ ?>