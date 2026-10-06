<?php $__env->startSection('title', 'Diagnosa Registrar — ' . $registrar->name); ?>

<?php $__env->startSection('content'); ?>

  <a href="<?php echo e(route('admin.registrars.index')); ?>" class="text-decoration-none text-muted" style="font-size:12px">
    <i class="fa-solid fa-arrow-left"></i> Kembali ke Registrar
  </a>
  <h1 class="h4 fw-bold text-dark mt-1 mb-2">Diagnosa — <?php echo e($registrar->name); ?></h1>
  <p class="small text-muted mb-4">
    Data langsung dari API <?php echo e(ucfirst($registrar->provider)); ?> — cuma membaca, tidak mengubah apa pun di akunmu.
  </p>

  <?php if(! empty($apiErrors)): ?>
    <?php $__currentLoopData = $apiErrors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $err): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <div class="card border rounded-3 p-3 mb-3" style="border-color:#fecaca!important;background:#fef2f2">
        <p class="mb-0" style="font-size:14px;color:#991b1b"><i class="fa-solid fa-circle-exclamation"></i> <?php echo e($err); ?></p>
      </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  <?php endif; ?>

  <div class="row g-3">

    
    <div class="col-12 col-lg-4">
      <div class="card border rounded-4 p-4 h-100" style="<?php echo e($details && $details['selling_currency'] ? 'border-color:#a7f3d0!important;background:rgba(16,185,129,.04)' : ''); ?>">
        <h2 class="small fw-bold text-dark mb-3">Mata Uang Akun</h2>
        <?php if($details && $details['selling_currency']): ?>
          <p class="fw-bold text-dark mb-2" style="font-size:1.75rem"><?php echo e($details['selling_currency']); ?></p>
          <p class="text-muted mb-0" style="font-size:12px">
            Semua angka harga &amp; saldo dari API ini dalam satuan <b class="text-dark"><?php echo e($details['selling_currency']); ?></b>.
            <?php if($details['selling_currency'] === 'USD'): ?>
              <br>Artinya kolom "Customer Price" di dashboard Liqu.id juga dalam USD — bukan ribuan Rupiah,
              meski labelnya tertulis begitu.
            <?php endif; ?>
          </p>
        <?php else: ?>
          <p class="text-muted mb-0" style="font-size:14px">Tidak bisa diambil — lihat pesan galat di atas.</p>
        <?php endif; ?>

        <?php if($details && ($details['name'] || $details['company'])): ?>
          <div class="mt-3 pt-3 border-top text-muted" style="font-size:12px">
            <?php if($details['name']): ?> <p class="mb-1">Nama: <?php echo e($details['name']); ?></p> <?php endif; ?>
            <?php if($details['company']): ?> <p class="mb-0">Perusahaan: <?php echo e($details['company']); ?></p> <?php endif; ?>
          </div>
        <?php endif; ?>
      </div>
    </div>

    
    <div class="col-12 col-lg-4">
      <div class="card border rounded-4 p-4 h-100">
        <h2 class="small fw-bold text-dark mb-3">Saldo Deposit</h2>
        <?php if($balance): ?>
          <p class="fw-bold mb-2 <?php echo e($balance['balance'] < 20 ? 'text-danger' : 'text-dark'); ?>" style="font-size:1.75rem">
            <?php echo e($details['selling_currency'] ?? ''); ?> <?php echo e(number_format($balance['balance'], 2)); ?>

          </p>
          <?php if($balance['balance'] < 20): ?>
            <p class="text-danger mb-0" style="font-size:12px">
              <i class="fa-solid fa-triangle-exclamation"></i>
              Saldo tipis. Registrasi satu domain .com saja butuh sekitar USD 53 —
              pastikan deposit cukup sebelum ada klien yang membeli.
            </p>
          <?php endif; ?>
        <?php else: ?>
          <p class="text-muted mb-0" style="font-size:14px">Tidak bisa diambil — lihat pesan galat di atas.</p>
        <?php endif; ?>
      </div>
    </div>
  </div>

  
  <div class="card border rounded-4 p-4 mt-3">
    <h2 class="small fw-bold text-dark mb-1">Contoh Format Harga (data mentah)</h2>
    <p class="text-muted mb-3" style="font-size:12px">
      Tiga baris pertama dari daftar harga akunmu — untuk memastikan format angka yang sebenarnya
      dikembalikan API, bukan tebakan.
    </p>
    <pre class="rounded-3 p-3 mb-0" style="background:#1e293b;color:#f1f5f9;font-size:12px;overflow-x:auto"><?php echo e(json_encode($priceSample, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)); ?></pre>
  </div>

  
  <div class="card border rounded-4 overflow-hidden mt-3">
    <div class="px-4 py-3 border-bottom">
      <h2 class="small fw-bold text-dark mb-0">Customer Terbaru di Liqu.id</h2>
      <p class="text-muted mt-1 mb-0" style="font-size:11px">20 customer terakhir yang tercatat di akun ini — termasuk yang dibuat otomatis saat ada klien mendaftarkan domain.</p>
    </div>
    <div>
      <?php $__empty_1 = true; $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="px-4 py-3 border-bottom">
          <p class="text-dark mb-0" style="font-size:14px"><?php echo e($c['name'] ?: '(tanpa nama)'); ?> <span class="text-muted" style="font-size:11px">— ID <?php echo e($c['id']); ?></span></p>
          <p class="text-muted mb-0" style="font-size:11px"><?php echo e($c['email'] ?: '—'); ?> <?php if($c['company']): ?> · <?php echo e($c['company']); ?> <?php endif; ?></p>
        </div>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <p class="text-center text-muted py-4 mb-0" style="font-size:14px">Belum ada customer tercatat, atau gagal diambil — lihat pesan galat di atas.</p>
      <?php endif; ?>
    </div>
  </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/admin/registrars/diagnostics.blade.php ENDPATH**/ ?>