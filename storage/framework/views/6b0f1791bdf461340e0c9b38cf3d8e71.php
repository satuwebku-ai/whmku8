<?php $__env->startSection('title', 'Detail Hosting Account — ' . $account->domain); ?>

<?php $__env->startSection('content'); ?>

  <?php
    $statusBadge = [
      'active' => 'badge-soft-success', 'pending' => 'badge-soft-warning',
      'suspended' => 'badge-soft-danger', 'terminated' => 'badge-soft-secondary',
    ];
  ?>

  <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <div>
      <a href="<?php echo e(route('admin.hosting-accounts')); ?>" class="text-decoration-none text-muted" style="font-size:12px"><i class="fa-solid fa-arrow-left"></i> Kembali ke Hosting Account</a>
      <h1 class="h4 fw-bold text-dark mt-1 mb-0"><?php echo e($account->domain); ?></h1>
    </div>
    <span class="badge <?php echo e($statusBadge[$account->status] ?? 'badge-soft-secondary'); ?>" style="font-size:13px;padding:.4rem .8rem"><?php echo e(ucfirst($account->status)); ?></span>
  </div>

  <div class="row g-3">
    <div class="col-12 col-lg-8">

      <?php if($account->credentials_email_failed_at): ?>
        <div class="card border rounded-4 p-4 mb-3" style="background:#fef2f2;border-color:#fecaca!important">
          <p class="small fw-bold mb-1" style="color:#991b1b">
            <i class="fa-solid fa-envelope-circle-check"></i> Email info akun ke klien gagal terkirim
          </p>
          <p class="mb-0" style="font-size:12px;color:#b91c1c">
            Percobaan terakhir <?php echo e($account->credentials_email_failed_at->format('d M Y H:i')); ?>. Password tidak disimpan, jadi gunakan tombol kirim info
            (membuat password baru lalu mengirimnya ke klien).
          </p>
        </div>
      <?php endif; ?>

      <?php if($account->cancellation_status === 'requested'): ?>
        <div class="card border rounded-4 p-4 mb-3" style="background:#fffbeb;border-color:#fde68a!important">
          <p class="small fw-bold mb-1" style="color:#92400e">
            <i class="fa-solid fa-triangle-exclamation"></i> Klien mengajukan pembatalan
          </p>
          <p class="text-muted mb-2" style="font-size:12px;color:#b45309!important">
            Diajukan <?php echo e($account->cancellation_requested_at?->diffForHumans()); ?>. Alasan dari klien:
          </p>
          <p class="small bg-white rounded-3 border px-3 py-2 mb-3" style="border-color:#fde68a!important">
            <?php echo e($account->cancellation_reason); ?>

          </p>

          <div class="row g-2">
            <div class="col-sm-6">
              <form method="POST" action="<?php echo e(route('admin.hosting-accounts.cancellation.approve', $account)); ?>"
                    data-confirm="Setujui pembatalan? Layanan akan langsung dihentikan (terminate)."
                    data-confirm-title="Setujui Pembatalan" data-confirm-style="danger" data-confirm-label="Ya, Hentikan Layanan">
                <?php echo csrf_field(); ?>
                <input type="text" name="admin_note" placeholder="Catatan (opsional)" class="form-control form-control-sm mb-2">
                <button type="submit" class="btn btn-danger btn-sm w-100">
                  <i class="fa-solid fa-check" style="font-size:11px"></i> Setujui & Hentikan
                </button>
              </form>
            </div>
            <div class="col-sm-6">
              <form method="POST" action="<?php echo e(route('admin.hosting-accounts.cancellation.decline', $account)); ?>">
                <?php echo csrf_field(); ?>
                <input type="text" name="admin_note" placeholder="Alasan penolakan (opsional)" class="form-control form-control-sm mb-2">
                <button type="submit" class="btn btn-outline-secondary btn-sm w-100">
                  <i class="fa-solid fa-xmark" style="font-size:11px"></i> Tolak Pengajuan
                </button>
              </form>
            </div>
          </div>
        </div>
      <?php endif; ?>

      <div class="card border rounded-4 p-4 mb-3">
        <h2 class="small fw-bold text-dark mb-3">Informasi Akun</h2>
        <div class="row g-3 small">
          <div class="col-sm-6">
            <p class="text-muted mb-1" style="font-size:11px">KLIEN</p>
            <p class="fw-medium text-dark mb-0"><?php echo e($account->client->name ?? '—'); ?></p>
          </div>
          <div class="col-sm-6">
            <p class="text-muted mb-1" style="font-size:11px">PAKET</p>
            <p class="fw-medium text-dark mb-0"><?php echo e($account->package); ?></p>
          </div>
          <div class="col-sm-6">
            <p class="text-muted mb-1" style="font-size:11px">SERVER</p>
            <p class="fw-medium text-dark mb-0"><?php echo e($account->serverModel->name ?? 'Manual (tidak terhubung)'); ?></p>
          </div>
          <div class="col-sm-6">
            <p class="text-muted mb-1" style="font-size:11px">USERNAME PANEL</p>
            <p class="fw-medium text-dark mb-0"><?php echo e($account->username ?? '—'); ?></p>
          </div>
          <div class="col-sm-6">
            <p class="text-muted mb-1" style="font-size:11px">HARGA</p>
            <p class="fw-medium text-dark mb-0">Rp <?php echo e(number_format($account->price, 0, ',', '.')); ?> / <?php echo e(str_replace('_', ' ', $account->billing_cycle)); ?></p>
            <?php if($account->activeAddons->isNotEmpty() || $account->options->isNotEmpty()): ?>
              <p class="text-muted mb-0" style="font-size:11px">Total dengan opsi & addon: Rp <?php echo e(number_format($account->renewalAmount(), 0, ',', '.')); ?> / <?php echo e(str_replace('_', ' ', $account->billing_cycle)); ?></p>
            <?php endif; ?>
          </div>
          <div class="col-sm-6">
            <p class="text-muted mb-1" style="font-size:11px">JATUH TEMPO</p>
            <p class="fw-medium text-dark mb-0"><?php echo e($account->next_due_date?->format('d M Y') ?? '—'); ?></p>
          </div>
          <?php if(! is_null($sslStatus)): ?>
            <div class="col-sm-6">
              <p class="text-muted mb-1" style="font-size:11px">SSL</p>
              <p class="mb-0">
                <?php if($sslStatus['installed']): ?>
                  <span class="badge badge-soft-success"><i class="fa-solid fa-lock" style="font-size:10px"></i> Aktif</span>
                  <?php if($sslStatus['expires_at']): ?>
                    <span class="text-muted ms-1" style="font-size:11px">s.d. <?php echo e($sslStatus['expires_at']); ?></span>
                  <?php endif; ?>
                <?php else: ?>
                  <span class="badge badge-soft-secondary"><i class="fa-solid fa-lock-open" style="font-size:10px"></i> Tidak Ada</span>
                <?php endif; ?>
              </p>
            </div>
          <?php endif; ?>
        </div>

        <?php if($account->provision_status !== 'provisioned'): ?>
          <div class="mt-3 pt-3 border-top small">
            <p class="text-muted mb-0" style="font-size:11px">STATUS PROVISIONING TERAKHIR</p>
            <p class="mb-0 mt-1 <?php echo e($account->provision_status === 'manual' ? 'text-muted' : 'text-danger'); ?>">
              <?php echo e($account->provision_message ?: ($account->provision_status === 'manual' ? '(belum pernah dicoba — masih menunggu pemicu otomatis atau diproses manual admin)' : '(tidak ada keterangan)')); ?>

            </p>
            <div class="d-flex align-items-center gap-2 mt-2 flex-wrap">
              <form method="POST" action="<?php echo e(route('admin.hosting-accounts.retry', $account)); ?>"
                    data-confirm="Coba provisikan hosting account ini sekarang? Cuma pilih ini kalau YAKIN akunnya belum ada sama sekali di server (cek dulu di halaman Diagnosa Server)." data-confirm-title="Coba Provisikan" data-confirm-style="warn" data-confirm-label="Ya, Coba Buat Baru">
                <?php echo csrf_field(); ?>
                <button type="submit" class="btn btn-outline-secondary btn-sm">
                  <i class="fa-solid fa-rotate-right" style="font-size:11px"></i> Coba Provisikan (Buat Baru)
                </button>
              </form>
              <form method="POST" action="<?php echo e(route('admin.hosting-accounts.sync', $account)); ?>"
                    data-confirm="Sinkronkan catatan kita dari kondisi sungguhan di server? Pilih ini kalau akunnya SUDAH ada di server (lihat halaman Diagnosa Server)." data-confirm-title="Sinkronkan dari Server" data-confirm-style="info" data-confirm-label="Ya, Sinkronkan">
                <?php echo csrf_field(); ?>
                <button type="submit" class="btn btn-outline-secondary btn-sm">
                  <i class="fa-solid fa-rotate" style="font-size:11px"></i> Sinkronkan dari Server
                </button>
              </form>
            </div>
            <p class="text-muted mt-2 mb-0" style="font-size:11px">
              Cek dulu di <a href="<?php echo e(route('admin.servers.diagnostics', $account->server_id)); ?>" class="text-accent">Diagnosa Server</a> — kalau domain ini sudah tertulis "Ada di server", pakai <b>Sinkronkan</b>, bukan Coba Provisikan.
            </p>
          </div>
        <?php endif; ?>

        <?php if($account->activeAddons->isNotEmpty() || $account->options->isNotEmpty()): ?>
          <div class="mt-3 pt-3 border-top">
            <p class="text-muted mb-2" style="font-size:11px">OPSI KONFIGURASI & ADDON TERPASANG</p>
            <div class="d-flex flex-column gap-1">
              <?php $__currentLoopData = $account->options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="d-flex align-items-center justify-content-between" style="font-size:13px">
                  <span class="text-dark"><?php echo e($option->name); ?> <span class="text-muted" style="font-size:11px">(<?php echo e($option->group_name); ?>)</span></span>
                  <span class="fw-medium text-dark">Rp <?php echo e(number_format($option->price, 0, ',', '.')); ?></span>
                </div>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              <?php $__currentLoopData = $account->activeAddons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $addon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="d-flex align-items-center justify-content-between" style="font-size:13px">
                  <span class="text-dark"><?php echo e($addon->name); ?> <span class="text-muted" style="font-size:11px">(addon)</span></span>
                  <span class="fw-medium text-dark">Rp <?php echo e(number_format($addon->price, 0, ',', '.')); ?></span>
                </div>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
          </div>
        <?php endif; ?>
      </div>

      <?php if($account->orders->isNotEmpty()): ?>
        <div class="card border rounded-4 p-4 mb-3">
          <h2 class="small fw-bold text-dark mb-2">Order Terkait</h2>
          <?php
            // Beda dari $statusBadge di atas (itu untuk HostingAccount::status,
            // string biasa) -- Order::status di-cast ke enum OrderStatus,
            // jadi butuh peta terpisah dikunci pakai ->value.
            $orderStatusBadge = [
              'draft' => 'badge-soft-secondary', 'requirements_pending' => 'badge-soft-warning',
              'requirements_review' => 'badge-soft-warning', 'requirements_rejected' => 'badge-soft-danger',
              'requirements_approved' => 'badge-soft-info', 'pending' => 'badge-soft-warning',
              'pending_payment' => 'badge-soft-warning', 'paid' => 'badge-soft-info',
              'provisioning' => 'badge-soft-info', 'completed' => 'badge-soft-success',
              'failed' => 'badge-soft-danger', 'cancelled' => 'badge-soft-secondary',
              'expired' => 'badge-soft-secondary',
            ];
          ?>
          <?php $__currentLoopData = $account->orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('admin.orders.details', $order)); ?>" class="d-flex align-items-center justify-content-between py-2 small text-decoration-none border-bottom text-dark">
              <span>#<?php echo e($order->order_number); ?> — <?php echo e($order->product_name); ?></span>
              <span class="badge <?php echo e($orderStatusBadge[$order->status->value] ?? 'badge-soft-secondary'); ?>"><?php echo e(ucfirst(str_replace('_', ' ', $order->status->value))); ?></span>
            </a>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
      <?php endif; ?>

      <div class="card border rounded-4 p-4">
        <h2 class="small fw-bold text-dark mb-2">Catatan Internal</h2>
        <form method="POST" action="<?php echo e(route('admin.hosting-account.notes')); ?>">
          <?php echo csrf_field(); ?>
          <input type="hidden" name="hosting_account_id" value="<?php echo e($account->id); ?>">
          <textarea name="internal_notes" rows="4" class="form-control form-control-sm" placeholder="Catatan staf tentang akun ini..."><?php echo e(old('internal_notes', $account->internal_notes)); ?></textarea>
          <button type="submit" class="btn btn-outline-secondary btn-sm mt-2"><i class="fa-solid fa-floppy-disk" style="font-size:11px"></i> Simpan Catatan</button>
        </form>
      </div>
    </div>

    <div class="col-12 col-lg-4">
      <div class="card border rounded-4 p-4 mb-3">
        <h2 class="small fw-bold text-dark mb-3">Riwayat Provisioning</h2>
        <?php $__empty_1 = true; $__currentLoopData = $account->provisionings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attempt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
          <div class="pb-3 mb-3 border-bottom">
            <div class="d-flex justify-content-between align-items-center gap-2">
              <span class="badge <?php echo e($attempt->status === 'succeeded' ? 'badge-soft-success' : ($attempt->status === 'running' ? 'badge-soft-warning' : ($attempt->status === 'failed' ? 'badge-soft-danger' : 'badge-soft-secondary'))); ?>">
                <?php echo e(ucfirst($attempt->status)); ?>

              </span>
              <?php if($attempt->attempt_number > 0): ?><span class="small text-muted">Percobaan #<?php echo e($attempt->attempt_number); ?></span><?php endif; ?>
            </div>
            <p class="small text-dark mt-2 mb-1"><?php echo e($attempt->message ?: 'Tidak ada detail.'); ?></p>
            <?php if($attempt->server): ?>
              <p class="small text-muted mb-1">Server: <?php echo e($attempt->server->name); ?><?php echo e($attempt->serverPackage ? ' · ' . $attempt->serverPackage->name : ''); ?></p>
            <?php endif; ?>
            <p class="small text-muted mb-0">
              <?php echo e($attempt->started_at?->format('d M Y H:i:s') ?? $attempt->created_at?->format('d M Y H:i:s')); ?>

              <?php if($attempt->finished_at): ?> — selesai <?php echo e($attempt->finished_at->format('d M Y H:i:s')); ?><?php endif; ?>
            </p>
          </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
          <p class="small text-muted mb-0">Belum ada riwayat provisioning.</p>
        <?php endif; ?>
      </div>

      <div class="card border rounded-4 p-4 mb-3">
        <h2 class="small fw-bold text-dark mb-3">Riwayat Status Layanan</h2>
        <?php $__empty_1 = true; $__currentLoopData = $account->logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $entry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
          <div class="pb-3 mb-3 border-bottom">
            <div class="d-flex justify-content-between gap-2">
              <span class="small fw-bold text-dark"><?php echo e(ucfirst(str_replace('_', ' ', $entry->action))); ?></span>
              <span class="small text-muted text-nowrap"><?php echo e($entry->created_at?->format('d M Y H:i')); ?></span>
            </div>
            <?php if($entry->message): ?><p class="small text-muted mt-1 mb-0"><?php echo e($entry->message); ?></p><?php endif; ?>
            <?php if($entry->admin): ?><p class="small text-muted mt-1 mb-0">Oleh <?php echo e($entry->admin->name); ?></p><?php endif; ?>
          </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
          <p class="small text-muted mb-0">Belum ada riwayat suspend, unsuspend, atau terminate.</p>
        <?php endif; ?>
      </div>

      <div class="card border rounded-4 p-4 mb-3">
        <h2 class="small fw-bold text-dark mb-2">Aksi</h2>
        <div class="d-flex flex-column gap-2">
          <?php if($account->serverModel && $account->username): ?>
            <?php if($account->status !== 'suspended' && $account->status !== 'terminated'): ?>
              <form method="POST" action="<?php echo e(route('admin.hosting-accounts.suspend', $account)); ?>" data-confirm="Suspend akun ini di server?" data-confirm-title="Suspend Layanan" data-confirm-style="warn" data-confirm-label="Ya, Suspend">
                <?php echo csrf_field(); ?>
                <button type="submit" class="btn btn-outline-warning btn-sm w-100 text-start"><i class="fa-solid fa-pause" style="font-size:11px"></i> Suspend</button>
              </form>
            <?php endif; ?>
            <?php if($account->status === 'suspended'): ?>
              <form method="POST" action="<?php echo e(route('admin.hosting-accounts.unsuspend', $account)); ?>">
                <?php echo csrf_field(); ?>
                <button type="submit" class="btn btn-primary btn-sm w-100 text-start"><i class="fa-solid fa-play" style="font-size:11px"></i> Unsuspend</button>
              </form>
            <?php endif; ?>
            <?php if($account->status !== 'terminated'): ?>
              <form method="POST" action="<?php echo e(route('admin.hosting-accounts.terminate', $account)); ?>" data-confirm="Terminate akun ini? Akan DIHAPUS dari server dan tidak bisa dikembalikan." data-confirm-title="Hapus Data" data-confirm-style="danger" data-confirm-label="Ya, Hapus">
                <?php echo csrf_field(); ?>
                <button type="submit" class="btn btn-outline-danger btn-sm w-100 text-start"><i class="fa-solid fa-power-off" style="font-size:11px"></i> Terminate</button>
              </form>
            <?php endif; ?>
          <?php else: ?>
            <p class="text-muted mb-0" style="font-size:12px">Akun manual (tidak terhubung ke server), aksi API tidak tersedia. Ubah status lewat form Edit.</p>
          <?php endif; ?>
          <a href="<?php echo e(route('admin.hosting-account.edit.page', $account)); ?>" class="btn btn-outline-secondary btn-sm w-100 text-start">
            <i class="fa-regular fa-pen-to-square" style="font-size:11px"></i> Edit Data
          </a>
        </div>
      </div>

      <?php if($account->serverModel && $account->username): ?>
        <div class="card border rounded-4 p-4">
          <h2 class="small fw-bold text-dark mb-1">Ganti Password cPanel</h2>
          <p class="text-muted mb-3" style="font-size:12px">Password akun cPanel klien ini di server — langsung berlaku, tidak perlu password lama.</p>
          <form method="POST" action="<?php echo e(route('admin.hosting-accounts.change-password', $account)); ?>"
                data-confirm="Ganti password cPanel akun ini sekarang?" data-confirm-title="Ganti Password" data-confirm-style="warn" data-confirm-label="Ya, Ganti">
            <?php echo csrf_field(); ?>
            <div class="d-flex gap-2">
              <input type="password" name="new_password" id="pwField" class="form-control form-control-sm" required minlength="8">
              <button type="button" data-action="call" data-call="lumoraGeneratePassword" data-args='["pwField",null,"pwChecklist"]' class="btn btn-outline-secondary btn-sm text-nowrap flex-shrink-0">
                <i class="fa-solid fa-dice" style="font-size:11px"></i> Buatkan
              </button>
            </div>
            <ul id="pwChecklist" class="text-muted mt-2 mb-0 ps-0" style="font-size:11px;list-style:none"></ul>
            <button type="submit" class="btn btn-primary btn-sm w-100 mt-2"><i class="fa-solid fa-key" style="font-size:11px"></i> Ganti Password</button>
          </form>

          <hr class="my-3">

          <h2 class="small fw-bold text-dark mb-1">Kirim Info Akun</h2>
          <p class="text-muted mb-3" style="font-size:12px">
            Untuk klien yang kehilangan email awal. Password lama TIDAK
            tersimpan di sistem, jadi tombol ini akan membuat password
            BARU lalu mengirimkannya ke email klien.
          </p>
          <form method="POST" action="<?php echo e(route('admin.hosting-accounts.send-info', $account)); ?>"
                data-confirm="Buat password baru & kirim info akun ke email klien?" data-confirm-title="Kirim Info Akun" data-confirm-style="warn" data-confirm-label="Ya, Kirim">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn btn-outline-secondary btn-sm w-100"><i class="fa-solid fa-paper-plane" style="font-size:11px"></i> Kirim Info Akun (Reset Password)</button>
          </form>
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

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/admin/hosting-accounts/details.blade.php ENDPATH**/ ?>