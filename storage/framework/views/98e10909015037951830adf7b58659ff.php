
<div class="rounded-3 border p-3 mb-3" style="background:rgba(79,70,229,.03)">
  <?php if($pending): ?>
    <p class="mb-2" style="font-size:12px">
      <i class="fa-solid fa-circle-check text-success"></i>
      Kode dikirim lewat <b><?php echo e($pending['channel'] === 'whatsapp' ? 'WhatsApp' : 'email'); ?></b>,
      berlaku sampai <?php echo e(\Carbon\Carbon::createFromTimestamp($pending['expires_at'])->format('H:i')); ?>.
    </p>
  <?php endif; ?>

  <form method="POST" action="<?php echo e(route('client.profile.otp')); ?>" class="d-flex flex-column gap-2">
    <?php echo csrf_field(); ?>
    <input type="hidden" name="purpose" value="<?php echo e($purpose); ?>">
    <p class="fw-medium text-dark mb-0" style="font-size:12px"><?php echo e($pending ? 'Kirim ulang kode ke:' : 'Kirim kode verifikasi ke:'); ?></p>
    <?php $__currentLoopData = $channels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $channelKey => $channelLabel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <label class="d-flex align-items-center gap-2 mb-0" style="font-size:13px;cursor:pointer">
        <input type="radio" name="channel" value="<?php echo e($channelKey); ?>" class="form-check-input mt-0" <?php if($loop->first): echo 'checked'; endif; ?> required>
        <?php echo e($channelLabel); ?>

      </label>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php if(count($channels) === 1): ?>
      <p class="text-muted mb-0" style="font-size:11px">Ingin menerima lewat WhatsApp? Isi nomor WhatsApp di Data Akun (butuh gateway WhatsApp aktif).</p>
    <?php endif; ?>
    <button type="submit" class="btn btn-outline-secondary btn-sm" style="width:fit-content">
      <i class="fa-regular fa-paper-plane" style="font-size:11px"></i> <?php echo e($pending ? 'Kirim Ulang' : 'Kirim Kode'); ?>

    </button>
  </form>
</div>
<?php /**PATH /home/runner/workspace/hosting-billing/resources/views/themes/client-themes/default/client/profile/_otp.blade.php ENDPATH**/ ?>