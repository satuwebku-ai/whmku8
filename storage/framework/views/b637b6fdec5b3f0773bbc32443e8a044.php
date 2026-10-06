<?php $__env->startSection('title', $domain->domain_name); ?>

<?php $__env->startSection('content'); ?>
  <?php
    $badgeMap = ['active' => 'badge-soft-success', 'pending' => 'badge-soft-warning', 'expired' => 'badge-soft-danger'];
  ?>

  <a href="<?php echo e(route('client.domains')); ?>" class="text-decoration-none text-muted" style="font-size:12px">&larr; Kembali ke Domain</a>

  <div class="d-flex align-items-center justify-content-between mt-2 mb-4 flex-wrap gap-3">
    <h1 class="h4 fw-bold text-dark mb-0"><?php echo e($domain->domain_name); ?></h1>
    <div class="d-flex align-items-center gap-2">
      <?php if($domain->status === 'active' && $domain->isWithinRenewalWindow() && ! $domain->renewal_invoice_id): ?>
        <form method="POST" action="<?php echo e(route('client.domains.renew-now', $domain)); ?>"
              data-confirm="Buat invoice perpanjangan sekarang untuk <?php echo e($domain->domain_name); ?>? Masa aktif akan bertambah dari tanggal kedaluwarsa saat ini (<?php echo e($domain->expiry_date->format('d M Y')); ?>) setelah invoice ini dibayar — bukan dari hari ini."
              data-confirm-title="Perpanjang Sekarang" data-confirm-style="info" data-confirm-label="Ya, Buat Invoice">
          <?php echo csrf_field(); ?>
          <button type="submit" class="btn btn-outline-secondary btn-sm">
            <i class="fa-solid fa-rotate" style="font-size:11px"></i> Perpanjang Sekarang
          </button>
        </form>
      <?php endif; ?>
      <?php if($domain->provision_status === 'registered' && $domain->supportsPrivacy()): ?>
        <a href="<?php echo e(route('client.domains.addons', $domain)); ?>" class="btn btn-outline-secondary btn-sm">
          <i class="fa-solid fa-puzzle-piece" style="font-size:11px"></i> Addons
        </a>
      <?php endif; ?>
      <span class="badge <?php echo e($badgeMap[$domain->status === 'expired' ? 'expired' : $domain->status] ?? 'badge-soft-secondary'); ?>"><?php echo e(ucfirst($domain->status)); ?></span>
    </div>
  </div>

  <?php if($domain->is_premium && $domain->provision_status !== 'registered' && $domain->provision_status !== 'needs_documents'): ?>
    <div class="card-public p-4 mb-4" style="border-color:#fde68a!important;background:#fffbeb">
      <p class="mb-0" style="font-size:14px;color:#92400e">
        <i class="fa-solid fa-crown"></i>
        <?php if($domain->status === 'pending' && $domain->provision_status === 'manual' && $domain->provision_message): ?>
          Domain premium ini sudah dibayar dan sedang didaftarkan manual oleh tim kami. Anda akan diberi tahu setelah aktif.
        <?php else: ?>
          Domain premium ini diproses manual oleh tim kami setelah pembayaran terverifikasi.
        <?php endif; ?>
      </p>
    </div>
  <?php endif; ?>

  <?php if($domain->provision_status === 'needs_documents'): ?>
    <div class="card-public p-4 mb-4" style="border-color:#fde68a!important;background:#fffbeb">
      <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap">
        <p class="mb-0" style="font-size:14px;color:#92400e">
          <i class="fa-solid fa-file-circle-exclamation"></i>
          Domain ini butuh dokumen tambahan sebelum bisa diaktifkan (persyaratan PANDI untuk TLD Indonesia).
        </p>
        <a href="<?php echo e(route('client.domains.documents', $domain)); ?>" class="btn btn-theme btn-sm flex-shrink-0">
          Unggah Dokumen
        </a>
      </div>
    </div>
  <?php endif; ?>

  
  <?php if($domain->status === 'active'
      && $domain->renewal_invoice_id
      && $domain->renewalInvoice
      && in_array($domain->renewalInvoice->status, ['unpaid', 'overdue'], true)): ?>
    <div class="card-public p-4 mb-4" style="border-color:#c7d2fe!important;background:rgba(79,70,229,.04)">
      <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap">
        <p class="text-dark mb-0" style="font-size:14px">
          <i class="fa-solid fa-file-invoice text-theme"></i>
          Invoice perpanjangan <b><?php echo e($domain->renewalInvoice->invoice_number); ?></b> sudah dibuat,
          jatuh tempo <?php echo e($domain->renewalInvoice->due_date->format('d M Y')); ?>.
        </p>
        <a href="<?php echo e(route('client.invoices.show', $domain->renewalInvoice)); ?>" class="btn btn-theme btn-sm flex-shrink-0">
          Bayar Sekarang
        </a>
      </div>
    </div>
  <?php elseif($domain->is_expiring_soon): ?>
    <div class="card-public p-4 mb-4" style="border-color:#fde68a!important;background:#fffbeb">
      <p class="mb-0" style="font-size:14px;color:#92400e">
        <i class="fa-solid fa-triangle-exclamation"></i>
        Domain ini akan kedaluwarsa <?php echo e($domain->expiry_date->format('d M Y')); ?>.
        <?php if($domain->auto_renew): ?>
          Invoice perpanjangan akan dibuat otomatis mendekati tanggal tersebut.
        <?php else: ?>
          Perpanjangan Otomatis sedang nonaktif untuk domain ini — aktifkan di bawah atau hubungi kami sebelum tanggal tersebut.
        <?php endif; ?>
      </p>
    </div>
  <?php endif; ?>

  <div class="row g-4">
    <div class="col-12 col-lg-8">
      <div class="card-public p-4">
        <h2 class="small fw-bold text-dark mb-3">Detail Domain</h2>
        <div class="row g-3">
          <div class="col-sm-6"><p class="text-muted mb-0" style="font-size:11px">Tanggal Registrasi</p><p class="fw-medium text-dark mb-0" style="font-size:14px"><?php echo e($domain->register_date?->format('d M Y') ?? '—'); ?></p></div>
          <div class="col-sm-6"><p class="text-muted mb-0" style="font-size:11px">Kedaluwarsa</p><p class="fw-medium text-dark mb-0" style="font-size:14px"><?php echo e($domain->expiry_date?->format('d M Y') ?? '—'); ?></p></div>

          <div class="col-sm-6">
            <p class="text-muted mb-0" style="font-size:11px">Perpanjangan Otomatis</p>
            <div class="d-flex align-items-center gap-2 mt-1">
              <span class="fw-medium text-dark" style="font-size:14px"><?php echo e($domain->auto_renew ? 'Aktif' : 'Nonaktif'); ?></span>
              <?php if($domain->provision_status === 'registered'): ?>
                <form method="POST" action="<?php echo e(route('client.domains.auto-renew', $domain)); ?>">
                  <?php echo csrf_field(); ?>
                  <div class="form-check form-switch mb-0">
                    <input class="form-check-input" type="checkbox" role="switch" <?php if($domain->auto_renew): echo 'checked'; endif; ?> data-auto-submit>
                  </div>
                </form>
              <?php endif; ?>
            </div>
          </div>

          <?php if($domain->supportsPrivacy()): ?>
          <div class="col-sm-6">
            <p class="text-muted mb-0" style="font-size:11px">ID Protection (WHOIS Privacy)</p>
            <?php $privacyActive = $domain->hasActivePrivacy(); ?>
            <div class="d-flex align-items-center gap-2 mt-1">
              <span class="badge <?php echo e($privacyActive ? 'badge-soft-success' : 'badge-soft-secondary'); ?>"><?php echo e($privacyActive ? 'Aktif' : 'Nonaktif'); ?></span>
              <?php if($domain->provision_status === 'registered'): ?>
                <a href="<?php echo e(route('client.domains.addons', $domain)); ?>" class="text-decoration-none text-theme" style="font-size:12px">Kelola di Addons &rarr;</a>
              <?php endif; ?>
            </div>
          </div>
          <?php else: ?>
          <div class="col-sm-6">
            <p class="text-muted mb-0" style="font-size:11px">ID Protection (WHOIS Privacy)</p>
            <p class="mb-0 mt-1 text-muted" style="font-size:12px">Tidak tersedia untuk domain .id (aturan PANDI)</p>
          </div>
          <?php endif; ?>

          <?php if(! is_null($lockStatus)): ?>
            <div class="col-sm-6">
              <p class="text-muted mb-0" style="font-size:11px">Registrar Lock</p>
              <div class="d-flex align-items-center gap-2 mt-1">
                <span class="fw-medium text-dark" style="font-size:14px"><?php echo e($lockStatus ? 'Terkunci' : 'Tidak Terkunci'); ?></span>
                <form method="POST" action="<?php echo e(route('client.domains.lock', $domain)); ?>">
                  <?php echo csrf_field(); ?>
                  <div class="form-check form-switch mb-0">
                    <input class="form-check-input" type="checkbox" role="switch" <?php if($lockStatus): echo 'checked'; endif; ?> data-auto-submit>
                  </div>
                </form>
              </div>
            </div>
          <?php endif; ?>

          <?php if(! is_null($theftStatus)): ?>
            <div class="col-sm-6">
              <p class="text-muted mb-0" style="font-size:11px">
                Theft Protection
                <i class="fa-solid fa-circle-question text-muted" title="Proteksi tambahan dari pencurian domain lewat perubahan data tanpa verifikasi ekstra — beda dari ID Protection"></i>
              </p>
              <div class="d-flex align-items-center gap-2 mt-1">
                <span class="fw-medium text-dark" style="font-size:14px"><?php echo e($theftStatus ? 'Aktif' : 'Nonaktif'); ?></span>
                <form method="POST" action="<?php echo e(route('client.domains.theft-protection', $domain)); ?>">
                  <?php echo csrf_field(); ?>
                  <div class="form-check form-switch mb-0">
                    <input class="form-check-input" type="checkbox" role="switch" <?php if($theftStatus): echo 'checked'; endif; ?> data-auto-submit>
                  </div>
                </form>
              </div>
            </div>
          <?php endif; ?>
        </div>

        <?php if($supportsForwarding): ?>
          <div class="mt-4 pt-4 border-top">
            <h3 class="fw-semibold text-dark mb-1" style="font-size:14px">Domain Forwarding</h3>
            <p class="text-muted mb-2" style="font-size:11px">Arahkan domain ini ke alamat website lain (redirect), tanpa perlu hosting terpisah.</p>
            <form method="POST" action="<?php echo e(route('client.domains.forwarding', $domain)); ?>" class="d-flex gap-2">
              <?php echo csrf_field(); ?>
              <input type="url" name="forward_to" value="<?php echo e($forwardTo); ?>" placeholder="https://contoh.com (kosongkan untuk matikan)" class="form-control form-control-sm">
              <button type="submit" class="btn btn-outline-secondary btn-sm flex-shrink-0">Simpan</button>
            </form>
          </div>
        <?php endif; ?>

        <?php if($supportsEmailForwarding): ?>
          <div class="mt-4 pt-4 border-top">
            <a href="<?php echo e(route('client.domains.email-forwarding', $domain)); ?>" class="btn btn-outline-secondary">
              <i class="fa-solid fa-envelope" style="font-size:11px"></i> Kelola Email Forwarding
            </a>
          </div>
        <?php endif; ?>

        
        <?php if($domain->provision_status === 'registered'): ?>
          <div class="mt-4 pt-4 border-top d-flex flex-wrap gap-3">
            <a href="<?php echo e(route('client.domains.dns', $domain)); ?>" class="btn btn-outline-secondary">
              <i class="fa-solid fa-server" style="font-size:11px"></i> Kelola DNS
            </a>
            <form method="POST" action="<?php echo e(route('client.domains.auth-code', $domain)); ?>">
              <?php echo csrf_field(); ?>
              <button type="submit" class="btn btn-outline-secondary">
                <i class="fa-solid fa-key" style="font-size:11px"></i> Ajukan Permintaan Kode Transfer (EPP)
              </button>
            </form>
          </div>
          <p class="text-muted mt-2 mb-0" style="font-size:11px">
            Kode transfer tidak diberikan langsung — permintaan akan ditinjau tim kami lewat tiket, lalu dikirim ke email Anda setelah disetujui.
          </p>
        <?php else: ?>
          <div class="mt-4 pt-4 border-top">
            <p class="text-muted mb-0" style="font-size:12px">
              <i class="fa-solid fa-circle-info"></i>
              Pengelolaan DNS dan permintaan kode transfer tersedia setelah domain ini berhasil didaftarkan.
              <?php if($domain->status === 'pending'): ?>
                Status saat ini: menunggu pembayaran/pemrosesan.
              <?php endif; ?>
            </p>
          </div>
        <?php endif; ?>

        
        <div class="mt-4 pt-4 border-top">
          <h3 class="fw-semibold text-dark mb-1" style="font-size:14px">Nameserver</h3>
          <p class="text-muted mb-3" style="font-size:12px">
            Arahkan domain ke server hosting mana pun. Isi minimal dua nameserver.
          </p>

          <?php if($domain->status !== 'active'): ?>
            <p class="text-muted mb-0" style="font-size:14px">
              Nameserver hanya bisa diubah untuk domain berstatus aktif.
            </p>
          <?php elseif(! $domain->registrar_id): ?>
            <p class="text-muted mb-0" style="font-size:14px">
              Domain ini belum terhubung ke registrar. Silakan hubungi support untuk mengubah nameserver.
            </p>
          <?php else: ?>
            <?php $ns = $domain->nameservers ?? []; ?>

            <form method="POST" action="<?php echo e(route('client.domains.nameservers', $domain)); ?>" class="d-flex flex-column gap-2">
              <?php echo csrf_field(); ?>

              <?php for($i = 0; $i < 4; $i++): ?>
                <input type="text" name="nameservers[]"
                       value="<?php echo e($errors->has('nameservers') || $errors->has('nameservers.*') ? old('nameservers.' . $i, $ns[$i] ?? '') : ($ns[$i] ?? '')); ?>"
                       placeholder="ns<?php echo e($i + 1); ?>.contoh.com<?php echo e($i >= 2 ? ' (opsional)' : ''); ?>"
                       class="form-control form-control-sm" style="font-family:monospace" <?php echo e($i < 2 ? 'required' : ''); ?>>
              <?php endfor; ?>

              <?php $__errorArgs = ['nameservers'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
              <?php $__errorArgs = ['nameservers.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

              <button type="submit" class="btn btn-theme mt-1" style="width:fit-content">
                <i class="fa-solid fa-check" style="font-size:11px"></i> Simpan Nameserver
              </button>

              <p class="text-muted mb-0" style="font-size:11px">
                Perubahan DNS bisa memakan waktu hingga 24 jam untuk menyebar ke seluruh dunia.
              </p>
            </form>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <div class="col-12 col-lg-4">
      <div class="card-public p-4">
        <h2 class="small fw-bold text-dark mb-2">Bantuan</h2>
        <p class="text-muted mb-3" style="font-size:14px">Butuh perpanjang domain atau ubah data WHOIS?</p>
        <a href="<?php echo e(route('client.tickets.create')); ?>" class="btn btn-theme w-100">
          <i class="fa-solid fa-headset" style="font-size:11px"></i> Hubungi Support
        </a>
      </div>
    </div>
  </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('client.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/themes/client-themes/default/client/domains/show.blade.php ENDPATH**/ ?>