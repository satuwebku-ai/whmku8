<?php $__env->startSection('title', 'Addons — ' . $domain->domain_name); ?>

<?php $__env->startSection('content'); ?>
  <a href="<?php echo e(route('client.domains.show', $domain)); ?>" class="text-decoration-none text-muted" style="font-size:12px">
    &larr; Kembali ke <?php echo e($domain->domain_name); ?>

  </a>

  <div class="mt-2 mb-4">
    <h1 class="h4 fw-bold text-dark mb-1">Addons — <?php echo e($domain->domain_name); ?></h1>
    <p class="text-muted mb-0">Fitur tambahan yang bisa dipasang di domain ini.</p>
  </div>

  <div class="card-public p-4">
    <div class="d-flex align-items-start justify-content-between gap-3 flex-wrap">
      <div class="d-flex align-items-start gap-3">
        <span class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width:36px;height:36px;background:rgba(79,70,229,.1);color:#4f46e5">
          <i class="fa-solid fa-user-shield" style="font-size:14px"></i>
        </span>
        <div>
          <h2 class="fw-semibold text-dark mb-1" style="font-size:14px">ID Protection (WHOIS Privacy)</h2>
          <p class="text-muted mb-0" style="font-size:12px;max-width:28rem">Sembunyikan data pribadi (nama, alamat, email, telepon) dari pencarian WHOIS publik — mencegah spam dan penyalahgunaan data.</p>

          <?php
            $privacyActive = $domain->hasActivePrivacy();
            $privacyDaysLeft = $domain->privacyDaysLeft();
            $privacyPrice = $domain->privacyPrice();
          ?>

          <div class="d-flex align-items-center gap-2 flex-wrap mt-2">
            <span class="badge <?php echo e($privacyActive ? 'badge-soft-success' : 'badge-soft-secondary'); ?>"><?php echo e($privacyActive ? 'Aktif' : 'Nonaktif'); ?></span>

            <?php if($privacyActive): ?>
              <span style="font-size:12px;<?php echo e($privacyDaysLeft !== null && $privacyDaysLeft <= 30 ? 'color:#b45309' : 'color:#94a3b8'); ?>">
                s.d. <?php echo e($domain->privacy_expires_at->format('d M Y')); ?>

                <?php if($privacyDaysLeft !== null && $privacyDaysLeft <= 30): ?>
                  (<?php echo e($privacyDaysLeft); ?> hari lagi)
                <?php endif; ?>
              </span>
            <?php elseif($domain->privacy_expires_at && $domain->privacy_expires_at->isPast()): ?>
              <span class="text-danger" style="font-size:12px">Kedaluwarsa <?php echo e($domain->privacy_expires_at->format('d M Y')); ?></span>
            <?php endif; ?>
          </div>

          <?php if(! is_null($privacyAtRegistrar ?? null) && $privacyAtRegistrar !== $privacyActive): ?>
            <p class="text-danger mt-2 mb-0" style="font-size:12px">
              <i class="fa-solid fa-triangle-exclamation"></i>
              Status di registrar: <b><?php echo e($privacyAtRegistrar ? 'Aktif' : 'Nonaktif'); ?></b> — tidak cocok dengan catatan di sini. Hubungi support untuk disesuaikan.
            </p>
          <?php endif; ?>
        </div>
      </div>

      <div class="flex-shrink-0">
        <?php if($domain->privacy_invoice_id): ?>
          <a href="<?php echo e(route('client.invoices.show', $domain->privacy_invoice_id)); ?>" class="btn btn-theme btn-sm">
            Bayar Sekarang
          </a>
        <?php else: ?>
          <form method="POST" action="<?php echo e(route('client.domains.privacy', $domain)); ?>">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn btn-sm <?php echo e($privacyActive ? 'btn-outline-secondary' : 'btn-theme'); ?>">
              <?php if($privacyActive): ?>
                Matikan
              <?php else: ?>
                <?php echo e($domain->privacy_expires_at ? 'Perpanjang' : 'Aktifkan'); ?><?php echo e($privacyPrice > 0 ? ' — Rp ' . number_format($privacyPrice, 0, ',', '.') . '/thn' : ''); ?>

              <?php endif; ?>
            </button>
          </form>
        <?php endif; ?>
      </div>
    </div>
  </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('client.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/themes/client-themes/namahost/client/domains/addons.blade.php ENDPATH**/ ?>