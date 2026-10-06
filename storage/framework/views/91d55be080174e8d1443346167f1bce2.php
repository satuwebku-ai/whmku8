<?php $__env->startSection('title', 'Email'); ?>
<?php $__env->startSection('content'); ?>
  <?php echo $__env->make('admin.settings._nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

  <?php
    use App\Models\Setting;

    $has = fn ($key) => filled(Setting::get($key));
    $field = fn ($key, $default = '') => old($key, Setting::get($key, $default));
    $imapStatus = Setting::get('imap_last_status');
    $imapRunAt = Setting::get('imap_last_run_at');
    $imapMsg = Setting::get('imap_last_message');
  ?>

  <div class="mb-4">
    <h1 class="h4 fw-bold text-dark mb-1">Email</h1>
    <p class="small text-muted mb-0">Pengiriman email (SMTP) dan penerimaan balasan lewat email (IMAP). Disimpan di database, tidak perlu mengubah file .env.</p>
  </div>

  <form method="POST" action="<?php echo e(route('admin.settings.email.update')); ?>" style="max-width:42rem">
    <?php echo csrf_field(); ?>

    
    <div class="card border rounded-4 p-4 mb-4">
      <h2 class="h6 fw-bold text-dark mb-1"><i class="fa-solid fa-paper-plane me-1"></i> Email Keluar (SMTP)</h2>
      <p class="text-muted mb-3" style="font-size:12px">Dipakai untuk semua email aplikasi: notifikasi tiket, invoice, OTP, dan balasan live chat. Kalau Host dikosongkan, aplikasi memakai nilai MAIL_* dari .env.</p>

      <div class="row g-3">
        <div class="col-md-8">
          <label class="form-label small fw-medium text-dark">Host SMTP</label>
          <input type="text" name="mail_host" value="<?php echo e($field('mail_host')); ?>" class="form-control form-control-sm" placeholder="mail.domainanda.com" autocomplete="off">
          <?php $__errorArgs = ['mail_host'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        <div class="col-md-4">
          <label class="form-label small fw-medium text-dark">Port</label>
          <input type="number" name="mail_port" value="<?php echo e($field('mail_port', 465)); ?>" class="form-control form-control-sm" placeholder="465">
          <?php $__errorArgs = ['mail_port'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        <div class="col-md-6">
          <label class="form-label small fw-medium text-dark">Enkripsi</label>
          <?php $enc = $field('mail_encryption', 'ssl'); ?>
          <select name="mail_encryption" class="form-select form-select-sm">
            <option value="ssl" <?php if($enc === 'ssl'): echo 'selected'; endif; ?>>SSL/TLS (port 465)</option>
            <option value="tls" <?php if($enc === 'tls'): echo 'selected'; endif; ?>>STARTTLS (port 587)</option>
            <option value="none" <?php if($enc === 'none'): echo 'selected'; endif; ?>>Tanpa enkripsi</option>
          </select>
        </div>
        <div class="col-md-6">
          <label class="form-label small fw-medium text-dark">Username</label>
          <input type="text" name="mail_username" value="<?php echo e($field('mail_username')); ?>" class="form-control form-control-sm" placeholder="noreply@domainanda.com" autocomplete="off">
        </div>
        <div class="col-12">
          <label class="form-label small fw-medium text-dark">Password</label>
          <input type="password" name="mail_password" value="" class="form-control form-control-sm" autocomplete="new-password"
                 placeholder="<?php echo e($has('mail_password') ? '•••••••• (tersimpan terenkripsi — kosongkan jika tidak diubah)' : 'Password mailbox'); ?>">
          <?php $__errorArgs = ['mail_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        <div class="col-md-6">
          <label class="form-label small fw-medium text-dark">Alamat Pengirim (From)</label>
          <input type="email" name="mail_from_address" value="<?php echo e($field('mail_from_address')); ?>" class="form-control form-control-sm" placeholder="noreply@domainanda.com">
          <?php $__errorArgs = ['mail_from_address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        <div class="col-md-6">
          <label class="form-label small fw-medium text-dark">Nama Pengirim</label>
          <input type="text" name="mail_from_name" value="<?php echo e($field('mail_from_name')); ?>" class="form-control form-control-sm" placeholder="<?php echo e(config('app.name')); ?>">
        </div>
        <div class="col-12">
          <label class="form-label small fw-medium text-dark">Balas Ke (Reply-To)</label>
          <input type="email" name="mail_reply_to" value="<?php echo e($field('mail_reply_to')); ?>" class="form-control form-control-sm" placeholder="support@domainanda.com">
          <?php $__errorArgs = ['mail_reply_to'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          <p class="text-muted mt-1 mb-0" style="font-size:11px">Isi dengan mailbox support yang dibaca lewat IMAP di bawah. Saat klien menekan "Balas", email mereka dikirim ke alamat ini, bukan ke alamat noreply.</p>
        </div>

        <div class="col-12">
          <div class="d-flex align-items-end gap-2 flex-wrap">
            <div class="flex-grow-1" style="min-width:14rem">
              <label class="form-label small fw-medium text-dark">Kirim email uji ke</label>
              <input type="email" name="test_to" value="<?php echo e(old('test_to')); ?>" class="form-control form-control-sm" placeholder="emailanda@gmail.com">
              <?php $__errorArgs = ['test_to'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <button type="submit" formaction="<?php echo e(route('admin.settings.email.test-smtp')); ?>" formnovalidate class="btn btn-outline-secondary btn-sm">
              <i class="fa-solid fa-plug" style="font-size:11px"></i> Kirim Email Uji
            </button>
          </div>
          <p class="text-muted mt-1 mb-0" style="font-size:11px">Menguji nilai yang sedang diisi di atas, tanpa perlu menyimpan dulu.</p>
        </div>
      </div>
    </div>

    
    <div class="card border rounded-4 p-4 mb-4">
      <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
        <h2 class="h6 fw-bold text-dark mb-0"><i class="fa-solid fa-inbox me-1"></i> Email Masuk (IMAP)</h2>
        <?php if($imapStatus === 'success'): ?>
          <span class="badge badge-soft-success" title="<?php echo e($imapRunAt ? \Carbon\Carbon::parse($imapRunAt)->diffForHumans() : ''); ?>"><i class="fa-solid fa-check" style="font-size:10px"></i> Terakhir OK</span>
        <?php elseif($imapStatus === 'failed'): ?>
          <span class="badge badge-soft-danger" title="<?php echo e($imapRunAt ? \Carbon\Carbon::parse($imapRunAt)->diffForHumans() : ''); ?>"><i class="fa-solid fa-xmark" style="font-size:10px"></i> Terakhir gagal</span>
        <?php endif; ?>
      </div>
      <p class="text-muted mb-3" style="font-size:12px">
        Aplikasi membaca mailbox support secara berkala. Balasan email klien masuk ke tiket yang sama (nomor tiket ada di subjek).
        Email dari pengunjung atau alamat yang belum dikenal masuk ke menu <b>Email</b> (Dukungan → Email), dan balasan staf dari sana dikirim ke email mereka.
      </p>

      <?php if($imapStatus === 'failed' && $imapMsg): ?>
        <div class="rounded-3 px-3 py-2 mb-3" style="background:#fef2f2;border:1px solid #fecaca;font-size:12px;color:#991b1b">
          Pengambilan terakhir gagal: <?php echo e($imapMsg); ?>

        </div>
      <?php endif; ?>

      <?php $imapOn = old('imap_enabled', Setting::get('imap_enabled', '0')) == '1'; ?>
      <div class="form-check form-switch mb-3">
        <input type="checkbox" class="form-check-input" role="switch" id="imapEnabled" name="imap_enabled" value="1" <?php if($imapOn): echo 'checked'; endif; ?>>
        <label class="form-check-label small fw-medium text-dark" for="imapEnabled">Aktifkan penerimaan email</label>
      </div>

      <div class="row g-3">
        <div class="col-md-8">
          <label class="form-label small fw-medium text-dark">Host IMAP</label>
          <input type="text" name="imap_host" value="<?php echo e($field('imap_host')); ?>" class="form-control form-control-sm" placeholder="mail.domainanda.com" autocomplete="off">
          <?php $__errorArgs = ['imap_host'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        <div class="col-md-4">
          <label class="form-label small fw-medium text-dark">Port</label>
          <input type="number" name="imap_port" value="<?php echo e($field('imap_port', 993)); ?>" class="form-control form-control-sm" placeholder="993">
        </div>
        <div class="col-md-6">
          <label class="form-label small fw-medium text-dark">Enkripsi</label>
          <?php $ienc = $field('imap_encryption', 'ssl'); ?>
          <select name="imap_encryption" class="form-select form-select-sm">
            <option value="ssl" <?php if($ienc === 'ssl'): echo 'selected'; endif; ?>>SSL/TLS (port 993)</option>
            <option value="tls" <?php if($ienc === 'tls'): echo 'selected'; endif; ?>>STARTTLS (port 143)</option>
            <option value="none" <?php if($ienc === 'none'): echo 'selected'; endif; ?>>Tanpa enkripsi</option>
          </select>
        </div>
        <div class="col-md-6">
          <label class="form-label small fw-medium text-dark">Folder</label>
          <input type="text" name="imap_folder" value="<?php echo e($field('imap_folder', 'INBOX')); ?>" class="form-control form-control-sm" placeholder="INBOX">
        </div>
        <div class="col-md-6">
          <label class="form-label small fw-medium text-dark">Username (mailbox support)</label>
          <input type="text" name="imap_username" value="<?php echo e($field('imap_username')); ?>" class="form-control form-control-sm" placeholder="support@domainanda.com" autocomplete="off">
        </div>
        <div class="col-md-6">
          <label class="form-label small fw-medium text-dark">Password</label>
          <input type="password" name="imap_password" value="" class="form-control form-control-sm" autocomplete="new-password"
                 placeholder="<?php echo e($has('imap_password') ? '•••••••• (tersimpan)' : 'Password mailbox'); ?>">
        </div>
        <div class="col-12">
          <?php $verify = old('imap_verify_cert', Setting::get('imap_verify_cert', '1')) == '1'; ?>
          <div class="form-check">
            <input type="checkbox" class="form-check-input" id="imapVerify" name="imap_verify_cert" value="1" <?php if($verify): echo 'checked'; endif; ?>>
            <label class="form-check-label small text-dark" for="imapVerify">Verifikasi sertifikat SSL server</label>
          </div>
          <p class="text-muted mt-1 mb-0" style="font-size:11px">Matikan hanya jika server memakai sertifikat yang tidak cocok dengan nama host (umum di sebagian shared hosting).</p>
        </div>
        <div class="col-12">
          <button type="submit" formaction="<?php echo e(route('admin.settings.email.test-imap')); ?>" formnovalidate class="btn btn-outline-secondary btn-sm">
            <i class="fa-solid fa-plug" style="font-size:11px"></i> Uji Koneksi IMAP
          </button>
        </div>
      </div>

      <div class="rounded-3 px-3 py-2 mt-3" style="background:#f8fafc;border:1px solid #e2e8f0;font-size:12px;color:#475569">
        <i class="fa-solid fa-clock"></i>
        Pengambilan email berjalan lewat tugas <b>Ambil Email Masuk</b> di
        <a href="<?php echo e(route('admin.cron.index')); ?>" class="text-decoration-none">Cron Jobs</a>
        (default tiap 5 menit). Pastikan cron <code>lumora:cron</code> sudah terpasang di server.
        Gunakan mailbox khusus support, bukan mailbox pribadi: email yang dibaca akan ditandai sudah dibaca.
      </div>
    </div>

    <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-check" style="font-size:11px"></i> Simpan Pengaturan</button>
  </form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/admin/settings/email.blade.php ENDPATH**/ ?>