<?php $__env->startSection('title', 'Email Forwarding — ' . $domain->domain_name); ?>

<?php $__env->startSection('content'); ?>
  <a href="<?php echo e(route('client.domains.show', $domain)); ?>" class="text-decoration-none text-muted" style="font-size:12px">
    &larr; Kembali ke <?php echo e($domain->domain_name); ?>

  </a>

  <div class="mt-2 mb-4">
    <h1 class="h4 fw-bold text-dark mb-1">Email Forwarding — <?php echo e($domain->domain_name); ?></h1>
    <p class="text-muted mb-0">
      Teruskan email yang masuk ke alamat {{ $domain->domain_name }} ke email lain — tanpa perlu hosting email sendiri.
    </p>
  </div>

  <?php if($warning): ?>
    <div class="card-public p-4 mb-4" style="border-color:#fde68a!important;background:#fffbeb">
      <p class="mb-0" style="font-size:14px;color:#92400e"><i class="fa-solid fa-triangle-exclamation"></i> <?php echo e($warning); ?></p>
    </div>
  <?php endif; ?>

  <div class="row g-4">
    <div class="col-12 col-lg-8">
      <div class="card-public overflow-hidden">
        <div class="px-4 py-3 border-bottom">
          <h2 class="small fw-bold text-dark mb-0">Forwarding Aktif</h2>
        </div>
        <div>
          <?php $__empty_1 = true; $__currentLoopData = $forwards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fwd): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="d-flex align-items-center justify-content-between px-4 py-3 border-bottom">
              <div class="min-w-0" style="font-size:14px">
                <p class="fw-medium text-dark text-truncate mb-0"><?php echo e($fwd['email']); ?></p>
                <p class="text-muted mb-0" style="font-size:11px">
                  <i class="fa-solid fa-arrow-right" style="font-size:9px"></i> <?php echo e($fwd['forward_to']); ?>

                </p>
              </div>
              <form method="POST" action="<?php echo e(route('client.domains.email-forwarding.delete', $domain)); ?>"
                    data-confirm="Hapus forwarding untuk <?php echo e($fwd['email']); ?>?" data-confirm-title="Hapus Email Forwarding" data-confirm-style="danger" data-confirm-label="Ya, Hapus">
                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                <input type="hidden" name="email" value="<?php echo e($fwd['email']); ?>">
                <button type="submit" class="btn btn-outline-danger btn-sm d-inline-flex align-items-center justify-content-center flex-shrink-0" style="width:28px;height:28px;padding:0">
                  <i class="fa-regular fa-trash-can" style="font-size:11px"></i>
                </button>
              </form>
            </div>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <p class="text-center text-muted py-5 mb-0" style="font-size:14px">Belum ada email forwarding.</p>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <div class="col-12 col-lg-4">
      <div class="card-public p-4">
        <h2 class="small fw-bold text-dark mb-3">Tambah Forwarding</h2>
        <form method="POST" action="<?php echo e(route('client.domains.email-forwarding.add', $domain)); ?>" class="d-flex flex-column gap-3">
          <?php echo csrf_field(); ?>
          <div>
            <label class="form-label">Alamat di Domain Ini</label>
            <div class="d-flex align-items-center gap-2">
              <input type="text" name="email" placeholder="info" class="form-control">
              <span class="text-muted flex-shrink-0" style="font-size:13px">{{ $domain->domain_name }}</span>
            </div>
            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>
          <div>
            <label class="form-label">Teruskan ke Email</label>
            <input type="email" name="forward_to" placeholder="tujuan@gmail.com" class="form-control">
            <?php $__errorArgs = ['forward_to'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>
          <button type="submit" class="btn btn-theme w-100">
            <i class="fa-solid fa-plus" style="font-size:11px"></i> Tambah
          </button>
        </form>
      </div>
    </div>
  </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('client.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/themes/client-themes/namahost/client/domains/email-forwarding.blade.php ENDPATH**/ ?>