<?php $__env->startSection('title', 'Cek Domain'); ?>

<?php $__env->startSection('content'); ?>

  <?php echo $__env->make('admin.domains._nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

  <div class="mb-4">
    <h1 class="h4 fw-bold text-dark mb-1">Cek Ketersediaan Domain</h1>
    <p class="small text-muted mb-0">Cari domain lewat registrar aktif (default), hasil dicek untuk semua TLD yang sudah diberi harga.</p>
  </div>

  <form method="GET" action="<?php echo e(route('admin.domain.search')); ?>" class="card border rounded-4 p-4 mb-4 d-flex flex-wrap flex-sm-row gap-2">
    <input type="text" name="domain" value="<?php echo e($query); ?>" placeholder="contoh: namahosting" class="form-control form-control-sm flex-grow-1" style="min-width:200px" required>
    <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-magnifying-glass" style="font-size:11px"></i> Cek Domain</button>
  </form>

  <?php if($tldPrices->isEmpty()): ?>
    <div class="card border rounded-4 p-3 mb-4" style="background:#fffbeb;border-color:#fde68a!important">
      <p class="mb-0" style="font-size:13px;color:#92400e">
        <i class="fa-solid fa-triangle-exclamation"></i>
        Belum ada TLD aktif. Tambahkan dulu di tab <a href="<?php echo e(route('admin.tlds.index')); ?>" class="text-decoration-underline fw-medium" style="color:inherit">TLD Pricing</a>,
        supaya sistem tahu ekstensi mana saja yang perlu dicek dan berapa harga jualnya.
      </p>
    </div>
  <?php endif; ?>

  <?php if($query): ?>
    <div class="card border rounded-4 overflow-hidden">
      <?php if(! $results['success']): ?>
        <div class="p-4 text-danger d-flex align-items-center gap-2" style="font-size:14px">
          <i class="fa-solid fa-circle-exclamation"></i> <?php echo e($results['message']); ?>

        </div>
      <?php else: ?>
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead>
              <tr class="small text-uppercase text-muted" style="background:#f8fafc">
                <th class="px-4 py-3">Domain</th>
                <th class="py-3">Ketersediaan</th>
                <th class="text-end py-3">Harga Register</th>
                <th class="text-end px-4 py-3">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php $__empty_1 = true; $__currentLoopData = $results['results']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $domainName => $available): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                  $ext = '.' . \Illuminate\Support\Str::after($domainName, '.');
                  $tld = $tldPrices->get($ext);
                ?>
                <tr>
                  <td class="px-4 py-3 fw-medium text-dark"><?php echo e($domainName); ?></td>
                  <td class="py-3">
                    <?php if($available === true): ?>
                      <span class="badge badge-soft-success"><i class="fa-solid fa-circle-check"></i> Tersedia</span>
                    <?php elseif($available === null): ?>
                      <span class="badge badge-soft-warning"><i class="fa-solid fa-circle-question"></i> Belum Pasti</span>
                    <?php else: ?>
                      <span class="badge badge-soft-danger"><i class="fa-solid fa-circle-xmark"></i> Sudah Terdaftar</span>
                    <?php endif; ?>
                  </td>
                  <td class="text-end text-dark py-3">
                    <?php if($tld): ?>
                      Rp <?php echo e(number_format($tld->register_price, 0, ',', '.')); ?>

                      <span class="d-block text-muted" style="font-size:10px">1 tahun</span>
                      <?php if($tld->max_years >= 2): ?>
                        <span class="d-block text-muted" style="font-size:10px">
                          2 th: Rp <?php echo e(number_format($tld->priceForYears(2), 0, ',', '.')); ?>

                          <?php if($tld->hasYearOverride(2)): ?>
                            <span class="text-success">(diskon)</span>
                          <?php endif; ?>
                        </span>
                      <?php endif; ?>
                    <?php else: ?>
                      —
                    <?php endif; ?>
                  </td>
                  <td class="text-end px-4 py-3">
                    <?php if($available === true): ?>
                      <a href="<?php echo e(route('admin.domain.add.page', ['domain' => $domainName])); ?>" class="btn btn-primary btn-sm">
                        <i class="fa-solid fa-cart-plus" style="font-size:11px"></i> Daftarkan
                      </a>
                    <?php else: ?>
                      <span class="text-muted" style="font-size:12px">—</span>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                  <td colspan="4" class="text-center py-5">
                    <p class="text-muted mb-1" style="font-size:14px"><?php echo e($results['message'] ?? 'Tidak ada hasil.'); ?></p>
                    <p class="text-muted mb-0" style="font-size:11px">
                      Kalau ini terjadi padahal registrar sudah "Terhubung", kemungkinan format respons
                      registrar berbeda dari yang diharapkan. Respons mentahnya sudah dicatat di
                      <code>storage/logs/laravel.log</code>.
                    </p>
                  </td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>
  <?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/admin/domains/search.blade.php ENDPATH**/ ?>