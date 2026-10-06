<?php
  $seoTitle = 'Transfer Domain';
  $seoDescription = 'Pindahkan domain Anda dari registrar lain — masa aktif bertambah 1 tahun setelah transfer selesai.';
?>

<?php $__env->startSection('content'); ?>
  <div class="mx-auto" style="max-width:42rem">

    <div class="text-center mb-4">
      <h1 class="fw-bold text-dark" style="font-size:1.6rem">Transfer Domain ke Sini</h1>
      <p class="text-muted mt-2 mb-0">
        Pindahkan domain Anda dari registrar lain. Masa aktif domain <b>bertambah 1 tahun</b> setelah transfer selesai.
      </p>
    </div>

    <div class="card-public p-4 mb-3">
      <form method="POST" action="<?php echo e(route('domains.transfer.submit')); ?>">
        <?php echo csrf_field(); ?>

        <div class="mb-3">
          <label class="form-label">Nama Domain</label>
          <input type="text" name="domain_name" value="<?php echo e(old('domain_name')); ?>"
                 placeholder="contohdomain.com" class="form-control font-monospace" required>
          <?php $__errorArgs = ['domain_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="mb-3">
          <label class="form-label">Kode EPP / Auth Code</label>
          <input type="text" name="auth_code" value="<?php echo e(old('auth_code')); ?>"
                 placeholder="Kode dari registrar lama Anda" class="form-control font-monospace" required>
          <?php $__errorArgs = ['auth_code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          <p class="text-muted mt-1 mb-0" style="font-size:11px">
            Minta kode ini ke registrar tempat domain Anda terdaftar sekarang.
          </p>
        </div>

        <button type="submit" class="btn btn-theme w-100">
          <i class="fa-solid fa-right-left" style="font-size:12px"></i> Lanjutkan Transfer
        </button>
      </form>
    </div>

    
    <div class="card-public p-4 mb-3">
      <h2 class="fw-semibold text-dark mb-3" style="font-size:14px">Sebelum Transfer, Pastikan:</h2>
      <ul class="d-flex flex-column gap-2 mb-0 ps-0" style="list-style:none">
        <li class="d-flex gap-2">
          <i class="fa-solid fa-circle-check text-success flex-shrink-0" style="font-size:12px;margin-top:2px"></i>
          <span class="text-muted" style="font-size:14px">Domain sudah <b class="text-dark">berumur minimal 60 hari</b> sejak didaftarkan atau sejak transfer terakhir.</span>
        </li>
        <li class="d-flex gap-2">
          <i class="fa-solid fa-circle-check text-success flex-shrink-0" style="font-size:12px;margin-top:2px"></i>
          <span class="text-muted" style="font-size:14px"><b class="text-dark">Registrar Lock dimatikan</b> di registrar lama Anda.</span>
        </li>
        <li class="d-flex gap-2">
          <i class="fa-solid fa-circle-check text-success flex-shrink-0" style="font-size:12px;margin-top:2px"></i>
          <span class="text-muted" style="font-size:14px"><b class="text-dark">ID Protection/WHOIS Privacy dimatikan sementara</b>, supaya email konfirmasi bisa sampai ke Anda.</span>
        </li>
        <li class="d-flex gap-2">
          <i class="fa-solid fa-circle-check text-success flex-shrink-0" style="font-size:12px;margin-top:2px"></i>
          <span class="text-muted" style="font-size:14px">Email pemilik domain di WHOIS <b class="text-dark">masih aktif dan bisa Anda akses</b> — konfirmasi transfer dikirim ke sana.</span>
        </li>
      </ul>
      <p class="text-muted mt-3 mb-0" style="font-size:11px">
        Proses transfer biasanya memakan waktu 5–7 hari, tergantung kecepatan persetujuan dari registrar lama.
      </p>
    </div>

    <?php if($tlds->isNotEmpty()): ?>
      <div class="card-public p-4">
        <h2 class="fw-semibold text-dark mb-3" style="font-size:14px">Biaya Transfer</h2>
        <div class="row g-2">
          <?php $__currentLoopData = $tlds; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tld): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-sm-6">
              <div class="d-flex align-items-center justify-content-between py-2 px-3 rounded-3" style="font-size:14px;background:#f8fafc">
                <span class="font-monospace text-muted">.<?php echo e(ltrim($tld->extension, '.')); ?></span>
                <span class="fw-semibold text-dark">Rp <?php echo e(number_format($tld->transfer_price, 0, ',', '.')); ?></span>
              </div>
            </div>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <p class="text-muted mt-3 mb-0" style="font-size:11px">Biaya transfer sudah termasuk perpanjangan 1 tahun.</p>
      </div>
    <?php endif; ?>
  </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('public.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/themes/public-themes/modern/public/catalog/domain-transfer.blade.php ENDPATH**/ ?>