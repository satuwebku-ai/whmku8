<?php $seoTitle = $license->name; ?>

<?php $__env->startSection('content'); ?>

  <div class="row g-4 py-4 py-lg-5">
    <div class="col-12 col-lg-7">
      <a href="<?php echo e(route('license.index')); ?>" class="small text-decoration-none text-theme"><i class="fa-solid fa-arrow-left"></i> Semua Lisensi & SSL</a>

      <div class="d-flex flex-wrap align-items-center gap-2 mt-4 mb-2">
        <span class="badge badge-soft-success"><?php echo e($license->category_label); ?></span>
        <?php if($license->brand): ?><span class="badge badge-soft-secondary"><?php echo e($license->brand); ?></span><?php endif; ?>
      </div>
      <h1 class="display-6 fw-bold text-dark"><?php echo e($license->name); ?></h1>
      <?php if($license->summary): ?><p class="lead text-muted"><?php echo e($license->summary); ?></p><?php endif; ?>

      <div class="prose-content text-muted mt-3">
        <?php echo nl2br(e($license->long_description ?: $license->description ?: 'Lisensi digital untuk kebutuhan operasional Anda.')); ?>

      </div>

      <?php if(! empty($license->features)): ?>
        <div class="card-public p-4 mt-4">
          <h2 class="h6 fw-bold text-dark mb-3"><?php echo e($isSsl ? 'Keunggulan sertifikat ini' : 'Fitur utama'); ?></h2>
          <ul class="list-unstyled mb-0">
            <?php $__currentLoopData = $license->features; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <li class="d-flex gap-2 mb-2 text-muted"><i class="fa-solid fa-circle-check text-theme mt-1"></i><span><?php echo e($feature); ?></span></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </ul>
        </div>
      <?php endif; ?>

      <?php if(! empty($license->specs)): ?>
        <div class="card-public mt-4 overflow-hidden">
          <h2 class="h6 fw-bold text-dark p-4 pb-2 mb-0">Spesifikasi</h2>
          <div class="table-responsive">
            <table class="table table-borderless align-middle mb-0 small">
              <tbody>
                <?php $__currentLoopData = $license->specs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <tr class="border-top">
                    <th class="fw-semibold text-dark ps-4" style="width:40%"><?php echo e($label); ?></th>
                    <td class="text-muted pe-4"><?php echo e($value); ?></td>
                  </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </tbody>
            </table>
          </div>
        </div>
      <?php endif; ?>

      <?php if(! empty($license->faqs)): ?>
        <div class="mt-4">
          <h2 class="h6 fw-bold text-dark mb-3">Pertanyaan yang sering diajukan</h2>
          <div class="accordion" id="licenseFaq">
            <?php $__currentLoopData = $license->faqs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $faq): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <div class="accordion-item">
                <h3 class="accordion-header">
                  <button class="accordion-button <?php echo e($i > 0 ? 'collapsed' : ''); ?> small fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#faq<?php echo e($i); ?>" aria-expanded="<?php echo e($i === 0 ? 'true' : 'false'); ?>"><?php echo e($faq['q']); ?></button>
                </h3>
                <div id="faq<?php echo e($i); ?>" class="accordion-collapse collapse <?php echo e($i === 0 ? 'show' : ''); ?>" data-bs-parent="#licenseFaq">
                  <div class="accordion-body small text-muted"><?php echo e($faq['a']); ?></div>
                </div>
              </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </div>
        </div>
      <?php endif; ?>
    </div>

    <div class="col-12 col-lg-5">
      <div class="card-public p-4 sticky-lg-top" style="top:6rem">
        <h2 class="h5 fw-bold text-dark">Pesan sekarang</h2>

        <?php if(count($cycles) === 1): ?>
          <?php ($only = array_key_first($cycles)); ?>
          <div class="d-flex align-items-baseline gap-1 my-3">
            <span class="display-6 fw-bold text-dark">Rp <?php echo e(number_format($cycles[$only], 0, ',', '.')); ?></span>
            <span class="text-muted"><?php echo e($suffix[$only] ?? ''); ?></span>
          </div>
          <?php if(! empty($license->specs['Registrasi & perpanjangan'])): ?>
            <p class="small text-muted">Registrasi dan perpanjangan: <?php echo e(strtolower($license->specs['Registrasi & perpanjangan'])); ?>.</p>
          <?php endif; ?>
        <?php else: ?>
          <p class="text-muted small">Pilih siklus pembayaran untuk melanjutkan.</p>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('cart.add-addon')); ?>">
          <?php echo csrf_field(); ?>
          <input type="hidden" name="addon_id" value="<?php echo e($license->id); ?>">
          <?php if($license->requiresIp()): ?>
            <div class="mb-3">
              <label class="form-label small fw-semibold">IP Server <span class="text-danger fst-italic fw-normal">*required</span></label>
              <input type="text" name="license_ip" value="<?php echo e(old('license_ip')); ?>" inputmode="decimal" placeholder="Contoh: 103.10.20.30" class="form-control <?php $__errorArgs = ['license_ip'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
              <?php $__errorArgs = ['license_ip'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php else: ?><div class="form-text">Lisensi terikat ke IP publik server Anda, bukan IP lokal (192.168.x / 10.x).</div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
          <?php endif; ?>
          <?php if(count($cycles) === 1): ?>
            <input type="hidden" name="billing_cycle" value="<?php echo e($only); ?>">
          <?php else: ?>
            <label class="form-label small fw-semibold">Siklus pembayaran</label>
            <select name="billing_cycle" class="form-select mb-3" required>
              <?php $__currentLoopData = $cycles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cycle => $price): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($cycle); ?>"><?php echo e($labels[$cycle] ?? $cycle); ?> - Rp <?php echo e(number_format($price, 0, ',', '.')); ?></option>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
          <?php endif; ?>
          <button class="btn btn-theme w-100"><i class="fa-solid fa-cart-plus me-1"></i> Tambahkan ke Keranjang</button>
        </form>

        <ul class="list-unstyled small text-muted mt-3 mb-0">
          <li class="mb-1"><i class="fa-solid fa-file-invoice me-2 text-theme"></i>Invoice dan status order tersimpan di panel client</li>
          <li><i class="fa-solid fa-headset me-2 text-theme"></i>Dukungan lewat tiket dan live chat</li>
        </ul>
      </div>
    </div>
  </div>

  <?php if($related->isNotEmpty()): ?>
    <section class="pb-5">
      <h2 class="h5 fw-bold text-dark mb-3"><?php echo e($isSsl ? 'Sertifikat SSL lainnya' : 'Lisensi lainnya'); ?></h2>
      <div class="row g-3">
        <?php $__currentLoopData = $related; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <?php ($cheap = $item->cheapestCycle()); ?>
          <div class="col-12 col-md-4">
            <a href="<?php echo e(route('license.show', $item->slug)); ?>" class="card-public d-block p-3 h-100 text-decoration-none">
              <div class="small text-muted"><?php echo e($item->brand); ?></div>
              <div class="fw-bold text-dark"><?php echo e($item->name); ?></div>
              <div class="text-theme fw-semibold mt-1">Rp <?php echo e(number_format($cheap['price'], 0, ',', '.')); ?> <span class="text-muted fw-normal small"><?php echo e($suffix[$cheap['cycle']] ?? ''); ?></span></div>
            </a>
          </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </div>
    </section>
  <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('public.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/themes/public-themes/modern/public/licenses/show.blade.php ENDPATH**/ ?>