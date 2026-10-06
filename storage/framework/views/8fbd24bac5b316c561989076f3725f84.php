<?php $__env->startSection('title', 'Profil Saya'); ?>

<?php $__env->startSection('content'); ?>
  <div class="mb-4">
    <h1 class="h4 fw-bold text-dark mb-1">Profil Saya</h1>
    <p class="text-muted mb-0">Perbarui data akun dan password Anda.</p>
  </div>

  <?php if($client->google_id): ?>
    <div class="card-public p-3 mb-4 d-flex align-items-center gap-3" style="border-color:#c7d2fe!important;background:rgba(79,70,229,.04)">
      <?php if($client->avatar): ?>
        <img src="<?php echo e($client->avatar); ?>" alt="<?php echo e($client->name); ?>" class="rounded-circle" style="width:40px;height:40px;object-fit:cover">
      <?php endif; ?>
      <p class="text-muted mb-0" style="font-size:12px">
        <i class="fa-brands fa-google text-theme"></i>
        Akun ini tertaut dengan Google (<b class="text-dark"><?php echo e($client->email); ?></b>). Anda bisa mengatur password
        lewat kode OTP (email/WhatsApp) di bawah kalau ingin bisa masuk tanpa Google juga.
      </p>
    </div>
  <?php endif; ?>

  <div class="row g-4">

    <div class="col-12 col-lg-6">
      <div class="card-public p-4">
        <h2 class="small fw-bold text-dark mb-3">Data Akun</h2>

        <?php if($client->pending_email): ?>
          <div class="rounded-3 border p-3 mb-3" style="border-color:#fde68a!important;background:#fffbeb">
            <p class="fw-semibold mb-1" style="font-size:13px;color:#92400e"><i class="fa-solid fa-envelope-circle-check"></i> Menunggu verifikasi email baru</p>
            <p class="mb-2" style="font-size:12px;color:#92400e">
              Kami mengirim kode 6 digit ke <b><?php echo e($client->pending_email); ?></b>. Email akun tetap
              <b><?php echo e($client->email); ?></b> sampai kode dimasukkan.
            </p>
            <form method="POST" action="<?php echo e(route('client.profile.email.verify')); ?>" class="d-flex gap-2 mb-2">
              <?php echo csrf_field(); ?>
              <input type="text" name="code" inputmode="numeric" autocomplete="one-time-code" maxlength="6" placeholder="Kode 6 digit" class="form-control form-control-sm" style="max-width:10rem" required>
              <button type="submit" class="btn btn-theme btn-sm">Verifikasi</button>
            </form>
            <?php $__errorArgs = ['code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mb-2" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            <div class="d-flex gap-3">
              <form method="POST" action="<?php echo e(route('client.profile.email.resend')); ?>"><?php echo csrf_field(); ?><button type="submit" class="btn btn-link p-0" style="font-size:12px">Kirim ulang kode</button></form>
              <form method="POST" action="<?php echo e(route('client.profile.email.cancel')); ?>"><?php echo csrf_field(); ?><button type="submit" class="btn btn-link p-0 text-danger" style="font-size:12px">Batalkan</button></form>
            </div>
          </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('client.profile.update')); ?>" class="d-flex flex-column gap-3">
          <?php echo csrf_field(); ?>

          <div>
            <label class="form-label">Nama Lengkap</label>
            <input type="text" name="name" value="<?php echo e(old('name', $client->name)); ?>" required class="form-control">
            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>

          <div>
            <label class="form-label">Email</label>
            <input type="email" name="email" id="profileEmail" value="<?php echo e(old('email', $client->email)); ?>" required class="form-control" <?php if($client->google_id): ?> readonly <?php endif; ?>>
            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            <?php if($client->google_id): ?>
              <p class="text-muted mt-1 mb-0" style="font-size:11px">Email akun Google tidak bisa diganti dari sini.</p>
            <?php else: ?>
              <div id="emailPasswordBox" class="mt-2 <?php echo e(old('current_password') !== null || $errors->has('current_password') ? '' : 'd-none'); ?>">
                <label class="form-label" style="font-size:12px">Password saat ini <span class="text-danger">*</span></label>
                <input type="password" name="current_password" autocomplete="current-password" class="form-control" placeholder="Wajib untuk mengganti email">
                <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                <p class="text-muted mt-1 mb-0" style="font-size:11px">Kode verifikasi dikirim ke email baru. Email lama tetap dipakai sampai kode dimasukkan, dan alamat lama akan diberi tahu.</p>
              </div>
              <script <?php echo 'nonce="' . e(app(\App\Support\CspNonce::class)->value()) . '"'; ?>>
                (function () {
                  var input = document.getElementById('profileEmail');
                  var box = document.getElementById('emailPasswordBox');
                  var original = <?php echo json_encode($client->email, 15, 512) ?>;
                  if (!input || !box) return;
                  input.addEventListener('input', function () {
                    box.classList.toggle('d-none', input.value.trim().toLowerCase() === original.toLowerCase());
                  });
                })();
              </script>
            <?php endif; ?>
          </div>

          <div>
            <label class="form-label">No. WhatsApp / Telepon</label>
            <input type="text" name="phone" value="<?php echo e(old('phone', $client->phone)); ?>" required class="form-control" inputmode="tel" placeholder="081234567890 atau +60123456789">
            <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>

          <div>
            <label class="form-label">Perusahaan</label>
            <input type="text" name="company" value="<?php echo e(old('company', $client->company)); ?>" class="form-control">
          </div>

          <div>
            <label class="form-label">Alamat</label>
            <input type="text" name="address" value="<?php echo e(old('address', $client->address)); ?>" class="form-control">
          </div>

          <div class="row g-3">
            <div class="col-sm-6">
              <label class="form-label">Kota</label>
              <input type="text" name="city" value="<?php echo e(old('city', $client->city)); ?>" class="form-control">
            </div>
            <div class="col-sm-6">
              <label class="form-label">Negara</label>
              <select name="country" class="form-select">
                <option value="">— Pilih negara —</option>
                <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $countryName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <option value="<?php echo e($code); ?>" <?php if(old('country', $selectedCountry) === $code): echo 'selected'; endif; ?>><?php echo e($countryName); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </select>
              <?php $__errorArgs = ['country'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
          </div>

          <div class="row g-3">
            <div class="col-sm-6">
              <label class="form-label">Provinsi <span class="text-muted fw-normal">(untuk registrasi domain)</span></label>
              <input type="text" name="state" value="<?php echo e(old('state', $client->state)); ?>" placeholder="DKI Jakarta" class="form-control">
            </div>
            <div class="col-sm-6">
              <label class="form-label">Kode Pos</label>
              <input type="text" name="postal_code" value="<?php echo e(old('postal_code', $client->postal_code)); ?>" class="form-control">
            </div>
          </div>

          
          <div class="pt-3 border-top">
            <h3 class="fw-semibold text-dark mb-1" style="font-size:14px">Notifikasi</h3>
            <p class="text-muted mb-3" style="font-size:12px">
              Email tagihan, pembayaran, dan tiket selalu dikirim karena bagian dari layanan.
              Yang di bawah ini bisa Anda atur sendiri.
            </p>

            <div class="d-flex flex-column gap-3">
              <div>
                <label class="form-label">Nomor WhatsApp <span class="text-muted fw-normal">(opsional)</span></label>
                <input type="text" name="whatsapp_number" value="<?php echo e(old('whatsapp_number', $client->whatsapp_number)); ?>"
                       placeholder="081234567890 atau +60123456789" class="form-control" inputmode="tel">
                <p class="text-muted mt-1 mb-0" style="font-size:11px">Nomor luar negeri awali dengan + dan kode negara. Nomor ini juga bisa dipakai menerima kode OTP.</p>
                <?php $__errorArgs = ['whatsapp_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
              </div>

              <label class="d-flex align-items-start gap-3 rounded-3 border px-3 py-3">
                <input type="checkbox" name="notify_whatsapp" value="1" <?php if(old('notify_whatsapp', $client->notify_whatsapp)): echo 'checked'; endif; ?>
                       class="form-check-input flex-shrink-0" style="margin-top:2px">
                <span>
                  <span class="d-block fw-medium text-dark" style="font-size:14px">Terima notifikasi lewat WhatsApp</span>
                  <span class="d-block text-muted" style="font-size:12px">Tagihan dan info layanan dikirim juga ke WhatsApp. Butuh nomor di atas terisi.</span>
                </span>
              </label>

              <label class="d-flex align-items-start gap-3 rounded-3 border px-3 py-3">
                <input type="checkbox" name="notify_sms" value="1" <?php if(old('notify_sms', $client->notify_sms)): echo 'checked'; endif; ?>
                       class="form-check-input flex-shrink-0" style="margin-top:2px">
                <span>
                  <span class="d-block fw-medium text-dark" style="font-size:14px">Terima notifikasi lewat SMS</span>
                  <span class="d-block text-muted" style="font-size:12px">Cuma untuk hal mendesak (kode login, tagihan, layanan disuspend) — dikirim ke nomor telepon di atas.</span>
                </span>
              </label>

              <label class="d-flex align-items-start gap-3 rounded-3 border px-3 py-3">
                <input type="checkbox" name="notify_promo" value="1" <?php if(old('notify_promo', $client->notify_promo)): echo 'checked'; endif; ?>
                       class="form-check-input flex-shrink-0" style="margin-top:2px">
                <span>
                  <span class="d-block fw-medium text-dark" style="font-size:14px">Terima info promo dan penawaran</span>
                  <span class="d-block text-muted" style="font-size:12px">Hilangkan centang untuk berhenti menerima email promosi.</span>
                </span>
              </label>
            </div>
          </div>

          <button type="submit" class="btn btn-theme" style="width:fit-content"><i class="fa-solid fa-check" style="font-size:11px"></i> Simpan Perubahan</button>
        </form>
      </div>

      <div class="card-public p-4">
        <h2 class="small fw-bold text-dark mb-1">Notifikasi Push Browser</h2>
        <p class="text-muted mb-3" style="font-size:12px">
          Dapatkan notifikasi langsung di browser ini (tanpa perlu buka email) untuk tagihan baru, balasan tiket,
          dan info layanan lainnya — gratis, dan bisa dimatikan kapan saja.
        </p>
        <button type="button" id="pushToggleBtn" class="btn btn-outline-secondary btn-sm" disabled>Memeriksa dukungan browser…</button>
      </div>
    </div>

    <div class="col-12 col-lg-6 d-flex flex-column gap-4">
      <div class="card-public p-4">
        <h2 class="small fw-bold text-dark mb-3">Ganti Password</h2>

        <?php $pwViaOtp = $client->requiresOtpForSensitive(); ?>

        <?php if($pwViaOtp): ?>
          <p class="text-muted mb-3" style="font-size:12px;line-height:1.6">
            <?php if($client->passwordKnownToUser()): ?>
              Ganti password memakai kode verifikasi (OTP) yang dikirim ke email atau WhatsApp Anda.
            <?php else: ?>
              Akun Anda masuk lewat Google, jadi password diatur dengan kode verifikasi (OTP) yang dikirim ke email atau WhatsApp Anda.
            <?php endif; ?>
          </p>
          <?php echo $__env->make('client.profile._otp', ['purpose' => 'password_change', 'pending' => $otpPending['password_change'], 'channels' => $otpChannels], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('client.profile.password')); ?>" class="d-flex flex-column gap-3">
          <?php echo csrf_field(); ?>

          <?php if($pwViaOtp): ?>
            <div>
              <label class="form-label">Kode Verifikasi</label>
              <input type="text" name="otp" inputmode="numeric" autocomplete="one-time-code" maxlength="6" placeholder="6 digit" required class="form-control" style="max-width:12rem">
              <?php $__errorArgs = ['otp'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
          <?php else: ?>
            <div>
              <label class="form-label">Password Saat Ini</label>
              <input type="password" name="current_password" required class="form-control">
              <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
          <?php endif; ?>

          <div>
            <label class="form-label">Password Baru</label>
            <div class="d-flex gap-2">
              <input type="password" name="password" id="pwField" required class="form-control">
              <button type="button" data-action="call" data-call="lumoraGeneratePassword" data-args='["pwField","pwConfirmField","pwChecklist"]' class="btn btn-outline-secondary text-nowrap flex-shrink-0">
                <i class="fa-solid fa-dice" style="font-size:11px"></i> Buatkan Otomatis
              </button>
            </div>
            <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            <ul id="pwChecklist" class="text-muted mt-2 mb-0 ps-0" style="font-size:11px;list-style:none"></ul>
          </div>

          <div>
            <label class="form-label">Ulangi Password Baru</label>
            <input type="password" name="password_confirmation" id="pwConfirmField" required class="form-control">
          </div>

          <button type="submit" class="btn btn-theme" style="width:fit-content"><i class="fa-solid fa-key" style="font-size:11px"></i> Ganti Password</button>
          <p class="text-muted mb-0" style="font-size:11px">Setelah diganti, perangkat lain otomatis keluar dan Anda diberi tahu lewat email.</p>
        </form>

        <?php if($client->passwordKnownToUser()): ?>
          <div class="pt-3 mt-3 border-top">
            <div class="d-flex align-items-center gap-2 mb-1">
              <h3 class="fw-semibold text-dark mb-0" style="font-size:14px">Ganti password pakai kode OTP</h3>
              <span class="badge <?php echo e($client->password_otp_enabled ? 'badge-soft-success' : 'badge-soft-secondary'); ?>"><?php echo e($client->password_otp_enabled ? 'Aktif' : 'Nonaktif'); ?></span>
            </div>
            <p class="text-muted mb-2" style="font-size:12px;line-height:1.6">
              Saat aktif, mengganti password harus memasukkan kode yang dikirim ke email atau WhatsApp Anda, bukan password lama.
            </p>
            <form method="POST" action="<?php echo e(route('client.profile.password-otp')); ?>" class="d-flex flex-column gap-2" style="max-width:20rem">
              <?php echo csrf_field(); ?>
              <?php if($client->password_otp_enabled): ?>
                <input type="password" name="current_password" class="form-control form-control-sm" placeholder="Password untuk menonaktifkan" required>
                <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                <button type="submit" class="btn btn-outline-danger btn-sm" style="width:fit-content">Nonaktifkan</button>
              <?php else: ?>
                <button type="submit" class="btn btn-theme btn-sm" style="width:fit-content">Aktifkan</button>
              <?php endif; ?>
            </form>
          </div>
        <?php endif; ?>
      </div>

      
      <div class="card-public p-4">
        <div class="d-flex align-items-center gap-2 mb-1">
          <h2 class="small fw-bold text-dark mb-0">Verifikasi Dua Langkah (2FA)</h2>
          <span class="badge <?php echo e($client->two_factor_enabled ? 'badge-soft-success' : 'badge-soft-secondary'); ?>">
            <?php echo e($client->two_factor_enabled ? 'Aktif' : 'Nonaktif'); ?>

          </span>
        </div>
        <p class="text-muted mb-3" style="font-size:14px;line-height:1.6">
          Saat aktif, setiap login akan meminta kode 6 digit yang dikirim ke email
          <b class="text-dark"><?php echo e($client->email); ?></b>. Ini melindungi akun Anda meski password bocor.
        </p>

        <?php if($client->two_factor_enabled && $client->requiresOtpForSensitive()): ?>
          <p class="text-muted mb-2" style="font-size:12px">Untuk menonaktifkan 2FA, minta kode verifikasi dulu lalu masukkan di bawah.</p>
          <?php echo $__env->make('client.profile._otp', ['purpose' => 'two_factor_disable', 'pending' => $otpPending['two_factor_disable'], 'channels' => $otpChannels], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('client.profile.two-factor')); ?>" class="d-flex flex-column gap-2" style="max-width:20rem">
          <?php echo csrf_field(); ?>
          <?php if($client->two_factor_enabled): ?>
            <?php if($client->requiresOtpForSensitive()): ?>
              <input type="text" name="otp" inputmode="numeric" autocomplete="one-time-code" maxlength="6" class="form-control form-control-sm" placeholder="Kode verifikasi (OTP)" required>
              <?php $__errorArgs = ['otp'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            <?php else: ?>
              <input type="password" name="current_password" class="form-control form-control-sm" placeholder="Password untuk menonaktifkan" required>
              <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            <?php endif; ?>
            <button type="submit" class="btn btn-outline-danger btn-sm"><i class="fa-solid fa-shield-halved" style="font-size:11px"></i> Nonaktifkan 2FA</button>
          <?php else: ?>
            <button type="submit" class="btn btn-theme btn-sm"><i class="fa-solid fa-shield-halved" style="font-size:11px"></i> Aktifkan 2FA</button>
          <?php endif; ?>
        </form>
      </div>

      <div class="card-public p-4">
        <h2 class="small fw-bold text-dark mb-3">Aktivitas Login Terakhir</h2>
        <div class="row g-3">
          <div class="col-6">
            <p class="text-muted mb-0" style="font-size:11px">Waktu</p>
            <p class="fw-medium text-dark mb-0" style="font-size:14px"><?php echo e($client->last_login_at?->format('d M Y H:i') ?? '—'); ?></p>
          </div>
          <div class="col-6">
            <p class="text-muted mb-0" style="font-size:11px">Alamat IP</p>
            <p class="fw-medium text-dark mb-0" style="font-size:14px"><?php echo e($client->last_login_ip ?? '—'); ?></p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="<?php echo e(asset('assets/js/push-notifications.js')); ?>"></script>
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

    if (window.LumoraPush) {
      window.LumoraPush.attachToggle(document.getElementById('pushToggleBtn'), {
        vapidKey: '<?php echo e(route('client.push.vapid-key')); ?>',
        subscribe: '<?php echo e(route('client.push.subscribe')); ?>',
        unsubscribe: '<?php echo e(route('client.push.unsubscribe')); ?>',
      });
    }
  });
  </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('client.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/themes/client-themes/namahost/client/profile/edit.blade.php ENDPATH**/ ?>