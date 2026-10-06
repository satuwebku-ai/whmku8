<?php $__env->startSection('title', 'Diagnosa Server — ' . $server->name); ?>

<?php $__env->startSection('content'); ?>

  <a href="<?php echo e(route('admin.servers.index')); ?>" class="text-decoration-none text-muted" style="font-size:12px">
    <i class="fa-solid fa-arrow-left"></i> Kembali ke Server
  </a>
  <h1 class="h4 fw-bold text-dark mt-1 mb-2">Diagnosa — <?php echo e($server->name); ?></h1>
  <p class="small text-muted mb-4">
    Data langsung dari <?php echo e(ucfirst($server->panel)); ?> — cuma membaca, tidak mengubah apa pun di server.
  </p>

  <?php if($apiError): ?>
    <div class="card border rounded-4 p-3 mb-4 small" style="background:#fef2f2;border-color:#fecaca!important;color:#991b1b">
      <i class="fa-solid fa-circle-exclamation"></i> <?php echo e($apiError); ?>

    </div>
  <?php endif; ?>

  
  <div class="card border rounded-4 overflow-hidden mb-4">
    <div class="px-4 py-3 border-bottom">
      <h2 class="small fw-bold text-dark mb-0">Hosting Account vs Kondisi Sungguhan di WHM</h2>
      <p class="text-muted mb-0 mt-1" style="font-size:12px">Domain di kolom kanan harus ADA di server — kalau tidak, akunnya belum pernah benar-benar dibuat (biasanya karena provisioning gagal).</p>
    </div>

    <?php if($accountsError): ?>
      <div class="px-4 py-3 small text-danger" style="background:#fef2f2">
        <i class="fa-solid fa-circle-exclamation"></i> <?php echo e($accountsError); ?>

      </div>
    <?php endif; ?>

    <div>
      <?php $__empty_1 = true; $__currentLoopData = $ourAccounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $acc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="d-flex align-items-center justify-content-between px-4 py-3 border-bottom">
          <div>
            <p class="small text-dark mb-0"><?php echo e($acc['domain']); ?> <span class="text-muted" style="font-size:11px">(ID <?php echo e($acc['id']); ?>)</span></p>
            <p class="text-muted mb-0" style="font-size:11px">Status: <?php echo e($acc['status']); ?> · Provisioning: <?php echo e($acc['provision_status']); ?></p>
          </div>
          <?php if($acc['ada_di_whm']): ?>
            <span class="badge badge-soft-success"><i class="fa-solid fa-check" style="font-size:10px"></i> Ada di server</span>
          <?php else: ?>
            <span class="badge badge-soft-danger"><i class="fa-solid fa-xmark" style="font-size:10px"></i> TIDAK ada di server</span>
          <?php endif; ?>
        </div>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <p class="text-center text-muted small py-4 mb-0">Belum ada Hosting Account yang tercatat untuk server ini.</p>
      <?php endif; ?>
    </div>

    <?php if(! empty($orphanWhmDomains)): ?>
      <div class="px-4 py-3 border-top" style="background:#fffbeb">
        <p class="fw-medium mb-1" style="font-size:12px;color:#92400e">
          <i class="fa-solid fa-triangle-exclamation"></i> Ada di server tapi TIDAK tercatat di sistem kita (<?php echo e(count($orphanWhmDomains)); ?>):
        </p>
        <p class="mb-1" style="font-size:12px;color:#b45309"><?php echo e(implode(', ', $orphanWhmDomains)); ?></p>
        <p class="mb-0" style="font-size:11px;color:#d97706">Biasanya ini dibuat manual langsung di WHM, di luar Lumora — atau sisa dari reseller lain di server yang sama.</p>
      </div>
    <?php endif; ?>
  </div>

  
  <div class="card border rounded-4 overflow-hidden mb-4">
    <div class="px-4 py-3 border-bottom">
      <h2 class="small fw-bold text-dark mb-0">Produk yang Terhubung ke Server Ini</h2>
      <p class="text-muted mb-0 mt-1" style="font-size:12px">Nama paket di kolom kanan harus PERSIS sama dengan yang ada di WHM (besar-kecil huruf ikut berpengaruh).</p>
    </div>
    <div>
      <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="d-flex align-items-center justify-content-between px-4 py-3 border-bottom">
          <p class="small text-dark mb-0"><?php echo e($p['name']); ?></p>
          <div class="d-flex align-items-center gap-2">
            <code class="px-2 py-1 rounded" style="font-size:11px;background:#f1f5f9"><?php echo e($p['panel_package'] ?: '(kosong — mode manual)'); ?></code>
            <?php if(is_null($p['matches'])): ?>
              <span class="badge badge-soft-secondary">Manual</span>
            <?php elseif($p['matches']): ?>
              <span class="badge badge-soft-success"><i class="fa-solid fa-check" style="font-size:10px"></i> Cocok</span>
            <?php else: ?>
              <span class="badge badge-soft-danger"><i class="fa-solid fa-xmark" style="font-size:10px"></i> Tidak ditemukan di server</span>
            <?php endif; ?>
          </div>
        </div>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <p class="text-center text-muted small py-4 mb-0">Belum ada produk yang terhubung ke server ini.</p>
      <?php endif; ?>
    </div>
  </div>

  
  <div class="card border rounded-4 p-4">
    <h2 class="small fw-bold text-dark mb-2">Semua Paket di Server Ini (<?php echo e(count($packages)); ?>)</h2>
    <?php if(count($packages)): ?>
      <div class="d-flex flex-wrap gap-2">
        <?php $__currentLoopData = $packages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pkg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <code class="px-2 py-1 rounded" style="font-size:11px;background:#f1f5f9"><?php echo e($pkg); ?></code>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </div>
    <?php else: ?>
      <p class="text-muted small mb-0">Tidak bisa diambil — lihat pesan galat di atas.</p>
    <?php endif; ?>
  </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/admin/servers/diagnostics.blade.php ENDPATH**/ ?>