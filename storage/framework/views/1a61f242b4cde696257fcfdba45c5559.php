<?php $__env->startSection('title', 'VPS Saya'); ?>

<?php $__env->startSection('content'); ?>
  <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
    <div>
      <h1 class="h4 fw-bold text-dark mb-1">VPS Saya</h1>
      <p class="text-muted mb-0">Kelola mesin virtual Anda — nyalakan, matikan, dan pantau pemakaian.</p>
    </div>
    <a href="<?php echo e(route('client.balance')); ?>" class="card-public px-3 py-2 text-decoration-none">
      <span class="d-block text-muted" style="font-size:11px">Saldo Anda</span>
      <span class="fw-bold <?php echo e((float) $client->balance <= 0 ? 'text-danger' : 'text-dark'); ?>">
        Rp <?php echo e(number_format((float) $client->balance, 0, ',', '.')); ?>

      </span>
    </a>
  </div>

  <div class="d-flex flex-column gap-3">
    <?php $__empty_1 = true; $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vps): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
      <?php
        $rate = $rates[$vps->id] ?? null;
        $hoursLeft = ($rate && $rate > 0) ? floor((float) $client->balance / $rate) : null;
        $spec = $vps->hasVmSpec() ? $vps->vmSpec() : null;
      ?>
      <a href="<?php echo e(route('client.vps.show', $vps)); ?>" class="dash-card dash-card-hover p-4 text-decoration-none d-block">
        <div class="d-flex align-items-start justify-content-between gap-3 flex-wrap">
          <div class="d-flex align-items-start gap-3 min-w-0">
            <span class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0 position-relative" style="width:40px;height:40px;background:<?php echo e($vps->status === 'active' ? 'rgba(21,128,61,.1)' : 'rgba(100,116,139,.1)'); ?>;color:<?php echo e($vps->status === 'active' ? '#15803d' : '#64748b'); ?>">
              <i class="fa-solid fa-server" style="font-size:15px"></i>
              <?php if($vps->status === 'active'): ?>
                <span class="position-absolute rounded-circle" style="top:-2px;right:-2px;width:9px;height:9px;background:#22c55e;border:2px solid #fff"></span>
              <?php endif; ?>
            </span>
            <div class="min-w-0">
              <p class="fw-semibold text-dark mb-0"><?php echo e($vps->domain); ?></p>
              <?php if($spec): ?>
                <p class="text-muted mb-0" style="font-size:12px">
                  <?php echo e($spec['vcpu']); ?> vCPU · <?php echo e($spec['ram']); ?> MB RAM · <?php echo e($spec['disk']); ?> GB Disk
                </p>
              <?php endif; ?>
              <?php if($vps->serverModel): ?>
                <p class="text-muted mb-0" style="font-size:11px"><?php echo e($vps->serverModel->name); ?></p>
              <?php endif; ?>
            </div>
          </div>

          <div class="text-end flex-shrink-0">
            <span class="badge <?php echo e(['active' => 'badge-soft-success', 'pending' => 'badge-soft-warning', 'suspended' => 'badge-soft-danger'][$vps->status] ?? 'badge-soft-secondary'); ?>">
              <?php echo e(['active' => 'Menyala', 'pending' => 'Menunggu', 'suspended' => 'Mati'][$vps->status] ?? ucfirst($vps->status)); ?>

            </span>

            <?php if($vps->billing_mode === 'deposit' && $rate): ?>
              <p class="fw-semibold text-dark mt-2 mb-0" style="font-size:13px">
                Rp <?php echo e(number_format($rate, 2, ',', '.')); ?> / jam
              </p>
              <?php if($hoursLeft !== null): ?>
                <p class="mb-0" style="font-size:11px;<?php echo e($hoursLeft < 24 ? 'color:#b91c1c;font-weight:600' : 'color:#94a3b8'); ?>">
                  <?php if($hoursLeft < 24): ?>
                    <i class="fa-solid fa-triangle-exclamation"></i> Sisa ± <?php echo e($hoursLeft); ?> jam
                  <?php else: ?>
                    ± <?php echo e(intdiv($hoursLeft, 24)); ?> hari lagi
                  <?php endif; ?>
                </p>
              <?php endif; ?>
            <?php elseif($vps->billing_mode === 'invoice'): ?>
              <p class="text-muted mt-2 mb-0" style="font-size:11px">
                Rp <?php echo e(number_format((float) $vps->price, 0, ',', '.')); ?> / <?php echo e(str_replace('_', ' ', $vps->billing_cycle)); ?>

              </p>
            <?php endif; ?>
          </div>
        </div>
      </a>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
      <div class="dash-card p-5 text-center">
        <span class="rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width:44px;height:44px;background:#f1f5f9;color:#94a3b8">
          <i class="fa-solid fa-server"></i>
        </span>
        <p class="fw-medium text-dark mb-1">Belum punya VPS</p>
        <p class="text-muted mb-3" style="font-size:14px">Pilih paket VPS dan mulai dalam hitungan menit.</p>
        <a href="<?php echo e(route('catalog.index')); ?>" class="btn btn-theme mx-auto" style="width:fit-content">Lihat Paket VPS</a>
      </div>
    <?php endif; ?>
  </div>

  <?php if($accounts->isNotEmpty()): ?>
    <p class="text-muted mt-3 mb-0" style="font-size:11px">
      <i class="fa-solid fa-circle-info"></i>
      VPS bersistem saldo dipotong otomatis tiap jam selama menyala. Matikan VPS yang tidak dipakai untuk menghemat saldo.
    </p>
  <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('client.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/themes/client-themes/namahost/client/vps/index.blade.php ENDPATH**/ ?>