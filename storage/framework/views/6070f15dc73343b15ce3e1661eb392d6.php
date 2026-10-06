<?php $__env->startSection('title', 'Harga Reseller/Sub-Reseller'); ?>

<?php $__env->startSection('content'); ?>

  <?php echo $__env->make('admin.domains._nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

  <div class="mb-4 d-flex align-items-start justify-content-between flex-wrap gap-2">
    <div>
      <h1 class="h4 fw-bold text-dark mb-1">Harga Reseller/Sub-Reseller</h1>
      <p class="small text-muted mb-0" style="max-width:48rem">
        Harga yang DISARANKAN registrar untuk pelanggan/sub-reseller mereka sendiri — beda dari
        <b>TLD Pricing</b> (yang menyimpan harga modal &amp; jual kita). Halaman ini murni
        referensi untuk menyusun paket harga sub-reseller sendiri; tidak ada yang tersimpan
        otomatis ke sistem dari sini.
      </p>
    </div>

    <form method="GET" class="d-flex gap-2">
      <select name="registrar" class="form-select form-select-sm" style="width:14rem" data-auto-submit>
        <option value="">— Pilih Registrar —</option>
        <?php $__currentLoopData = $registrars; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <option value="<?php echo e($r->id); ?>" <?php if($selected && $selected->id === $r->id): echo 'selected'; endif; ?>><?php echo e($r->name); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </select>
    </form>
  </div>

  <?php if(! $selected): ?>
    <div class="card border rounded-4 p-5 text-center text-muted">
      Pilih registrar dulu di atas untuk melihat harga pelanggan &amp; sub-reseller-nya.
    </div>
  <?php else: ?>

    <?php $__currentLoopData = $apiErrors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <div class="alert alert-warning py-2 px-3" style="font-size:13px">
        <i class="fa-solid fa-triangle-exclamation"></i> <?php echo e($error); ?>

      </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    
    <?php if($customerPricing !== null): ?>
      <div class="card border rounded-4 overflow-hidden mb-4">
        <div class="px-4 py-3 border-bottom">
          <h2 class="small fw-bold text-dark mb-1">Harga untuk Pelanggan — <?php echo e($selected->name); ?></h2>
          <p class="text-muted mb-0" style="font-size:12px">
            Dari <code>GET /customer-tld-pricings</code>. Ini harga yang <?php echo e($selected->name); ?> sarankan
            ke pelanggan MEREKA, bukan harga modal yang kita bayar (lihat TLD Pricing untuk itu).
          </p>
        </div>
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead>
              <tr class="small text-uppercase text-muted" style="background:#f8fafc">
                <th class="px-4 py-3">Ekstensi</th>
                <th class="text-end py-3">Register/thn</th>
                <th class="text-end py-3">Renewal/thn</th>
                <th class="text-end py-3">Transfer</th>
                <th class="text-center py-3">Premium</th>
              </tr>
            </thead>
            <tbody>
              <?php $__empty_1 = true; $__currentLoopData = $customerPricing; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                  <td class="px-4 py-2 fw-medium text-dark"><?php echo e($row['extension']); ?></td>
                  <td class="text-end py-2"><?php echo e($row['register'] !== null ? number_format($row['register'], 0, ',', '.') . ' ' . $row['currency'] : '—'); ?></td>
                  <td class="text-end py-2"><?php echo e($row['renew'] !== null ? number_format($row['renew'], 0, ',', '.') . ' ' . $row['currency'] : '—'); ?></td>
                  <td class="text-end py-2"><?php echo e($row['transfer'] !== null ? number_format($row['transfer'], 0, ',', '.') . ' ' . $row['currency'] : '—'); ?></td>
                  <td class="text-center py-2">
                    <?php if($row['is_premium']): ?>
                      <span class="badge" style="font-size:9px;background:#fef3c7;color:#92400e">Premium</span>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="5" class="text-center text-muted py-4">Tidak ada data.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    <?php endif; ?>

    
    <?php if($subResellerPricing !== null): ?>
      <div class="mb-2">
        <h2 class="small fw-bold text-dark mb-1">Harga Sub-Reseller — <?php echo e($selected->name); ?></h2>
        <p class="text-muted mb-3" style="font-size:12px">
          Dari <code>GET /sub-reseller-tld-pricings</code>. Dibagi per paket — tiap paket punya syarat
          deposit minimum &amp; batas saldo sendiri.
        </p>
      </div>

      <?php $__empty_1 = true; $__currentLoopData = $subResellerPricing; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $package): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="card border rounded-4 overflow-hidden mb-4">
          <div class="px-4 py-3 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
              <h3 class="small fw-bold text-dark mb-1"><?php echo e($package['package_name']); ?></h3>
              <?php if($package['description']): ?>
                <p class="text-muted mb-0" style="font-size:12px"><?php echo e($package['description']); ?></p>
              <?php endif; ?>
            </div>
            <div class="text-end" style="font-size:12px">
              <div>Deposit minimum: <b><?php echo e($package['minimum_deposit'] !== null ? 'Rp ' . number_format($package['minimum_deposit'], 0, ',', '.') : '—'); ?></b></div>
              <div>Batas saldo: <b><?php echo e($package['balance_limit'] !== null ? 'Rp ' . number_format($package['balance_limit'], 0, ',', '.') : '—'); ?></b></div>
            </div>
          </div>
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
              <thead>
                <tr class="small text-uppercase text-muted" style="background:#f8fafc">
                  <th class="px-4 py-3">Ekstensi</th>
                  <th class="text-end py-3">Register/thn</th>
                  <th class="text-end py-3">Renewal/thn</th>
                  <th class="text-end py-3">Transfer</th>
                  <th class="text-center py-3">Premium</th>
                </tr>
              </thead>
              <tbody>
                <?php $__empty_2 = true; $__currentLoopData = $package['tlds']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                  <tr>
                    <td class="px-4 py-2 fw-medium text-dark"><?php echo e($row['extension']); ?></td>
                    <td class="text-end py-2"><?php echo e($row['register'] !== null ? number_format($row['register'], 0, ',', '.') . ' ' . $row['currency'] : '—'); ?></td>
                    <td class="text-end py-2"><?php echo e($row['renew'] !== null ? number_format($row['renew'], 0, ',', '.') . ' ' . $row['currency'] : '—'); ?></td>
                    <td class="text-end py-2"><?php echo e($row['transfer'] !== null ? number_format($row['transfer'], 0, ',', '.') . ' ' . $row['currency'] : '—'); ?></td>
                    <td class="text-center py-2">
                      <?php if($row['is_premium']): ?>
                        <span class="badge" style="font-size:9px;background:#fef3c7;color:#92400e">Premium</span>
                      <?php endif; ?>
                    </td>
                  </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                  <tr><td colspan="5" class="text-center text-muted py-4">Tidak ada data.</td></tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="card border rounded-4 p-4 text-center text-muted mb-4">Tidak ada paket sub-reseller.</div>
      <?php endif; ?>
    <?php endif; ?>

    <?php if($customerPricing === null && $subResellerPricing === null): ?>
      <div class="card border rounded-4 p-5 text-center text-muted">
        Registrar <?php echo e($selected->name); ?> belum mendukung harga pelanggan/sub-reseller lewat halaman ini.
      </div>
    <?php endif; ?>

  <?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/admin/tlds/registrar-pricing.blade.php ENDPATH**/ ?>