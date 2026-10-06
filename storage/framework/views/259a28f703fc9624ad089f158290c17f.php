<?php $__env->startSection('title', $admin->exists ? 'Edit Admin' : 'Tambah Admin'); ?>

<?php $__env->startSection('content'); ?>

  <div class="mb-4">
    <a href="<?php echo e(route('admin.admins')); ?>" class="text-decoration-none text-muted" style="font-size:12px"><i class="fa-solid fa-arrow-left"></i> Kembali ke Manajemen Admin</a>
    <h1 class="h4 fw-bold text-dark mt-1 mb-0"><?php echo e($admin->exists ? 'Edit Admin' : 'Tambah Admin'); ?></h1>
  </div>

  <form method="POST" action="<?php echo e($admin->exists ? route('admin.admin.update', $admin) : route('admin.admin.add')); ?>" class="card border rounded-4 p-4" style="max-width:42rem">
    <?php echo csrf_field(); ?>

    <div class="row g-3 mb-3">
      <div class="col-sm-6">
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
      <div class="col-sm-6">
        <label class="form-label small fw-medium text-dark">Username</label>
        <input type="text" name="username" value="<?php echo e(old('username', $admin->username)); ?>" class="form-control form-control-sm" required>
        <?php $__errorArgs = ['username'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        <p class="text-muted mt-1 mb-0" style="font-size:11px">Dipakai untuk login. Huruf, angka, dan tanda hubung.</p>
      </div>
    </div>

    <div class="mb-3">
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
      <p class="text-muted mt-1 mb-0" style="font-size:11px">Tujuan kode OTP dan notifikasi admin.</p>
    </div>

    <div class="mb-3">
      <label class="form-label small fw-medium text-dark">Peran</label>
      <select name="role" id="roleSelect" class="form-select" style="padding:.25rem .6rem;font-size:.875rem;border-radius:.375rem">
        <optgroup label="Akses penuh & operasional">
          <?php $__currentLoopData = ['superadmin', 'administrator']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($key); ?>" <?php if(old('role', $admin->role ?? 'administrator') === $key): echo 'selected'; endif; ?>><?php echo e(\App\Models\Admin::ROLES[$key]); ?></option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </optgroup>
        <optgroup label="Keuangan & layanan">
          <?php $__currentLoopData = ['finance', 'billing', 'domain_manager', 'hosting_manager', 'support']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($key); ?>" <?php if(old('role', $admin->role ?? 'administrator') === $key): echo 'selected'; endif; ?>><?php echo e(\App\Models\Admin::ROLES[$key]); ?></option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </optgroup>
        <optgroup label="Konten & teknis">
          <?php $__currentLoopData = ['marketing', 'developer', 'devops', 'auditor', 'viewer']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($key); ?>" <?php if(old('role', $admin->role ?? 'administrator') === $key): echo 'selected'; endif; ?>><?php echo e(\App\Models\Admin::ROLES[$key]); ?></option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </optgroup>
        <?php if($admin->exists && in_array($admin->role, ['admin', 'staff'], true)): ?>
          <optgroup label="Kompatibilitas akun lama">
            <option value="<?php echo e($admin->role); ?>" selected><?php echo e(\App\Models\Admin::ROLES[$admin->role]); ?></option>
          </optgroup>
        <?php endif; ?>
      </select>
      <?php $__errorArgs = ['role'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      <div id="roleHint" class="rounded-3 px-3 py-2 mt-2" style="font-size:11px;background:#f8fafc;color:#64748b"></div>
    </div>

    <div id="modulesSection" class="mb-3 pt-3 border-top">
      <label class="form-label small fw-medium text-dark mb-1">Modul yang Boleh Diakses</label>
      <p class="text-muted mb-2" style="font-size:11px">
        Superadmin bisa atur manual siapa saja yang boleh masuk ke modul apa. Memilih peran di atas cuma
        mengisi centang bawaan — bebas diubah sendiri per akun. Kalau tidak ada yang dicentang, akun ini
        terkunci total dari semua modul (tetap bisa login &amp; lihat profil sendiri).
      </p>
      <input type="hidden" name="permissions_submitted" value="1">
      <div class="row g-2">
        <?php
          $currentModules = old('permissions', $admin->exists ? $admin->effectiveModules() : (\App\Models\Admin::ROLE_DEFAULT_MODULES['administrator'] ?? []));
        ?>
        <?php $__currentLoopData = \App\Models\Admin::MODULES; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
           <div class="col-sm-6">
             <label class="d-flex align-items-start gap-2 small text-dark border rounded-3 px-3 py-2 h-100" style="cursor:pointer">
              <input type="checkbox" name="permissions[]" value="<?php echo e($key); ?>" class="form-check-input module-checkbox" style="margin-top:2px"
                     <?php if(in_array($key, $currentModules, true)): echo 'checked'; endif; ?>>
               <span><strong class="d-block"><?php echo e(\Illuminate\Support\Str::before($label, ' — ')); ?></strong><span class="text-muted" style="font-size:10px"><?php echo e(\Illuminate\Support\Str::after($label, ' — ')); ?></span></span>
            </label>
          </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </div>
      <?php $__errorArgs = ['permissions'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    <div id="superadminNote" class="mb-3 pt-3 border-top d-none">
      <div class="rounded-3 border px-3 py-2" style="font-size:12px;background:#f8fafc;color:#64748b">
        <i class="fa-solid fa-circle-info"></i> Superadmin selalu punya akses ke semua modul — centang di atas diabaikan untuk peran ini.
      </div>
    </div>

    <div class="row g-3 mb-3 pt-3 border-top">
      <div class="col-sm-6">
        <label class="form-label small fw-medium text-dark">Password <?php echo e($admin->exists ? '(kosongkan jika tidak diganti)' : ''); ?></label>
        <div class="d-flex gap-2">
          <input type="password" name="password" id="pwField" class="form-control form-control-sm" <?php echo e($admin->exists ? '' : 'required'); ?> autocomplete="new-password">
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
      <div class="col-sm-6">
        <label class="form-label small fw-medium text-dark">Ulangi Password</label>
        <input type="password" name="password_confirmation" id="pwConfirmField" class="form-control form-control-sm" <?php echo e($admin->exists ? '' : 'required'); ?> autocomplete="new-password">
      </div>
    </div>

    <?php if(! $admin->exists || $admin->id !== auth('admin')->id()): ?>
      <label class="d-flex align-items-center gap-2 small text-dark mb-3">
        <input type="checkbox" name="is_active" value="1" <?php if(old('is_active', $admin->is_active ?? true)): echo 'checked'; endif; ?> class="form-check-input" style="margin-top:0">
        Akun aktif (bisa login)
      </label>
    <?php else: ?>
      <div class="rounded-3 border px-3 py-2 mb-3" style="font-size:12px;background:#f8fafc;color:#64748b">
        <i class="fa-solid fa-circle-info"></i>
        Status akun sendiri tidak bisa diubah dari sini — supaya Anda tidak mengunci diri sendiri.
      </div>
    <?php endif; ?>

    <div class="d-flex align-items-center gap-2 pt-2">
      <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-check" style="font-size:11px"></i> Simpan</button>
      <a href="<?php echo e(route('admin.admins')); ?>" class="btn btn-outline-secondary btn-sm">Batal</a>
    </div>
  </form>

  <script <?php echo 'nonce="' . e(app(\App\Support\CspNonce::class)->value()) . '"'; ?>>
    /**
     * Dipakai bersama di beberapa form (Admin & Akses, Profil, Hosting
     * Account) -- satu-satunya tempat admin BENAR-BENAR membuat password
     * baru (bukan menempel kredensial API pihak ketiga).
     */
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

      // ── Checklist modul: isi ulang centang bawaan saat peran diganti,
      // dan sembunyikan checklist untuk superadmin (selalu akses penuh).
      const roleDefaults = <?php echo json_encode(\App\Models\Admin::ROLE_DEFAULT_MODULES, 15, 512) ?>;
      const roleLabels = <?php echo json_encode(\App\Models\Admin::ROLES, 15, 512) ?>;
      const roleSelect = document.getElementById('roleSelect');
      const modulesSection = document.getElementById('modulesSection');
      const superadminNote = document.getElementById('superadminNote');
      const roleHint = document.getElementById('roleHint');
      const isEditingExisting = <?php echo e($admin->exists ? 'true' : 'false'); ?>;

      function applyRoleDefaults() {
        const role = roleSelect.value;

        if (role === 'superadmin') {
          modulesSection.classList.add('d-none');
          superadminNote.classList.remove('d-none');
          roleHint.innerHTML = '<i class="fa-solid fa-shield-halved text-success me-1"></i> Akses penuh. Modul di bawah tidak perlu diatur manual.';
          return;
        }

        modulesSection.classList.remove('d-none');
        superadminNote.classList.add('d-none');
        const label = roleLabels[role] || '';
        const desc = label.includes('—') ? label.split('—').slice(1).join('—').trim() : label;
        const count = (roleDefaults[role] || []).length;
        roleHint.innerHTML = '<i class="fa-solid fa-lock-open text-accent me-1"></i> Bawaan peran ini membuka <b>' + count + ' modul</b>. ' + desc + '. Hak akses tetap bisa disesuaikan per akun.';
      }

      if (roleSelect) {
        applyRoleDefaults();

        roleSelect.addEventListener('change', () => {
          applyRoleDefaults();

          // Cuma auto-isi ulang centang kalau ini form TAMBAH admin baru
          // (belum ada data tersimpan) -- di form EDIT, jangan timpa
          // pilihan manual superadmin cuma karena ganti dropdown peran.
          if (isEditingExisting) return;

          const defaults = roleDefaults[roleSelect.value] || [];
          document.querySelectorAll('.module-checkbox').forEach((cb) => {
            cb.checked = defaults.includes(cb.value);
          });
        });
      }
    });
  </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/admin/admins/form.blade.php ENDPATH**/ ?>