<?php $__env->startSection('title', $gateway->exists ? 'Edit Gateway' : 'Tambah Gateway'); ?>

<?php $__env->startSection('content'); ?>

  
  <div class="d-flex align-items-center gap-1 mb-3 border-bottom flex-wrap">
    <?php
      $topTabs = [
        ['label' => 'Transaksi', 'route' => 'admin.payments'],
        ['label' => 'Gateway', 'route' => 'admin.gateways'],
      ];
    ?>
    <?php $__currentLoopData = $topTabs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tab): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <a href="<?php echo e(route($tab['route'])); ?>"
         class="px-3 py-2 small fw-medium text-decoration-none border-bottom border-2 <?php echo e(request()->routeIs(str_replace('.bootstrap-preview', '', $tab['route']) . '*') ? 'border-primary text-accent' : 'border-transparent text-muted'); ?>">
        <?php echo e($tab['label']); ?>

      </a>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </div>

  <div class="mb-3">
    <h1 class="h4 fw-bold text-dark mb-1"><?php echo e($gateway->exists ? 'Edit Payment Gateway' : 'Tambah Payment Gateway'); ?></h1>
    <p class="small text-muted mb-0">Semua kredensial dienkripsi otomatis di database.</p>
  </div>

  <?php
    $hintStyle = 'background:#eef2ff;border:1px solid #c7d2fe;color:#4338ca';
    $hintStyleWarn = 'background:#fffbeb;border:1px solid #fde68a;color:#92400e';
  ?>

  <div id="hint-midtrans" class="driver-hint d-none rounded-3 px-3 py-2 small mb-3" style="max-width:42rem;<?php echo e($hintStyle); ?>">
    <i class="fa-solid fa-circle-info"></i>
    <b>Midtrans:</b> ambil Server Key &amp; Client Key dari Dashboard Midtrans » Settings » Access Keys.
    Daftarkan Payment Notification URL berikut di Settings » Configuration:
    <code class="px-1 rounded" style="background:rgba(255,255,255,.6)"><?php echo e(url('/payment/webhook/midtrans')); ?></code>
  </div>

  <div id="hint-xendit" class="driver-hint d-none rounded-3 px-3 py-2 small mb-3" style="max-width:42rem;<?php echo e($hintStyle); ?>">
    <i class="fa-solid fa-circle-info"></i>
    <b>Xendit:</b> isi Secret API Key di field Server Key, dan Callback Verification Token di field Callback Token
    (Dashboard Xendit » Settings » Developers). Daftarkan webhook URL:
    <code class="px-1 rounded" style="background:rgba(255,255,255,.6)"><?php echo e(url('/payment/webhook/xendit')); ?></code>
  </div>

  <div id="hint-duitku" class="driver-hint d-none rounded-3 px-3 py-2 small mb-3" style="max-width:42rem;<?php echo e($hintStyle); ?>">
    <i class="fa-solid fa-circle-info"></i>
    <b>Duitku:</b> isi Merchant Code di field Client Key, dan API Key di field Server Key
    (Dashboard Duitku » Pengaturan » Konfigurasi API). Daftarkan Callback URL berikut di sana:
    <code class="px-1 rounded" style="background:rgba(255,255,255,.6)"><?php echo e(url('/payment/webhook/duitku')); ?></code>
    <br>Mode Sandbox memakai kredensial project uji coba Duitku — biasanya perlu didaftarkan terpisah dari akun production.
    <br><br>
    <b>Kode Metode QRIS (opsional):</b> isi kolom "Kode Metode QRIS" di bawah kalau ingin kode QR
    tampil langsung di halaman invoice (tanpa klien diarahkan ke situs Duitku). Kodenya berbeda tiap akun —
    lihat di Dashboard Duitku » Metode Pembayaran, cari baris QRIS dan salin kodenya persis.
    Dikosongkan = QRIS tetap bisa dipakai lewat halaman Duitku biasa (redirect), fitur tertanam saja yang nonaktif.
  </div>

  <div id="hint-manual" class="driver-hint d-none rounded-3 px-3 py-2 small mb-3" style="max-width:42rem;<?php echo e($hintStyleWarn); ?>">
    <i class="fa-solid fa-circle-info"></i>
    <b>Transfer Manual:</b> tidak memanggil API apapun. Isi instruksi transfer (nomor rekening, nama penerima)
    di kolom Instruksi. Pembayaran diverifikasi admin lewat tombol Setujui di halaman detail.
  </div>

  <form method="POST" action="<?php echo e($gateway->exists ? route('admin.gateway.update', $gateway) : route('admin.gateway.add')); ?>" class="card border rounded-4 p-4" style="max-width:42rem">
    <?php echo csrf_field(); ?>

    <div class="row g-3 mb-3">
      <div class="col-sm-6">
        <label class="form-label small fw-medium text-dark">Nama (dilihat klien)</label>
        <input type="text" name="name" value="<?php echo e(old('name', $gateway->name)); ?>" placeholder="Transfer Bank BCA" class="form-control form-control-sm" required>
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
        <label class="form-label small fw-medium text-dark">Driver</label>
        <select name="driver" id="driverSelect" class="form-select" style="padding:.25rem .6rem;font-size:.875rem;border-radius:.375rem">
          <?php $__currentLoopData = $drivers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($key); ?>" <?php if(old('driver', $gateway->driver ?? 'midtrans') === $key): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
      </div>
    </div>

    <div id="fieldsAuto">
      <div id="fieldMode" class="mb-3">
        <label class="form-label small fw-medium text-dark">Mode</label>
        <select name="mode" class="form-select" style="padding:.25rem .6rem;font-size:.875rem;border-radius:.375rem">
          <option value="sandbox" <?php if(old('mode', $gateway->mode ?? 'sandbox') === 'sandbox'): echo 'selected'; endif; ?>>Sandbox (testing)</option>
          <option value="production" <?php if(old('mode', $gateway->mode) === 'production'): echo 'selected'; endif; ?>>Production</option>
        </select>
      </div>

      <div class="row g-3 mb-3">
        <div class="col-sm-6">
          <label class="form-label small fw-medium text-dark"><span id="labelServerKey">Server Key</span> <?php echo e($gateway->exists ? '(kosongkan jika tidak diganti)' : ''); ?></label>
          <input type="password" name="server_key" placeholder="<?php echo e($gateway->exists ? '••••••••••••' : ''); ?>" class="form-control form-control-sm">
          <?php $__errorArgs = ['server_key'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        <div id="fieldClientKey" class="col-sm-6">
          <label class="form-label small fw-medium text-dark"><span id="labelClient">Client Key</span> <?php echo e($gateway->exists ? '(opsional)' : ''); ?></label>
          <input type="password" name="client_key" placeholder="<?php echo e($gateway->exists ? '••••••••••••' : ''); ?>" class="form-control form-control-sm">
        </div>
      </div>

      <div id="fieldCallbackToken" class="mb-3">
        <label class="form-label small fw-medium text-dark">Callback Verification Token <?php echo e($gateway->exists ? '(kosongkan jika tidak diganti)' : ''); ?></label>
        <input type="password" name="callback_token" placeholder="<?php echo e($gateway->exists ? '••••••••••••' : ''); ?>" class="form-control form-control-sm">
        <p class="text-muted mt-1 mb-0" style="font-size:11px">Wajib untuk Xendit — dipakai memverifikasi keaslian webhook.</p>
      </div>

      <div id="fieldQrisCode" class="d-none mb-3">
        <label class="form-label small fw-medium text-dark">Kode Metode QRIS <span class="text-muted fw-normal">(opsional)</span></label>
        <input type="text" name="qris_method_code" value="<?php echo e(old('qris_method_code', $gateway->qris_method_code)); ?>"
               placeholder="mis. SP — lihat Dashboard Duitku » Metode Pembayaran" class="form-control form-control-sm">
        <?php $__errorArgs = ['qris_method_code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        <p class="text-muted mt-1 mb-0" style="font-size:11px">
          Isi supaya kode QR tampil langsung di halaman invoice. Kosongkan kalau tidak yakin —
          QRIS tetap berfungsi lewat halaman Duitku biasa.
        </p>
      </div>
    </div>

    <div id="fieldInstructions" class="d-none mb-3">
      <label class="form-label small fw-medium text-dark">Instruksi Transfer</label>
      <textarea name="instructions" rows="4" class="form-control form-control-sm" placeholder="Bank BCA&#10;No. Rek: 1234567890&#10;a/n PT Contoh Hosting"><?php echo e(old('instructions', $gateway->instructions)); ?></textarea>
    </div>

    <div class="row g-3 mb-3">
      <div class="col-sm-4">
        <label class="form-label small fw-medium text-dark">Biaya Tetap (Rp)</label>
        <input type="number" step="0.01" name="fee_flat" value="<?php echo e(old('fee_flat', $gateway->fee_flat ?? 0)); ?>" class="form-control form-control-sm">
      </div>
      <div class="col-sm-4">
        <label class="form-label small fw-medium text-dark">Biaya Persentase (%)</label>
        <input type="number" step="0.01" name="fee_percent" value="<?php echo e(old('fee_percent', $gateway->fee_percent ?? 0)); ?>" class="form-control form-control-sm">
      </div>
      <div class="col-sm-4">
        <label class="form-label small fw-medium text-dark">Mata Uang</label>
        <input type="text" name="currency" maxlength="3" value="<?php echo e(old('currency', $gateway->currency ?? 'IDR')); ?>" class="form-control form-control-sm" required>
      </div>
    </div>

    <div class="row g-3 mb-3 align-items-center">
      <div class="col-sm-6">
        <label class="form-label small fw-medium text-dark">Urutan Tampil</label>
        <input type="number" name="sort_order" value="<?php echo e(old('sort_order', $gateway->sort_order ?? 0)); ?>" class="form-control form-control-sm">
      </div>
      <div class="col-sm-6">
        <label class="d-flex align-items-center gap-2 small text-dark mb-0">
          <input type="checkbox" name="is_active" value="1" <?php if(old('is_active', $gateway->is_active ?? true)): echo 'checked'; endif; ?> class="form-check-input" style="margin-top:0">
          Aktif
        </label>
      </div>
    </div>

    <div class="d-flex align-items-center gap-2 pt-2 border-top">
      <button type="submit" class="btn btn-primary btn-sm mt-2"><i class="fa-solid fa-check" style="font-size:11px"></i> Simpan</button>
      <a href="<?php echo e(route('admin.gateways')); ?>" class="btn btn-outline-secondary btn-sm mt-2">Batal</a>
    </div>
  </form>

  <script <?php echo 'nonce="' . e(app(\App\Support\CspNonce::class)->value()) . '"'; ?>>
    (function () {
      const select        = document.getElementById('driverSelect');
      const fieldsAuto    = document.getElementById('fieldsAuto');
      const fieldClient   = document.getElementById('fieldClientKey');
      const fieldToken    = document.getElementById('fieldCallbackToken');
      const fieldQris     = document.getElementById('fieldQrisCode');
      const fieldInstr    = document.getElementById('fieldInstructions');
      const labelServer   = document.getElementById('labelServerKey');
      const labelClient   = document.getElementById('labelClient');

      function sync() {
        const driver = select.value;
        const isManual = driver === 'manual';

        // Gateway manual tidak butuh kredensial API sama sekali.
        fieldsAuto.classList.toggle('d-none', isManual);
        fieldInstr.classList.toggle('d-none', !isManual);

        // Client Key dipakai Midtrans (Client Key) & Duitku (Merchant Code).
        // Callback Token hanya dipakai Xendit — Duitku memverifikasi lewat
        // signature MD5 yang dihitung dari Merchant Code + API Key, bukan
        // token terpisah.
        fieldClient.classList.toggle('d-none', ! ['midtrans', 'duitku'].includes(driver));
        fieldToken.classList.toggle('d-none', driver !== 'xendit');
        fieldQris.classList.toggle('d-none', driver !== 'duitku');

        labelServer.textContent = driver === 'xendit' ? 'Secret API Key' : (driver === 'duitku' ? 'API Key' : 'Server Key');
        labelClient.textContent = driver === 'duitku' ? 'Merchant Code' : 'Client Key';

        document.querySelectorAll('.driver-hint').forEach(el => el.classList.add('d-none'));
        document.getElementById('hint-' + driver)?.classList.remove('d-none');
      }

      select.addEventListener('change', sync);
      sync();
    })();
  </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/admin/gateways/form.blade.php ENDPATH**/ ?>