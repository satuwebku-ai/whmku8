<?php $__env->startSection('title', 'cPanel Aplikasi Ini'); ?>

<?php $__env->startSection('content'); ?>

  <?php echo $__env->make('admin.settings._nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

  <div class="mb-4">
    <h1 class="h4 fw-bold text-dark mb-1">cPanel Aplikasi Ini</h1>
    <p class="small text-muted mb-0">
      Akses cepat ke cPanel server tempat Lumora sendiri berjalan — untuk cek file, log, database, dst.
      Server ini akun cPanel biasa (bukan WHM), jadi login tetap manual — tapi kredensial &amp; tautan
      cepatnya disimpan di sini supaya tidak perlu dicari-cari lagi tiap kali.
    </p>
  </div>

  <?php
    $url = \App\Models\Setting::get('self_cpanel_url');
    $username = \App\Models\Setting::get('self_cpanel_username');
    $password = \App\Models\Setting::get('self_cpanel_password');
    $sudahDiisi = filled($url) && filled($username);
  ?>

  <div class="row g-4">
    <div class="col-12 col-lg-6">
      <div class="card border rounded-4 p-4">
        <h2 class="small fw-bold text-dark mb-3">Kredensial</h2>
        <form method="POST" action="<?php echo e(route('admin.self-cpanel.update')); ?>">
          <?php echo csrf_field(); ?>
          <div class="mb-3">
            <label class="form-label small fw-medium text-dark">URL Login cPanel</label>
            <input type="url" name="self_cpanel_url" value="<?php echo e(old('self_cpanel_url', $url)); ?>"
                   class="form-control form-control-sm" placeholder="https://beragam.kreasi.org:2083">
            <?php $__errorArgs = ['self_cpanel_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>
          <div class="mb-3">
            <label class="form-label small fw-medium text-dark">Username</label>
            <input type="text" name="self_cpanel_username" value="<?php echo e(old('self_cpanel_username', $username)); ?>"
                   class="form-control form-control-sm" placeholder="satuclou">
            <?php $__errorArgs = ['self_cpanel_username'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>
          <div class="mb-3">
            <label class="form-label small fw-medium text-dark">Password <?php echo e($password ? '(sudah tersimpan, kosongkan jika tidak diganti)' : ''); ?></label>
            <input type="password" name="self_cpanel_password" class="form-control form-control-sm"
                   placeholder="<?php echo e($password ? '••••••••••••' : ''); ?>">
            <p class="text-muted mt-1 mb-0" style="font-size:11px">Disimpan terenkripsi di database, sama seperti kredensial server lain.</p>
          </div>
          <button type="submit" class="btn btn-primary btn-sm" style="width:fit-content">Simpan</button>
        </form>
      </div>

      <?php if($sudahDiisi): ?>
        <div class="card border rounded-4 p-4 mt-4">
          <h2 class="small fw-bold text-dark mb-3">Login</h2>
          <div class="d-flex align-items-center justify-content-between rounded-3 border px-3 py-2 mb-2">
            <div class="min-w-0">
              <p class="text-muted mb-0" style="font-size:10px">Username</p>
              <p class="fw-medium text-dark mb-0" style="font-family:monospace;font-size:13px"><?php echo e($username); ?></p>
            </div>
            <button type="button" data-action="call" data-call="salinNamaUser" data-pass-el class="btn btn-outline-secondary btn-sm">Salin</button>
          </div>

          <?php if($password): ?>
            <div class="d-flex align-items-center justify-content-between rounded-3 border px-3 py-2 mb-3">
              <div class="min-w-0">
                <p class="text-muted mb-0" style="font-size:10px">Password</p>
                <p class="fw-medium text-dark mb-0" style="font-family:monospace;font-size:13px" id="pwText">••••••••</p>
              </div>
              <div class="d-flex gap-1">
                <button type="button" data-action="call" data-call="tampilkanPassword" class="btn btn-outline-secondary btn-sm" id="pwToggleBtn">Lihat</button>
                <button type="button" data-action="call" data-call="salinPassword" data-pass-el class="btn btn-outline-secondary btn-sm">Salin</button>
              </div>
            </div>
          <?php endif; ?>

          <a href="<?php echo e($url); ?>" target="_blank" rel="noopener" class="btn btn-theme w-100">
            <i class="fa-solid fa-arrow-up-right-from-square" style="font-size:11px"></i> Buka Halaman Login cPanel
          </a>
          <p class="text-muted mt-2 mb-0" style="font-size:11px">
            Login manual sekali di tab yang terbuka — setelah itu tautan Akses Cepat di samping bisa
            dipakai berulang tanpa diminta login lagi (selama sesi browser masih aktif).
          </p>
        </div>
      <?php endif; ?>
    </div>

    <div class="col-12 col-lg-6">
      <div class="card border rounded-4 p-4">
        <h2 class="small fw-bold text-dark mb-1">Akses Cepat</h2>
        <?php if(! $sudahDiisi): ?>
          <p class="text-muted mb-0" style="font-size:13px">Isi URL &amp; username di samping dulu untuk mengaktifkan tautan cepat ini.</p>
        <?php else: ?>
          <p class="text-muted mb-3" style="font-size:11px">
            Login manual dulu lewat tombol di samping, baru tautan-tautan ini langsung menuju halamannya.
          </p>
          <div class="row g-2">
            <?php $__currentLoopData = $shortcuts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <div class="col-4 col-sm-3">
                <a href="<?php echo e(rtrim($url, '/')); ?>/<?php echo e($sc['path']); ?>" target="_blank" rel="noopener"
                   class="d-flex flex-column align-items-center gap-2 p-2 rounded-3 border text-decoration-none text-center h-100">
                  <i class="fa-solid <?php echo e($sc['icon']); ?> text-muted" style="font-size:16px"></i>
                  <span class="text-muted" style="font-size:10px;line-height:1.2"><?php echo e($sc['label']); ?></span>
                </a>
              </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <script <?php echo 'nonce="' . e(app(\App\Support\CspNonce::class)->value()) . '"'; ?>>
    // Nilai password disisipkan SEKALI di sini lewat direktif json Blade
    // (aman dari karakter kutip/spesial apa pun), lalu dipakai ulang
    // kedua tombol -- bukan disisipkan langsung ke atribut onclick,
    // yang berisiko rusak kalau passwordnya mengandung tanda kutip.
    const RAHASIA_CPANEL = <?php echo json_encode($password, 15, 512) ?>;
    const NAMA_USER_CPANEL = <?php echo json_encode($username, 15, 512) ?>;

    function salinTeks(teks, btn) {
      navigator.clipboard.writeText(teks);
      const asli = btn.textContent;
      btn.textContent = 'Tersalin!';
      setTimeout(() => { btn.textContent = asli; }, 1200);
    }

    function salinPassword(btn) {
      salinTeks(RAHASIA_CPANEL, btn);
    }

    function tampilkanPassword() {
      const el = document.getElementById('pwText');
      const btn = document.getElementById('pwToggleBtn');
      const tersembunyi = el.textContent === '••••••••';
      el.textContent = tersembunyi ? RAHASIA_CPANEL : '••••••••';
      btn.textContent = tersembunyi ? 'Sembunyikan' : 'Lihat';
    }
    (window.LumoraActions = window.LumoraActions || {}).tampilkanPassword = tampilkanPassword;
    (window.LumoraActions = window.LumoraActions || {}).salinPassword = salinPassword;
    (window.LumoraActions = window.LumoraActions || {}).salinNamaUser = function (btn) { salinTeks(NAMA_USER_CPANEL, btn); };
  </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/admin/settings/self-cpanel.blade.php ENDPATH**/ ?>