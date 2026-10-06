<?php $__env->startSection('title', 'Pengaturan Notifikasi'); ?>

<?php $__env->startSection('content'); ?>

  <?php echo $__env->make('admin.settings._nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

  <?php use App\Models\Setting; ?>

  <div class="mb-4 d-flex align-items-center justify-content-between flex-wrap gap-2">
    <div>
      <h1 class="h4 fw-bold text-dark mb-1">Notifikasi</h1>
      <p class="small text-muted mb-0">Atur email dan WhatsApp yang dikirim ke klien maupun ke Anda sendiri.</p>
    </div>
    <a href="<?php echo e(route('admin.notification-templates.index')); ?>" class="btn btn-outline-secondary btn-sm">
      <i class="fa-solid fa-pen-to-square" style="font-size:11px"></i> Edit Kata-Kata Template
    </a>
  </div>

  <form method="POST" action="<?php echo e(route('admin.settings.notifications.update')); ?>" style="max-width:56rem">
    <?php echo csrf_field(); ?>

    
    <div class="card border rounded-4 p-4 mb-3" style="border-color:#fecaca!important">
      <h2 class="small fw-bold text-dark mb-1">Auto-Suspend Layanan Telat Bayar</h2>
      <p class="text-muted mb-3" style="font-size:12px">
        Hosting yang invoice perpanjangannya tidak dibayar sampai melewati batas toleransi
        akan disuspend otomatis. Aktif kembali otomatis begitu invoice dibayar — tidak perlu
        admin membuka suspend manual.
      </p>

      <label class="d-flex align-items-start gap-2 rounded-3 border px-3 py-2 mb-3">
        <input type="checkbox" name="auto_suspend_enabled" value="1" <?php if(Setting::get('auto_suspend_enabled', '1') === '1'): echo 'checked'; endif; ?>
               class="form-check-input flex-shrink-0" style="margin-top:2px">
        <span>
          <span class="d-block small fw-medium text-dark">Aktifkan auto-suspend</span>
          <span class="d-block text-muted" style="font-size:11px">Matikan untuk menonaktifkan sementara tanpa menghapus jadwal cron.</span>
        </span>
      </label>

      <div class="row g-3">
        <div class="col-sm-6">
          <label class="form-label small fw-medium text-dark">Masa Toleransi (hari setelah jatuh tempo)</label>
          <input type="number" name="suspend_grace_days" min="0" max="30"
                 value="<?php echo e(Setting::get('suspend_grace_days', 3)); ?>" class="form-control form-control-sm">
          <p class="text-muted mt-1 mb-0" style="font-size:11px">0 = disuspend tepat di hari jatuh tempo, tanpa toleransi.</p>
        </div>
        <div class="col-sm-6">
          <label class="d-flex align-items-start gap-2 rounded-3 border px-3 py-2">
            <input type="checkbox" name="notify_suspend" value="1" <?php if(Setting::get('notify_suspend', '1') === '1'): echo 'checked'; endif; ?>
                   class="form-check-input flex-shrink-0" style="margin-top:2px">
            <span>
              <span class="d-block small fw-medium text-dark">Kirim email ke klien</span>
              <span class="d-block text-muted" style="font-size:11px">Saat layanan disuspend otomatis.</span>
            </span>
          </label>
        </div>
      </div>

      <div class="mt-3 rounded-3 px-3 py-2" style="background:#fffbeb;border:1px solid #fde68a;font-size:12px;color:#92400e">
        <i class="fa-solid fa-triangle-exclamation"></i>
        Uji dulu tanpa mengubah apa pun:
        <code class="d-block mt-1 px-2 py-1 rounded" style="background:rgba(255,255,255,.6)">php artisan lumora:suspend-overdue --dry</code>
      </div>
    </div>

    <div class="card border rounded-4 p-4 mb-3">
      <h2 class="small fw-bold text-dark mb-1">Invoice Perpanjangan Otomatis</h2>
      <p class="text-muted mb-3" style="font-size:12px">
        Invoice baru dibuat otomatis untuk hosting & domain yang masa aktifnya mendekati habis —
        klien tidak perlu ditagih manual satu per satu.
      </p>

      <div style="max-width:16rem">
        <label class="form-label small fw-medium text-dark">Buat Invoice Berapa Hari Sebelum Jatuh Tempo</label>
        <input type="number" name="renewal_invoice_days_before" min="1" max="60"
               value="<?php echo e(Setting::get('renewal_invoice_days_before', 7)); ?>" class="form-control form-control-sm">
        <p class="text-muted mt-1 mb-0" style="font-size:11px">
          Domain hanya diikutkan kalau opsi "Perpanjangan Otomatis" klien menyala.
        </p>
      </div>

      <div class="mt-3 rounded-3 px-3 py-2" style="background:#fffbeb;border:1px solid #fde68a;font-size:12px;color:#92400e">
        <i class="fa-solid fa-triangle-exclamation"></i>
        Hanya berjalan kalau cron sudah dipasang — sama seperti Pengingat Jatuh Tempo di bawah.
        Uji dulu tanpa membuat apa pun:
        <code class="d-block mt-1 px-2 py-1 rounded" style="background:rgba(255,255,255,.6)">php artisan lumora:generate-renewal-invoices --dry</code>
      </div>
    </div>

    <div class="card border rounded-4 p-4 mb-3">
      <h2 class="small fw-bold text-dark mb-1">Notifikasi ke Klien</h2>
      <p class="text-muted mb-3" style="font-size:12px">Dikirim otomatis ke email klien saat kejadian berikut terjadi.</p>

      <?php $__currentLoopData = [
        'notify_welcome' => ['Selamat datang', 'Saat klien selesai mendaftar.'],
        'notify_invoice' => ['Invoice baru', 'Saat tagihan terbit — PDF invoice ikut dilampirkan.'],
        'notify_paid'    => ['Pembayaran diterima', 'Saat invoice ditandai lunas.'],
        'notify_reminder'=> ['Pengingat jatuh tempo', 'Sebelum dan sesudah tanggal jatuh tempo.'],
        'notify_ticket_reply' => ['Balasan tiket support', 'Saat staf membalas tiket yang klien buka (bukan catatan internal).'],
      ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => [$judul, $ket]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <label class="d-flex align-items-start gap-2 rounded-3 border px-3 py-2 mb-2">
          <input type="checkbox" name="<?php echo e($key); ?>" value="1" <?php if(Setting::get($key, '1') === '1'): echo 'checked'; endif; ?>
                 class="form-check-input flex-shrink-0" style="margin-top:2px">
          <span>
            <span class="d-block small fw-medium text-dark"><?php echo e($judul); ?></span>
            <span class="d-block text-muted" style="font-size:11px"><?php echo e($ket); ?></span>
          </span>
        </label>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

      <div class="row g-3 mt-2 pt-3 border-top">
        <div class="col-sm-6">
          <label class="form-label small fw-medium text-dark">Pengingat Sebelum Jatuh Tempo</label>
          <input type="text" name="reminder_days_before" value="<?php echo e(Setting::get('reminder_days_before', '7,3,1')); ?>" class="form-control form-control-sm" placeholder="7,3,1">
          <?php $__errorArgs = ['reminder_days_before'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          <p class="text-muted mt-1 mb-0" style="font-size:11px">Jumlah hari, dipisah koma. "7,3,1" = dikirim H-7, H-3, dan H-1.</p>
        </div>
        <div class="col-sm-6">
          <label class="form-label small fw-medium text-dark">Pengingat Setelah Lewat Tempo</label>
          <input type="text" name="reminder_days_after" value="<?php echo e(Setting::get('reminder_days_after', '1,7')); ?>" class="form-control form-control-sm" placeholder="1,7">
          <?php $__errorArgs = ['reminder_days_after'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          <p class="text-muted mt-1 mb-0" style="font-size:11px">"1,7" = dikirim 1 hari dan 7 hari setelah lewat tempo.</p>
        </div>
      </div>

      <p class="text-muted mt-2 mb-0" style="font-size:11px">
        Jika beberapa tahap terlewat, hanya tahap terbaru yang masih relevan yang dikirim; tahap lama dilewati agar klien tidak menerima pesan bertubi-tubi.
      </p>

      <div class="mt-3 rounded-3 px-3 py-2" style="background:#fffbeb;border:1px solid #fde68a;font-size:12px;color:#92400e">
        <i class="fa-solid fa-triangle-exclamation"></i>
        Pengingat dijalankan oleh pusat Cron Jobs aplikasi. Pastikan hanya satu cron pusat
        <code>php artisan lumora:cron</code> yang dipasang dari
        <a href="<?php echo e(route('admin.cron.index')); ?>" class="fw-semibold">Pengaturan → Cron Jobs</a>;
        jangan memasang <code>schedule:run</code>.
      </div>
    </div>

    
    <div class="card border rounded-4 p-4 mb-3">
      <h2 class="small fw-bold text-dark mb-1">Notifikasi ke Admin</h2>
      <p class="text-muted mb-3" style="font-size:12px">Dikirim ke semua admin aktif, dan selalu tercatat di menu Aktivitas.</p>

      <?php $__currentLoopData = [
        'notify_admin_order'   => ['Pesanan baru masuk', 'Saat klien menyelesaikan checkout.'],
        'notify_admin_payment' => ['Pembayaran diterima', 'Saat invoice lunas.'],
        'notify_admin_ticket'  => ['Tiket support baru & balasan klien', 'Saat klien membuka tiket, atau membalas tiket yang sudah ada.'],
        'notify_admin_client'  => ['Klien baru mendaftar', 'Saat ada pendaftaran akun baru.'],
      ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => [$judul, $ket]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <label class="d-flex align-items-start gap-2 rounded-3 border px-3 py-2 mb-2">
          <input type="checkbox" name="<?php echo e($key); ?>" value="1" <?php if(Setting::get($key, '1') === '1'): echo 'checked'; endif; ?>
                 class="form-check-input flex-shrink-0" style="margin-top:2px">
          <span>
            <span class="d-block small fw-medium text-dark"><?php echo e($judul); ?></span>
            <span class="d-block text-muted" style="font-size:11px"><?php echo e($ket); ?></span>
          </span>
        </label>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    
    <div class="card border rounded-4 p-4 mb-3">
      <div class="d-flex align-items-center gap-2 mb-1">
        <h2 class="small fw-bold text-dark mb-0">WhatsApp</h2>
        <?php
          $waStatus = Setting::get('wa_last_test_status');
          $waTestedAt = Setting::get('wa_last_test_at');
        ?>
        <?php if($waStatus === 'success'): ?>
          <span class="badge badge-soft-success" title="Diuji <?php echo e(\Carbon\Carbon::parse($waTestedAt)->diffForHumans()); ?>">
            <i class="fa-solid fa-check" style="font-size:10px"></i> Success
          </span>
        <?php elseif($waStatus === 'failed'): ?>
          <span class="badge badge-soft-danger" title="Diuji <?php echo e(\Carbon\Carbon::parse($waTestedAt)->diffForHumans()); ?>">
            <i class="fa-solid fa-xmark" style="font-size:10px"></i> Ditolak
          </span>
        <?php endif; ?>
      </div>
      <p class="text-muted mb-3" style="font-size:12px">
        WhatsApp tidak punya API resmi yang murah untuk skala kecil, jadi dipakai gateway pihak ketiga.
        Klien hanya menerima WhatsApp kalau mereka sendiri mengaktifkannya di halaman Profil.
      </p>

      <div class="row g-3">
        <div class="col-sm-6">
          <label class="form-label small fw-medium text-dark">Gateway</label>
          <select name="wa_provider" id="waProvider" class="form-select" style="padding:.25rem .6rem;font-size:.875rem;border-radius:.375rem">
            <option value="none" <?php if(Setting::get('wa_provider', 'none') === 'none'): echo 'selected'; endif; ?>>Nonaktif</option>
            <option value="fonnte" <?php if(Setting::get('wa_provider') === 'fonnte'): echo 'selected'; endif; ?>>Fonnte</option>
            <option value="wablas" <?php if(Setting::get('wa_provider') === 'wablas'): echo 'selected'; endif; ?>>Wablas</option>
            <option value="custom" <?php if(Setting::get('wa_provider') === 'custom'): echo 'selected'; endif; ?>>Lainnya (JSON)</option>
          </select>
        </div>
        <div class="col-sm-6">
          <label class="form-label small fw-medium text-dark">Token <?php echo e(Setting::get('wa_token') ? '(kosongkan jika tidak diganti)' : ''); ?></label>
          <input type="password" name="wa_token" class="form-control form-control-sm" placeholder="<?php echo e(Setting::get('wa_token') ? '••••••••••••' : 'Token dari dashboard gateway'); ?>">
        </div>
      </div>

      <div class="row g-3 mt-1">
        <div id="waEndpointField" class="col-sm-6">
          <label class="form-label small fw-medium text-dark">Endpoint API</label>
          <input type="text" name="wa_endpoint" value="<?php echo e(Setting::get('wa_endpoint')); ?>" class="form-control form-control-sm" placeholder="https://console.wablas.com">
          <p class="text-muted mt-1 mb-0" style="font-size:11px">Wablas: domain akun Anda. Lainnya: URL lengkap endpoint kirim pesan.</p>
        </div>
        <div class="col-sm-6">
          <label class="form-label small fw-medium text-dark">Nomor WhatsApp Admin</label>
          <input type="text" name="wa_admin_number" value="<?php echo e(Setting::get('wa_admin_number')); ?>" class="form-control form-control-sm" placeholder="6281234567890">
          <p class="text-muted mt-1 mb-0" style="font-size:11px">Tujuan notifikasi admin lewat WhatsApp.</p>
        </div>
      </div>
    </div>

    
    <div class="card border rounded-4 p-4 mb-3">
      <div class="d-flex align-items-center gap-2 mb-1">
        <h2 class="small fw-bold text-dark mb-0">SMS</h2>
        <?php
          $smsStatus = Setting::get('sms_last_test_status');
          $smsTestedAt = Setting::get('sms_last_test_at');
        ?>
        <?php if($smsStatus === 'success'): ?>
          <span class="badge badge-soft-success" title="Diuji <?php echo e(\Carbon\Carbon::parse($smsTestedAt)->diffForHumans()); ?>">
            <i class="fa-solid fa-check" style="font-size:10px"></i> Success
          </span>
        <?php elseif($smsStatus === 'failed'): ?>
          <span class="badge badge-soft-danger" title="Diuji <?php echo e(\Carbon\Carbon::parse($smsTestedAt)->diffForHumans()); ?>">
            <i class="fa-solid fa-xmark" style="font-size:10px"></i> Ditolak
          </span>
        <?php endif; ?>
      </div>
      <p class="text-muted mb-3" style="font-size:12px">
        SMS berbayar per pesan, jadi cuma dipakai untuk kejadian yang benar-benar mendesak (kode login, tagihan, layanan
        disuspend) — lihat &amp; atur templatenya di menu Template Notifikasi. Klien hanya menerima SMS kalau mereka
        sendiri mengaktifkannya di halaman Profil.
      </p>

      <div class="row g-3">
        <div class="col-sm-6">
          <label class="form-label small fw-medium text-dark">Gateway</label>
          <select name="sms_provider" id="smsProvider" class="form-select" style="padding:.25rem .6rem;font-size:.875rem;border-radius:.375rem">
            <option value="none" <?php if(Setting::get('sms_provider', 'none') === 'none'): echo 'selected'; endif; ?>>Nonaktif</option>
            <option value="zenziva" <?php if(Setting::get('sms_provider') === 'zenziva'): echo 'selected'; endif; ?>>Zenziva</option>
            <option value="twilio" <?php if(Setting::get('sms_provider') === 'twilio'): echo 'selected'; endif; ?>>Twilio</option>
            <option value="custom" <?php if(Setting::get('sms_provider') === 'custom'): echo 'selected'; endif; ?>>Lainnya (JSON)</option>
          </select>
        </div>
        <div class="col-sm-6">
          <label class="form-label small fw-medium text-dark" id="smsUserkeyLabel">Userkey / Account SID</label>
          <input type="text" name="sms_userkey" value="<?php echo e(Setting::get('sms_userkey')); ?>" class="form-control form-control-sm" placeholder="Zenziva: Userkey. Twilio: Account SID.">
        </div>
      </div>

      <div class="row g-3 mt-1">
        <div class="col-sm-6">
          <label class="form-label small fw-medium text-dark" id="smsPasskeyLabel">Passkey / Auth Token <?php echo e(Setting::get('sms_passkey') ? '(kosongkan jika tidak diganti)' : ''); ?></label>
          <input type="password" name="sms_passkey" class="form-control form-control-sm" placeholder="<?php echo e(Setting::get('sms_passkey') ? '••••••••••••' : 'Zenziva: Passkey. Twilio: Auth Token.'); ?>">
        </div>
        <div id="smsSenderField" class="col-sm-6 d-none">
          <label class="form-label small fw-medium text-dark">Nomor Pengirim (Twilio)</label>
          <input type="text" name="sms_sender" value="<?php echo e(Setting::get('sms_sender')); ?>" class="form-control form-control-sm" placeholder="+15551234567">
          <p class="text-muted mt-1 mb-0" style="font-size:11px">Nomor Twilio yang dipakai untuk mengirim.</p>
        </div>
      </div>

      <div class="row g-3 mt-1">
        <div id="smsEndpointField" class="col-sm-6 d-none">
          <label class="form-label small fw-medium text-dark">Endpoint API</label>
          <input type="text" name="sms_endpoint" value="<?php echo e(Setting::get('sms_endpoint')); ?>" class="form-control form-control-sm" placeholder="https://gateway-anda.com/api/sms">
          <p class="text-muted mt-1 mb-0" style="font-size:11px">URL lengkap endpoint kirim SMS gateway custom Anda.</p>
        </div>
        <div class="col-sm-6">
          <label class="form-label small fw-medium text-dark">Nomor SMS Admin</label>
          <input type="text" name="sms_admin_number" value="<?php echo e(Setting::get('sms_admin_number')); ?>" class="form-control form-control-sm" placeholder="6281234567890">
          <p class="text-muted mt-1 mb-0" style="font-size:11px">Tujuan notifikasi admin lewat SMS.</p>
        </div>
      </div>
    </div>

    <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-check" style="font-size:11px"></i> Simpan Pengaturan</button>
  </form>

  
  <div class="card border rounded-4 p-4 mt-3" style="max-width:56rem">
    <h2 class="small fw-bold text-dark mb-1">Tes Kirim WhatsApp</h2>
    <p class="text-muted mb-3" style="font-size:12px">Simpan pengaturan dulu, baru kirim pesan percobaan.</p>

    <form method="POST" action="<?php echo e(route('admin.settings.notifications.test-wa')); ?>" class="d-flex gap-2">
      <?php echo csrf_field(); ?>
      <input type="text" name="test_number" class="form-control form-control-sm flex-grow-1" placeholder="6281234567890" required>
      <button type="submit" class="btn btn-outline-secondary btn-sm flex-shrink-0">
        <i class="fa-brands fa-whatsapp"></i> Kirim Tes
      </button>
    </form>
  </div>

  
  <div class="card border rounded-4 p-4 mt-3" style="max-width:56rem">
    <h2 class="small fw-bold text-dark mb-1">Tes Kirim SMS</h2>
    <p class="text-muted mb-3" style="font-size:12px">Simpan pengaturan dulu, baru kirim SMS percobaan.</p>

    <form method="POST" action="<?php echo e(route('admin.settings.notifications.test-sms')); ?>" class="d-flex gap-2">
      <?php echo csrf_field(); ?>
      <input type="text" name="test_number" class="form-control form-control-sm flex-grow-1" placeholder="6281234567890" required>
      <button type="submit" class="btn btn-outline-secondary btn-sm flex-shrink-0">
        <i class="fa-solid fa-comment-sms"></i> Kirim Tes
      </button>
    </form>
  </div>

  
  <div class="card border rounded-4 p-4 mt-3" style="max-width:56rem">
    <div class="d-flex align-items-center gap-2 mb-1">
      <h2 class="small fw-bold text-dark mb-0">Push Notification (Browser)</h2>
      <?php if(Setting::get('vapid_public_key')): ?>
        <span class="badge badge-soft-success"><i class="fa-solid fa-check" style="font-size:10px"></i> Aktif</span>
      <?php else: ?>
        <span class="badge badge-soft-secondary">Belum diatur</span>
      <?php endif; ?>
    </div>
    <p class="text-muted mb-3" style="font-size:12px">
      Notifikasi langsung ke browser klien/admin tanpa gateway pihak ketiga — gratis per pesan. Klien & admin
      mengaktifkannya sendiri lewat tombol di halaman Profil masing-masing setelah kunci di bawah ini dibuat.
    </p>

    <?php if(Setting::get('vapid_public_key')): ?>
      <div class="mb-3">
        <label class="form-label small fw-medium text-dark">Kunci Publik VAPID</label>
        <input type="text" value="<?php echo e(Setting::get('vapid_public_key')); ?>" class="form-control form-control-sm bg-light" readonly data-action="select">
      </div>
    <?php endif; ?>

    <div class="d-flex align-items-center gap-2 flex-wrap">
      <form method="POST" action="<?php echo e(route('admin.settings.notifications.vapid-keys')); ?>"
            data-confirm="<?php echo e(Setting::get('vapid_public_key') ? 'Kunci VAPID sudah ada dan sedang dipakai. Membuat kunci baru akan MEMATIKAN semua langganan push yang sudah aktif (klien & admin harus mengaktifkan ulang dari nol). Lanjutkan?' : 'Buat kunci VAPID baru untuk mengaktifkan Push Notification?'); ?>"
            data-confirm-title="<?php echo e(Setting::get('vapid_public_key') ? 'Ganti Kunci VAPID?' : 'Aktifkan Push Notification'); ?>"
            data-confirm-style="<?php echo e(Setting::get('vapid_public_key') ? 'danger' : 'info'); ?>"
            data-confirm-label="Ya, Lanjutkan">
        <?php echo csrf_field(); ?>
        <button type="submit" class="btn btn-outline-secondary btn-sm">
          <i class="fa-solid fa-key" style="font-size:11px"></i> <?php echo e(Setting::get('vapid_public_key') ? 'Buat Ulang Kunci' : 'Buat Kunci VAPID'); ?>

        </button>
      </form>

      <?php if(Setting::get('vapid_public_key')): ?>
        <form method="POST" action="<?php echo e(route('admin.settings.notifications.test-push')); ?>">
          <?php echo csrf_field(); ?>
          <button type="submit" class="btn btn-outline-secondary btn-sm">
            <i class="fa-solid fa-bell" style="font-size:11px"></i> Tes Push ke Akun Saya
          </button>
        </form>
      <?php endif; ?>
    </div>
    <p class="text-muted mt-2 mb-0" style="font-size:11px">
      Tombol tes mengirim ke langganan push akun ANDA sendiri (aktifkan dulu lewat halaman Profil kalau belum).
    </p>
  </div>

  <script <?php echo 'nonce="' . e(app(\App\Support\CspNonce::class)->value()) . '"'; ?>>
    // Endpoint hanya relevan untuk Wablas dan gateway custom.
    (function () {
      const provider = document.getElementById('waProvider');
      const endpoint = document.getElementById('waEndpointField');

      function sync() {
        endpoint.classList.toggle('d-none', !['wablas', 'custom'].includes(provider.value));
      }

      provider.addEventListener('change', sync);
      sync();
    })();

    // SMS: label Userkey/Passkey & field yang relevan beda tiap provider.
    (function () {
      const provider = document.getElementById('smsProvider');
      const userkeyLabel = document.getElementById('smsUserkeyLabel');
      const passkeyLabel = document.getElementById('smsPasskeyLabel');
      const senderField = document.getElementById('smsSenderField');
      const endpointField = document.getElementById('smsEndpointField');
      const passkeyKeptNote = passkeyLabel.textContent.includes('kosongkan') ? ' (kosongkan jika tidak diganti)' : '';

      const labels = {
        zenziva: ['Userkey', 'Passkey'],
        twilio: ['Account SID', 'Auth Token'],
        custom: ['Username / Key (opsional)', 'Token / Bearer'],
        none: ['Userkey / Account SID', 'Passkey / Auth Token'],
      };

      function sync() {
        const [userkeyText, passkeyText] = labels[provider.value] || labels.none;
        userkeyLabel.textContent = userkeyText;
        passkeyLabel.textContent = passkeyText + passkeyKeptNote;

        senderField.classList.toggle('d-none', provider.value !== 'twilio');
        endpointField.classList.toggle('d-none', provider.value !== 'custom');
      }

      provider.addEventListener('change', sync);
      sync();
    })();
  </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/admin/settings/notifications.blade.php ENDPATH**/ ?>