<?php $__env->startSection('title', $service->domain); ?>

<?php $__env->startSection('content'); ?>
  <?php
    $badgeMap = [
      'active' => 'badge-soft-success', 'pending' => 'badge-soft-warning',
      'suspended' => 'badge-soft-danger', 'terminated' => 'badge-soft-secondary',
    ];
  ?>

  <a href="<?php echo e(route('client.services')); ?>" class="text-decoration-none text-muted" style="font-size:12px">&larr; Kembali ke Layanan</a>

  <div class="d-flex align-items-center justify-content-between mt-2 mb-4 flex-wrap gap-3">
    <h1 class="h4 fw-bold text-dark mb-0"><?php echo e($service->domain); ?></h1>
    <div class="d-flex align-items-center gap-2 flex-wrap">
      <?php if($service->status === 'active' && ! $service->renewal_invoice_id): ?>
        <form method="POST" action="<?php echo e(route('client.services.renew-now', $service)); ?>"
              data-confirm="Buat invoice perpanjangan sekarang untuk <?php echo e($service->domain); ?>? Masa aktif akan bertambah dari tanggal jatuh tempo saat ini (<?php echo e($service->next_due_date->format('d M Y')); ?>) setelah invoice ini dibayar — bukan dari hari ini."
              data-confirm-title="Perpanjang Sekarang" data-confirm-style="info" data-confirm-label="Ya, Buat Invoice">
          <?php echo csrf_field(); ?>
          <button type="submit" class="btn btn-outline-secondary btn-sm">
            <i class="fa-solid fa-rotate" style="font-size:11px"></i> Perpanjang Sekarang
          </button>
        </form>
      <?php endif; ?>
      <?php if($service->status === 'active' && ! $service->pending_upgrade_invoice_id): ?>
        <a href="<?php echo e(route('client.services.upgrade', $service)); ?>" class="btn btn-outline-secondary btn-sm">
          <i class="fa-solid fa-arrow-up" style="font-size:11px"></i> Upgrade Paket
        </a>
      <?php endif; ?>
      <?php if($service->status === 'active'): ?>
        <a href="<?php echo e(route('client.services.addons', $service)); ?>" class="btn btn-outline-secondary btn-sm">
          <i class="fa-solid fa-puzzle-piece" style="font-size:11px"></i> Addons
        </a>
      <?php endif; ?>
      <span class="badge <?php echo e($badgeMap[$service->status] ?? 'badge-soft-secondary'); ?>"><?php echo e(ucfirst($service->status)); ?></span>
      <?php if(! is_null($sslStatus)): ?>
        <?php if($sslStatus['installed']): ?>
          <span class="badge badge-soft-success" title="Website ini sudah HTTPS">
            <i class="fa-solid fa-lock" style="font-size:10px"></i> SSL Aktif
          </span>
        <?php else: ?>
          <span class="badge badge-soft-secondary" title="Belum ada SSL — hubungi support kalau butuh HTTPS">
            <i class="fa-solid fa-lock-open" style="font-size:10px"></i> Belum Ada SSL
          </span>
        <?php endif; ?>
      <?php endif; ?>
    </div>
  </div>

  <?php if($service->pending_upgrade_invoice_id && $service->pendingUpgradeInvoice): ?>
    <div class="card-public p-4 mb-4" style="border-color:#c7d2fe!important;background:rgba(79,70,229,.04)">
      <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap">
        <p class="text-dark mb-0" style="font-size:14px">
          <i class="fa-solid fa-arrow-up text-theme"></i>
          Permintaan upgrade ke <b><?php echo e($service->pendingUpgradeProduct?->name); ?></b> sedang menunggu pembayaran
          invoice <b><?php echo e($service->pendingUpgradeInvoice->invoice_number); ?></b>.
        </p>
        <div class="d-flex align-items-center gap-2 flex-shrink-0">
          <form method="POST" action="<?php echo e(route('client.services.upgrade.cancel', $service)); ?>">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn btn-outline-secondary btn-sm">Batalkan</button>
          </form>
          <a href="<?php echo e(route('client.invoices.show', $service->pendingUpgradeInvoice)); ?>" class="btn btn-theme btn-sm">
            Bayar Sekarang
          </a>
        </div>
      </div>
    </div>
  <?php endif; ?>

  <?php if(in_array($service->status, ['active', 'suspended'], true) && $service->renewal_invoice_id && $service->renewalInvoice): ?>
    <div class="card-public p-4 mb-4" style="<?php echo e($service->status === 'suspended' ? 'border-color:#fecaca!important;background:#fef2f2' : 'border-color:#c7d2fe!important;background:rgba(79,70,229,.04)'); ?>">
      <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap">
        <p class="mb-0" style="font-size:14px;<?php echo e($service->status === 'suspended' ? 'color:#b91c1c' : 'color:#1e293b'); ?>">
          <i class="fa-solid <?php echo e($service->status === 'suspended' ? 'fa-circle-exclamation' : 'fa-file-invoice'); ?>"></i>
          <?php if($service->status === 'suspended'): ?>
            Layanan ini disuspend karena invoice <b><?php echo e($service->renewalInvoice->invoice_number); ?></b> belum dibayar.
            Aktif kembali otomatis begitu dibayar.
          <?php else: ?>
            Invoice perpanjangan <b><?php echo e($service->renewalInvoice->invoice_number); ?></b> sudah dibuat,
            jatuh tempo <?php echo e($service->renewalInvoice->due_date->format('d M Y')); ?>.
          <?php endif; ?>
        </p>
        <a href="<?php echo e(route('client.invoices.show', $service->renewalInvoice)); ?>"
           class="btn btn-sm flex-shrink-0 <?php echo e($service->status === 'suspended' ? 'btn-danger' : 'btn-theme'); ?>">
          Bayar Sekarang
        </a>
      </div>
    </div>
  <?php elseif($service->status === 'suspended'): ?>
    <div class="card-public p-4 mb-4" style="border-color:#fecaca!important;background:#fef2f2">
      <p class="mb-0" style="font-size:14px;color:#b91c1c">
        <i class="fa-solid fa-circle-exclamation"></i>
        Layanan ini sedang disuspend. Silakan cek
        <a href="<?php echo e(route('client.invoices')); ?>" class="text-decoration-underline fw-medium" style="color:inherit">halaman invoice</a>
        atau hubungi support.
      </p>
    </div>
  <?php endif; ?>

  <div class="row g-4">
    <div class="col-12 col-lg-8">
      <div class="card-public p-4">
        <h2 class="small fw-bold text-dark mb-3">Detail Layanan</h2>
        <div class="row g-3">
          <div class="col-sm-6"><p class="text-muted mb-0" style="font-size:11px">Paket</p><p class="fw-medium text-dark mb-0" style="font-size:14px"><?php echo e($service->package); ?></p></div>
          <div class="col-sm-6"><p class="text-muted mb-0" style="font-size:11px">Domain</p><p class="fw-medium text-dark mb-0" style="font-size:14px"><?php echo e($service->domain); ?></p></div>
          <div class="col-sm-6"><p class="text-muted mb-0" style="font-size:11px">Username Panel</p><p class="fw-medium text-dark mb-0" style="font-size:14px"><?php echo e($service->username ?? '—'); ?></p></div>
          <div class="col-sm-6"><p class="text-muted mb-0" style="font-size:11px">Panel</p><p class="fw-medium text-dark mb-0 text-capitalize" style="font-size:14px"><?php echo e($service->panel); ?></p></div>
          <div class="col-sm-6"><p class="text-muted mb-0" style="font-size:11px">Harga</p><p class="fw-medium text-dark mb-0" style="font-size:14px">Rp <?php echo e(number_format($service->price, 0, ',', '.')); ?> / <?php echo e(str_replace('_', ' ', $service->billing_cycle)); ?></p></div>
          <div class="col-sm-6"><p class="text-muted mb-0" style="font-size:11px">Jatuh Tempo Berikutnya</p><p class="fw-medium text-dark mb-0" style="font-size:14px"><?php echo e($service->next_due_date?->format('d M Y') ?? '—'); ?></p></div>
        </div>

        <?php if($usage): ?>
          <?php
            $usedNum = (float) preg_replace('/[^0-9.]/', '', $usage['disk_used'] ?? '0');
            $limitRaw = $usage['disk_limit'] ?? 'unlimited';
            $isUnlimited = strtolower((string) $limitRaw) === 'unlimited';
            $limitNum = $isUnlimited ? null : (float) preg_replace('/[^0-9.]/', '', $limitRaw);
            $percent = ($limitNum && $limitNum > 0) ? min(100, round($usedNum / $limitNum * 100)) : 0;
          ?>
          <div class="mt-4 pt-4 border-top">
            <div class="d-flex align-items-center justify-content-between mb-2">
              <p class="fw-bold text-muted mb-0" style="font-size:11px;text-transform:uppercase;letter-spacing:.03em">Pemakaian Disk</p>
              <p class="text-muted mb-0" style="font-size:12px">
                <?php echo e($usage['disk_used'] ?? '—'); ?> / <?php echo e($isUnlimited ? 'Unlimited' : $usage['disk_limit']); ?>

              </p>
            </div>
            <?php if (! ($isUnlimited)): ?>
              <div class="rounded-pill overflow-hidden" style="height:8px;background:#f1f5f9">
                <div class="h-100 rounded-pill" style="width:<?php echo e($percent); ?>%;background:<?php echo e($percent >= 90 ? '#f43f5e' : ($percent >= 70 ? '#f59e0b' : 'var(--lumora-theme)')); ?>"></div>
              </div>
            <?php endif; ?>
          </div>
        <?php endif; ?>

        
        <?php if($service->client_details): ?>
          <div class="mt-4 pt-4 border-top">
            <p class="fw-bold text-muted mb-2" style="font-size:11px;text-transform:uppercase;letter-spacing:.03em">
              <i class="fa-solid fa-key"></i> Info Akses Layanan
            </p>
            <div class="rounded-3 p-3" style="background:#1e293b;color:#f1f5f9;font-family:monospace;font-size:12px;white-space:pre-line;word-break:break-word"><?php echo e($service->client_details); ?></div>
            <p class="text-muted mt-2 mb-0" style="font-size:11px">
              Jaga kerahasiaan info ini. Hubungi support kalau ada yang perlu diubah atau di-reset.
            </p>
          </div>
        <?php endif; ?>

        
        <?php if($service->serverModel): ?>
          <div class="mt-4 pt-4 border-top">
            <p class="fw-bold text-muted mb-2" style="font-size:11px;text-transform:uppercase;letter-spacing:.03em">Info Koneksi (untuk email &amp; FTP)</p>
            <div class="row g-3">
              <div class="col-sm-6">
                <p class="text-muted mb-0" style="font-size:11px">Mail/FTP Server</p>
                <p class="fw-medium text-dark mb-0" style="font-family:monospace;font-size:12px"><?php echo e($service->serverModel->hostname); ?></p>
              </div>
              <?php if($usage && $usage['ip']): ?>
                <div class="col-sm-6">
                  <p class="text-muted mb-0" style="font-size:11px">Alamat IP</p>
                  <p class="fw-medium text-dark mb-0" style="font-family:monospace;font-size:12px"><?php echo e($usage['ip']); ?></p>
                </div>
              <?php endif; ?>
            </div>
            <p class="text-muted mt-2 mb-0" style="font-size:11px">
              Gunakan username panel &amp; password akun ini saat mengatur aplikasi email atau FTP.
            </p>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <div class="col-12 col-lg-4 d-flex flex-column gap-4">
      <?php if($service->status === 'active' && $service->username && $service->server_id): ?>
        <div class="card-public p-4">
          <h2 class="small fw-bold text-dark mb-1">Kelola Hosting</h2>
          <p class="text-muted mb-3" style="font-size:14px">
            Masuk ke control panel tanpa perlu memasukkan password.
          </p>
          <a href="<?php echo e(route('client.services.login-panel', $service)); ?>" target="_blank" rel="noopener"
             class="btn btn-theme w-100">
            <i class="fa-solid fa-arrow-up-right-from-square" style="font-size:11px"></i> Buka cPanel
          </a>
          <p class="text-muted mt-2 mb-0" style="font-size:11px">
            Tautan berlaku sekali pakai dan kedaluwarsa beberapa menit setelah dibuka.
          </p>

          <?php if($service->serverModel?->panel === 'cpanel'): ?>
            <?php
              $shortcuts = [
                ['label' => 'Email Accounts',   'icon' => 'fa-envelope',      'path' => 'frontend/jupiter/email/email_accounts.html'],
                ['label' => 'Forwarders',       'icon' => 'fa-share',         'path' => 'frontend/jupiter/email/email_forwarders.html'],
                ['label' => 'Autoresponders',   'icon' => 'fa-reply',         'path' => 'frontend/jupiter/email/autoresponders.html'],
                ['label' => 'File Manager',     'icon' => 'fa-folder-open',   'path' => 'frontend/jupiter/filemanager/index.html'],
                ['label' => 'Backup',           'icon' => 'fa-database',      'path' => 'frontend/jupiter/backup/index.html'],
                ['label' => 'Domains',          'icon' => 'fa-globe',         'path' => 'frontend/jupiter/domains/index.html'],
                ['label' => 'MySQL Databases',  'icon' => 'fa-server',        'path' => 'frontend/jupiter/sql/index.html'],
                ['label' => 'phpMyAdmin',       'icon' => 'fa-table-cells',   'path' => '3rdparty/phpMyAdmin/index.php'],
                ['label' => 'Awstats',          'icon' => 'fa-chart-line',    'path' => 'frontend/jupiter/stats/awstats_landing.html'],
              ];
            ?>
            <div class="mt-3 pt-3 border-top">
              <p class="fw-bold text-muted mb-2" style="font-size:11px;text-transform:uppercase;letter-spacing:.03em">Akses Cepat</p>
              <div class="row g-2">
                <?php $__currentLoopData = $shortcuts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <div class="col-4">
                    <a href="<?php echo e(route('client.services.login-panel', $service)); ?>?path=<?php echo e(urlencode($sc['path'])); ?>" target="_blank" rel="noopener"
                       class="d-flex flex-column align-items-center gap-2 p-2 rounded-3 border text-decoration-none text-center">
                      <i class="fa-solid <?php echo e($sc['icon']); ?> text-muted" style="font-size:14px"></i>
                      <span class="text-muted" style="font-size:10px;line-height:1.2"><?php echo e($sc['label']); ?></span>
                    </a>
                  </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </div>
            </div>
          <?php endif; ?>
        </div>

        <div class="card-public p-4">
          <h2 class="small fw-bold text-dark mb-1">Ganti Password cPanel</h2>
          <p class="text-muted mb-3" style="font-size:12px">Berlaku langsung, tidak perlu masukkan password lama.</p>
          <form method="POST" action="<?php echo e(route('client.services.change-password', $service)); ?>"
                data-confirm="Ganti password cPanel sekarang?" data-confirm-title="Ganti Password" data-confirm-style="warn" data-confirm-label="Ya, Ganti">
            <?php echo csrf_field(); ?>
            <div class="d-flex gap-2">
              <input type="password" name="new_password" id="pwField" class="form-control form-control-sm" required minlength="8">
              <button type="button" data-action="call" data-call="lumoraGeneratePassword" data-args='["pwField",null,"pwChecklist"]' class="btn btn-outline-secondary btn-sm text-nowrap flex-shrink-0">
                <i class="fa-solid fa-dice" style="font-size:11px"></i> Buatkan
              </button>
            </div>
            <ul id="pwChecklist" class="text-muted mt-2 mb-0 ps-0" style="font-size:11px;list-style:none"></ul>
            <button type="submit" class="btn btn-theme w-100 mt-3"><i class="fa-solid fa-key" style="font-size:11px"></i> Ganti Password</button>
          </form>
        </div>
      <?php endif; ?>

      <div class="card-public p-4">
        <h2 class="small fw-bold text-dark mb-2">Bantuan</h2>
        <p class="text-muted mb-3" style="font-size:14px">Ada kendala dengan layanan ini?</p>
        <a href="<?php echo e(route('client.tickets.create')); ?>" class="btn btn-theme w-100">
          <i class="fa-solid fa-headset" style="font-size:11px"></i> Hubungi Support
        </a>
      </div>

      
      <?php if($service->status !== 'terminated'): ?>
        <div class="card-public p-4">
          <h2 class="small fw-bold text-dark mb-3">Batalkan Layanan</h2>

          <?php if($service->cancellation_status === 'requested'): ?>
            <div class="rounded-3 px-3 py-2 mb-3" style="background:#fffbeb;border:1px solid #fde68a">
              <p class="fw-bold mb-1" style="font-size:12px;color:#92400e">
                <i class="fa-solid fa-clock"></i> Sedang ditinjau
              </p>
              <p class="mb-0" style="font-size:12px;color:#b45309">
                Diajukan <?php echo e($service->cancellation_requested_at?->diffForHumans()); ?>.
                Tim kami akan meninjau dalam 1x24 jam.
              </p>
            </div>
            <form method="POST" action="<?php echo e(route('client.services.cancel.withdraw', $service)); ?>">
              <?php echo csrf_field(); ?>
              <button type="submit" class="btn btn-outline-secondary w-100">Batalkan Pengajuan</button>
            </form>

          <?php elseif($service->cancellation_status === 'declined'): ?>
            <div class="rounded-3 px-3 py-2 mb-3" style="background:#f8fafc;border:1px solid #e2e8f0;font-size:12px;color:#475569">
              <p class="fw-bold mb-1">Pengajuan sebelumnya ditolak</p>
              <?php if($service->cancellation_admin_note): ?>
                <p class="mb-0"><?php echo e($service->cancellation_admin_note); ?></p>
              <?php endif; ?>
            </div>
            <button type="button" data-action="show" data-target="cancelForm" data-hide-self
                    class="btn btn-outline-danger w-100">
              Ajukan Kembali
            </button>
            <form id="cancelForm" method="POST" action="<?php echo e(route('client.services.cancel', $service)); ?>" class="d-none mt-3 d-flex flex-column gap-2">
              <?php echo csrf_field(); ?>
              <textarea name="reason" rows="3" class="form-control form-control-sm" placeholder="Alasan pembatalan..." required></textarea>
              <button type="submit" class="btn btn-danger w-100">Kirim Pengajuan</button>
            </form>

          <?php else: ?>
            <p class="text-muted mb-3" style="font-size:12px">
              Pengajuan akan ditinjau tim kami sebelum layanan benar-benar dihentikan — bukan otomatis.
            </p>
            <button type="button" data-action="show" data-target="cancelForm" data-hide-self
                    class="btn btn-outline-danger w-100">
              Ajukan Pembatalan
            </button>
            <form id="cancelForm" method="POST" action="<?php echo e(route('client.services.cancel', $service)); ?>" class="d-none mt-3 d-flex flex-column gap-2">
              <?php echo csrf_field(); ?>
              <textarea name="reason" rows="3" class="form-control form-control-sm" placeholder="Alasan pembatalan..." required></textarea>
              <button type="submit" class="btn btn-danger w-100">Kirim Pengajuan</button>
            </form>
          <?php endif; ?>

          <?php $__errorArgs = ['reason'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-2 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <script <?php echo 'nonce="' . e(app(\App\Support\CspNonce::class)->value()) . '"'; ?>>
    function lumoraPasswordChecks(pw) {
      return [
        { label: 'Minimal 8 karakter', ok: pw.length >= 8 },
        { label: 'Huruf besar & kecil', ok: /[a-z]/.test(pw) && /[A-Z]/.test(pw) },
        { label: 'Mengandung angka', ok: /[0-9]/.test(pw) },
        { label: 'Mengandung simbol (!@#$dst)', ok: /[^a-zA-Z0-9]/.test(pw) },
      ];
    }

    function lumoraRenderChecklist(pw, checklistId) {
      const el = document.getElementById(checklistId);
      if (!el) return;
      el.innerHTML = lumoraPasswordChecks(pw).map(c =>
        `<li class="${c.ok ? 'text-success' : 'text-muted'}" style="margin-bottom:.25rem"><i class="fa-solid ${c.ok ? 'fa-circle-check' : 'fa-circle'}" style="font-size:9px"></i> ${c.label}</li>`
      ).join('');
    }

    function lumoraGeneratePassword(pwFieldId, confirmFieldId, checklistId) {
      const upper = 'ABCDEFGHJKLMNPQRSTUVWXYZ';
      const lower = 'abcdefghijkmnpqrstuvwxyz';
      const digits = '23456789';
      const symbols = '!@#$%&*';
      const all = upper + lower + digits + symbols;

      const pick = (set) => set[Math.floor(Math.random() * set.length)];

      let pw = [pick(upper), pick(lower), pick(digits), pick(symbols)];
      for (let i = 0; i < 8; i++) pw.push(pick(all));
      pw = pw.sort(() => Math.random() - 0.5).join('');

      const pwField = document.getElementById(pwFieldId);
      pwField.value = pw;
      pwField.type = 'text';

      if (confirmFieldId) {
        const confirmField = document.getElementById(confirmFieldId);
        if (confirmField) confirmField.value = pw;
      }

      lumoraRenderChecklist(pw, checklistId);
    }

    (window.LumoraActions = window.LumoraActions || {}).lumoraGeneratePassword = lumoraGeneratePassword;
    document.addEventListener('DOMContentLoaded', () => {
      const pwField = document.getElementById('pwField');
      if (pwField) {
        pwField.addEventListener('input', () => lumoraRenderChecklist(pwField.value, 'pwChecklist'));
      }
    });
  </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('client.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/themes/client-themes/namahost/client/services/show.blade.php ENDPATH**/ ?>