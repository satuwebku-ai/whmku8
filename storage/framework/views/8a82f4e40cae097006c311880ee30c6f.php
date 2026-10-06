<?php $__env->startSection('title', 'Profil & Keamanan'); ?>

<?php $__env->startSection('content'); ?>

  <div class="mb-4">
    <h1 class="h4 fw-bold text-dark mb-1">Profil &amp; Keamanan</h1>
    <p class="small text-muted mb-0">Kelola data akun dan pengaturan keamanan login Anda.</p>
  </div>

  <div class="row g-3" style="max-width:56rem">

    
    <div class="col-12 col-lg-6">
      <div class="card border rounded-4 p-4 h-100">
        <h2 class="small fw-bold text-dark mb-3">Data Akun</h2>
        <form method="POST" action="<?php echo e(route('admin.profile.update')); ?>" class="d-flex flex-column gap-3">
          <?php echo csrf_field(); ?>
          <div>
            <label class="form-label small fw-medium text-dark">Username</label>
            <input type="text" value="<?php echo e($admin->username); ?>" class="form-control form-control-sm bg-light" disabled>
            <p class="text-muted mt-1 mb-0" style="font-size:11px">Username tidak bisa diubah sendiri. Hubungi superadmin bila perlu.</p>
          </div>
          <div>
            <label class="form-label small fw-medium text-dark">Nama Lengkap</label>
            <input type="text" name="name" value="<?php echo e(old('name', $admin->name)); ?>" class="form-control form-control-sm" required>
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
            <label class="form-label small fw-medium text-dark">Email</label>
            <input type="email" name="email" value="<?php echo e(old('email', $admin->email)); ?>" class="form-control form-control-sm" required>
            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            <p class="text-muted mt-1 mb-0" style="font-size:11px">Kode verifikasi 2FA dikirim ke alamat ini.</p>
          </div>
          <button type="submit" class="btn btn-primary btn-sm" style="width:fit-content"><i class="fa-solid fa-check" style="font-size:11px"></i> Simpan Profil</button>
        </form>
      </div>
    </div>

    
    <div class="col-12 col-lg-6">
      <div class="card border rounded-4 p-4 h-100">
        <h2 class="small fw-bold text-dark mb-3">Ganti Password</h2>
        <form method="POST" action="<?php echo e(route('admin.profile.password')); ?>" class="d-flex flex-column gap-3">
          <?php echo csrf_field(); ?>
          <div>
            <label class="form-label small fw-medium text-dark">Password Saat Ini</label>
            <input type="password" name="current_password" class="form-control form-control-sm" required>
            <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>
          <div>
            <label class="form-label small fw-medium text-dark">Password Baru</label>
            <div class="d-flex gap-2">
              <input type="password" name="password" id="pwField" class="form-control form-control-sm" required>
              <button type="button" data-action="call" data-call="lumoraGeneratePassword" data-args='["pwField","pwConfirmField","pwChecklist"]' class="btn btn-outline-secondary btn-sm text-nowrap flex-shrink-0">
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
            <label class="form-label small fw-medium text-dark">Konfirmasi Password Baru</label>
            <input type="password" name="password_confirmation" id="pwConfirmField" class="form-control form-control-sm" required>
          </div>
          <button type="submit" class="btn btn-primary btn-sm" style="width:fit-content"><i class="fa-solid fa-key" style="font-size:11px"></i> Ganti Password</button>
        </form>
      </div>
    </div>

    
    <div class="col-12">
      <div class="card border rounded-4 p-4">
        <div class="d-flex align-items-start justify-content-between gap-4 flex-wrap">
          <div class="flex-grow-1" style="min-width:280px">
            <div class="d-flex align-items-center gap-2 mb-1">
              <h2 class="small fw-bold text-dark mb-0">Verifikasi Dua Langkah (2FA)</h2>
              <span class="badge <?php echo e($admin->two_factor_enabled ? 'badge-soft-success' : 'badge-soft-secondary'); ?>">
                <?php echo e($admin->two_factor_enabled ? 'Aktif' : 'Nonaktif'); ?>

              </span>
            </div>
            <p class="text-muted mb-0" style="font-size:14px;line-height:1.6">
              Saat aktif, setiap login akan meminta kode 6 digit yang dikirim ke email
              <b class="text-dark"><?php echo e($admin->email); ?></b>. Ini melindungi akun Anda meski password bocor.
            </p>
            <p class="mt-2 mb-0" style="font-size:12px;color:#b45309">
              <i class="fa-solid fa-triangle-exclamation"></i>
              Pastikan pengaturan email server (SMTP) sudah benar sebelum mengaktifkan —
              kalau email tidak terkirim, Anda tidak akan bisa login.
            </p>
          </div>

          <form method="POST" action="<?php echo e(route('admin.profile.two-factor')); ?>" class="w-100 d-flex flex-column gap-2" style="max-width:20rem">
            <?php echo csrf_field(); ?>
            <?php if($admin->two_factor_enabled): ?>
              <input type="password" name="current_password" class="form-control form-control-sm" placeholder="Password untuk menonaktifkan" required>
              <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
              <button type="submit" class="btn btn-outline-danger btn-sm w-100"><i class="fa-solid fa-shield-halved" style="font-size:11px"></i> Nonaktifkan 2FA</button>
            <?php else: ?>
              <button type="submit" class="btn btn-primary btn-sm w-100"><i class="fa-solid fa-shield-halved" style="font-size:11px"></i> Aktifkan 2FA</button>
            <?php endif; ?>
          </form>
        </div>
      </div>
    </div>

    
    <div class="col-12">
      <div class="card border rounded-4 p-4">
        <h2 class="small fw-bold text-dark mb-3">Aktivitas Login Terakhir</h2>
        <div class="row g-3">
          <div class="col-sm-6">
            <p class="text-muted mb-0" style="font-size:11px">Waktu</p>
            <p class="fw-medium text-dark mb-0" style="font-size:14px"><?php echo e($admin->last_login_at?->format('d M Y H:i') ?? '—'); ?></p>
          </div>
          <div class="col-sm-6">
            <p class="text-muted mb-0" style="font-size:11px">Alamat IP</p>
            <p class="fw-medium text-dark mb-0" style="font-size:14px"><?php echo e($admin->last_login_ip ?? '—'); ?></p>
          </div>
        </div>
      </div>
    </div>
    
    <div class="col-12">
      <div class="card border rounded-4 p-4">
        <h2 class="small fw-bold text-dark mb-1">Notifikasi Push Browser</h2>
        <p class="text-muted mb-3" style="font-size:12px">
          Dapatkan notifikasi langsung di browser ini untuk tiket baru, pembayaran masuk, dan alert penting
          lainnya — tanpa perlu buka email atau refresh halaman terus-menerus.
        </p>
        <button type="button" id="pushToggleBtn" class="btn btn-outline-secondary btn-sm" style="width:fit-content" disabled>Memeriksa dukungan browser…</button>
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
          vapidKey: '<?php echo e(route('admin.push.vapid-key')); ?>',
          subscribe: '<?php echo e(route('admin.push.subscribe')); ?>',
          unsubscribe: '<?php echo e(route('admin.push.unsubscribe')); ?>',
        });
      }
    });
  </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/admin/profile/edit.blade.php ENDPATH**/ ?>