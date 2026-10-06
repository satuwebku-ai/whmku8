<?php $__env->startSection('title', 'Persyaratan per Domain'); ?>
<?php $__env->startSection('content'); ?>
  <?php echo $__env->make('admin.settings._nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

  <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <div>
      <h1 class="h4 fw-bold text-dark mb-1">Persyaratan per Domain</h1>
      <p class="small text-muted mb-0">
        Cari ekstensi domain, lalu centang berkas apa saja yang harus dipenuhi klien sebelum domain itu bisa diproses.
      </p>
    </div>
    <a href="<?php echo e(route('admin.settings.requirements.index')); ?>" class="btn btn-outline-secondary btn-sm">
      <i class="fa-solid fa-list" style="font-size:11px"></i> Kelola Daftar Berkas
    </a>
  </div>

  <?php if($requirements->isEmpty()): ?>
    <div class="card border rounded-4 p-5 text-center">
      <i class="fa-solid fa-folder-open text-muted mb-3" style="font-size:1.75rem"></i>
      <p class="fw-medium text-dark mb-1">Belum ada jenis berkas yang bisa dipetakan</p>
      <p class="text-muted mb-3" style="font-size:13px">Tambahkan dulu jenis berkasnya (KTP, NIB, dst), baru bisa dipetakan ke domain.</p>
      <a href="<?php echo e(route('admin.settings.requirements.create')); ?>" class="btn btn-primary btn-sm mx-auto" style="width:fit-content">
        <i class="fa-solid fa-plus" style="font-size:11px"></i> Tambah Persyaratan
      </a>
    </div>
  <?php else: ?>
    <form method="GET" class="d-flex gap-2 mb-3" style="max-width:26rem">
      <input type="text" name="search" value="<?php echo e($search); ?>" placeholder="Cari ekstensi, mis. .co.id" class="form-control form-control-sm">
      <button type="submit" class="btn btn-outline-secondary btn-sm">Cari</button>
      <?php if($search): ?>
        <a href="<?php echo e(route('admin.settings.requirements.domains')); ?>" class="btn btn-outline-secondary btn-sm">Reset</a>
      <?php endif; ?>
    </form>

    <?php if(! $search): ?>
      <div class="rounded-3 p-3 mb-3" style="background:#f8fafc;border:1px dashed #cbd5e1">
        <p class="text-muted mb-0" style="font-size:11px">
          <i class="fa-solid fa-circle-info"></i>
          Menampilkan ekstensi yang <b>sudah punya persyaratan</b> saja. Untuk menambahkan ke ekstensi lain,
          cari ekstensinya di kotak pencarian di atas.
        </p>
      </div>
    <?php endif; ?>

    <?php
      // Tanpa pencarian, cukup tampilkan yang sudah dipetakan -- daftar
      // penuh bisa ratusan ekstensi dan tidak ada gunanya digulir semua.
      $tampil = $search ? $extensions : $extensions->filter(fn ($e) => ! empty($current[$e] ?? []))->values();
    ?>

    <div class="d-flex flex-column gap-2">
      <?php $__empty_1 = true; $__currentLoopData = $tampil; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ext): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <?php $dipilih = $current[$ext] ?? []; ?>
        <form method="POST" action="<?php echo e(route('admin.settings.requirements.domains.update')); ?>"
              class="card border rounded-4 p-3 <?php echo e($dipilih ? '' : 'bg-white'); ?>"
              style="<?php echo e($dipilih ? 'border-color:#c7d2fe!important;background:rgba(79,70,229,.03)' : ''); ?>">
          <?php echo csrf_field(); ?>
          <input type="hidden" name="extension" value="<?php echo e($ext); ?>">

          <div class="d-flex align-items-center justify-content-between mb-2 flex-wrap gap-2">
            <span class="fw-bold text-dark" style="font-family:monospace;font-size:14px"><?php echo e($ext); ?></span>
            <span class="text-muted" style="font-size:11px">
              <?php if($dipilih): ?>
                <?php echo e(count($dipilih)); ?> berkas diwajibkan
              <?php else: ?>
                Tanpa persyaratan — domain langsung diproses
              <?php endif; ?>
            </span>
          </div>

          <div class="d-flex flex-wrap gap-3 mb-3">
            <?php $__currentLoopData = $requirements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $req): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <label class="d-flex align-items-center gap-2" style="cursor:pointer;font-size:12px">
                <input type="checkbox" name="requirements[]" value="<?php echo e($req->id); ?>"
                       <?php if(in_array($req->id, $dipilih, true)): echo 'checked'; endif; ?> class="form-check-input" style="margin-top:0">
                <?php echo e($req->name); ?>

                <?php if (! ($req->is_required)): ?>
                  <span class="badge badge-soft-secondary" style="font-size:9px">opsional</span>
                <?php endif; ?>
              </label>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </div>

          <button type="submit" class="btn btn-primary btn-sm" style="width:fit-content">
            <i class="fa-solid fa-check" style="font-size:11px"></i> Simpan <?php echo e($ext); ?>

          </button>
        </form>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="card border rounded-4 p-5 text-center">
          <p class="text-muted mb-0" style="font-size:14px">
            <?php if($search): ?>
              Tidak ada ekstensi yang cocok dengan "<?php echo e($search); ?>".
            <?php else: ?>
              Belum ada domain yang dipetakan. Cari ekstensinya di atas untuk mulai menambahkan.
            <?php endif; ?>
          </p>
        </div>
      <?php endif; ?>
    </div>
  <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/admin/settings/requirements/domains.blade.php ENDPATH**/ ?>