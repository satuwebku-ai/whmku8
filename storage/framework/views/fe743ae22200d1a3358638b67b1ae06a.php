<?php $__env->startSection('title', 'ID Protection'); ?>

<?php $__env->startSection('content'); ?>

  <?php echo $__env->make('admin.domains._nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

  <div class="mb-4">
    <h1 class="h4 fw-bold text-dark mb-1">ID Protection (WHOIS Privacy)</h1>
    <p class="small text-muted mb-0">
      Tiga tingkat harga, dari yang paling spesifik: <b>per-TLD</b> &rarr; <b>per-registrar</b> &rarr; <b>global (default)</b>.
      Kosongkan kolom harga di tabel manapun untuk jatuh ke tingkat di atasnya.
    </p>
  </div>

  
  <div class="card border rounded-4 p-4 mb-4" style="max-width:28rem">
    <h2 class="small fw-bold text-dark mb-1">Harga Default (Global)</h2>
    <p class="text-muted mb-3" style="font-size:12px">
      Dipakai kalau registrar dan TLD-nya tidak punya harga sendiri.
      Ditampilkan sebagai opsi tambahan di halaman Keranjang saat klien mendaftarkan domain baru.
    </p>
    <form method="POST" action="<?php echo e(route('admin.tlds.addon-pricing')); ?>">
      <?php echo csrf_field(); ?>
      <div class="mb-2">
        <label class="form-label small fw-medium text-dark">Harga ID Protection (per tahun)</label>
        <input type="number" name="whois_privacy_price" min="0" step="1000"
               value="<?php echo e(\App\Models\Setting::get('whois_privacy_price', 0)); ?>" class="form-control form-control-sm">
        <p class="text-muted mt-1 mb-0" style="font-size:11px">Kosongkan / isi 0 untuk menjadikannya gratis.</p>
      </div>
      <button type="submit" class="btn btn-primary btn-sm">Simpan Harga Default</button>
    </form>
  </div>

  
  <div class="card border rounded-4 overflow-hidden mb-4">
    <div class="px-4 py-3 border-bottom">
      <h2 class="small fw-bold text-dark mb-1">Harga per Registrar</h2>
      <p class="text-muted mb-0" style="font-size:12px">
        Kosongkan supaya registrar itu ikut harga default di atas.
      </p>
    </div>

    <form method="POST" action="<?php echo e(route('admin.tlds.privacy.registrars')); ?>">
      <?php echo csrf_field(); ?>
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead>
            <tr class="small text-uppercase text-muted" style="background:#f8fafc">
              <th class="px-4 py-3">Registrar</th>
              <th class="text-end py-3" style="width:12rem">Harga ID Protection (Rp)</th>
              <th class="px-4 py-3"></th>
            </tr>
          </thead>
          <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $registrars; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $registrar): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
              <tr>
                <td class="px-4 py-2 fw-medium text-dark">
                  <?php echo e($registrar->name); ?>

                  <?php if(! $registrar->is_active): ?>
                    <span class="badge badge-soft-secondary ms-1" style="font-size:9px">Nonaktif</span>
                  <?php endif; ?>
                </td>
                <td class="text-end py-2">
                  <input type="number" step="1" min="0"
                         name="registrars[<?php echo e($registrar->id); ?>][whois_privacy_price]"
                         value="<?php echo e($registrar->whois_privacy_price !== null ? (int) $registrar->whois_privacy_price : ''); ?>"
                         placeholder="<?php echo e(number_format((float) \App\Models\Setting::get('whois_privacy_price', 0), 0, ',', '.')); ?>"
                         class="form-control form-control-sm text-end" style="width:9rem;margin-left:auto">
                </td>
                <td class="px-4 py-2"></td>
              </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
              <tr><td colspan="3" class="text-center text-muted py-4">Belum ada registrar terdaftar.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
      <?php if($registrars->isNotEmpty()): ?>
        <div class="px-4 py-3 border-top">
          <button type="submit" class="btn btn-primary btn-sm">Simpan Harga Registrar</button>
        </div>
      <?php endif; ?>
    </form>
  </div>

  
  <div class="card border rounded-4 overflow-hidden">
    <div class="px-4 py-3 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
      <div>
        <h2 class="small fw-bold text-dark mb-1">Eligibilitas &amp; Harga per TLD</h2>
        <p class="text-muted mb-0" style="font-size:12px">
          TLD di bawah <code>.id</code> dilarang PANDI menawarkan WHOIS Privacy — centang cuma untuk yang benar-benar boleh.
        </p>
      </div>
      <form method="GET" class="d-flex gap-2 flex-wrap">
        <select name="registrar" class="form-select form-select-sm" style="width:11rem">
          <option value="">Semua Registrar</option>
          <option value="none" <?php if(request('registrar') === 'none'): echo 'selected'; endif; ?>>— Tidak ditentukan —</option>
          <?php $__currentLoopData = $registrars; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($r->id); ?>" <?php if((string) request('registrar') === (string) $r->id): echo 'selected'; endif; ?>><?php echo e($r->name); ?></option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari ekstensi..." class="form-control form-control-sm" style="width:11rem">
        <button type="submit" class="btn btn-outline-secondary btn-sm">Cari</button>
        <?php if(request('search') || request('registrar')): ?>
          <a href="<?php echo e(route('admin.tlds.privacy')); ?>" class="btn btn-outline-secondary btn-sm">Reset</a>
        <?php endif; ?>
      </form>
    </div>

    <form method="POST" action="<?php echo e(route('admin.tlds.privacy.tlds')); ?>">
      <?php echo csrf_field(); ?>
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead>
            <tr class="small text-uppercase text-muted" style="background:#f8fafc">
              <th class="px-4 py-3">Ekstensi</th>
              <th class="text-center py-3" style="width:8rem">Boleh Ditawari</th>
              <th class="text-end py-3" style="width:12rem">Harga Khusus (Rp)</th>
              <th class="px-4 py-3"></th>
            </tr>
          </thead>
          <tbody>
            <?php
              $hargaDefault = (float) \App\Models\Setting::get('whois_privacy_price', 0);
            ?>

            <?php $__empty_1 = true; $__currentLoopData = $tlds; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tld): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
              <?php
                // Harga yang BENAR-BENAR berlaku untuk TLD ini, mengikuti
                // urutan yang sama dengan CartService::privacyPriceFor():
                // harga per-TLD -> harga registrar -> harga default.
                $hargaRegistrar = $tld->registrar?->whois_privacy_price;

                if ($tld->whois_privacy_price !== null) {
                    $berlaku = (float) $tld->whois_privacy_price;
                    $sumber = 'khusus TLD ini';
                } elseif ($hargaRegistrar !== null) {
                    $berlaku = (float) $hargaRegistrar;
                    $sumber = 'dari ' . ($tld->registrar->name ?? 'registrar');
                } else {
                    $berlaku = $hargaDefault;
                    $sumber = 'dari harga default';
                }

                $idFamily = $tld->extension === '.id' || str_ends_with($tld->extension, '.id');
              ?>
              <tr style="<?php echo e($idFamily && $tld->whois_privacy_eligible ? 'background:rgba(239,68,68,.05)' : ''); ?>">
                <td class="px-4 py-2 fw-medium text-dark">
                  <?php echo e($tld->extension); ?>

                  <?php if($idFamily && $tld->whois_privacy_eligible): ?>
                    <span class="badge" style="font-size:9px;background:#fee2e2;color:#991b1b" title="Domain .id dan turunannya dilarang PANDI menawarkan WHOIS Privacy">
                      langgar PANDI
                    </span>
                  <?php endif; ?>
                  <span class="d-block fw-normal text-muted" style="font-size:10px"><?php echo e($tld->registrar->name ?? 'manual'); ?></span>
                </td>
                <td class="text-center py-2">
                  <input type="checkbox" name="eligible[]" value="<?php echo e($tld->id); ?>" <?php if($tld->whois_privacy_eligible): echo 'checked'; endif; ?> style="margin:0">
                </td>
                <td class="text-end py-2">
                  <input type="number" step="1" min="0"
                         name="rows[<?php echo e($tld->id); ?>][whois_privacy_price]"
                         value="<?php echo e($tld->whois_privacy_price !== null ? (int) $tld->whois_privacy_price : ''); ?>"
                         placeholder="<?php echo e($berlaku > 0 ? number_format($berlaku, 0, ',', '.') : 'Gratis'); ?>"
                         class="form-control form-control-sm text-end" style="width:9rem;margin-left:auto">
                  
                  <span class="d-block text-muted mt-1" style="font-size:10px">
                    Berlaku: <b><?php echo e($berlaku > 0 ? 'Rp ' . number_format($berlaku, 0, ',', '.') : 'Gratis'); ?></b>
                    <span style="opacity:.7">(<?php echo e($sumber); ?>)</span>
                  </span>
                </td>
                <td class="px-4 py-2"></td>
              </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
              <tr><td colspan="4" class="text-center text-muted py-4">Tidak ada TLD yang cocok.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

      <?php if($tlds->isNotEmpty()): ?>
        <div class="px-4 py-3 border-top d-flex align-items-center justify-content-between flex-wrap gap-2">
          <button type="submit" class="btn btn-primary btn-sm">Simpan Perubahan TLD</button>
          <?php echo e($tlds->links('pagination.bootstrap')); ?>

        </div>
      <?php endif; ?>
    </form>
  </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/admin/tlds/privacy.blade.php ENDPATH**/ ?>