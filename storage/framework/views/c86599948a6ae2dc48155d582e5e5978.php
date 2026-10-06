<?php $__env->startSection('title', $registrar->exists ? 'Edit Registrar' : 'Tambah Registrar'); ?>

<?php $__env->startSection('content'); ?>

  <?php echo $__env->make('admin.domains._nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

  <div class="mb-4">
    <h1 class="h4 fw-bold text-dark mb-1"><?php echo e($registrar->exists ? 'Edit Registrar' : 'Tambah Registrar'); ?></h1>
    <p class="small text-muted mb-0">API Key dienkripsi otomatis di database.</p>
  </div>

  
  <div id="hint-namecheap" class="provider-hint rounded-3 px-3 py-2 mb-3" style="max-width:48rem;background:#eef2ff;border:1px solid #c7d2fe;font-size:12px;color:#4338ca">
    <i class="fa-solid fa-circle-info"></i>
    <b>Namecheap:</b> aktifkan API Access di <b>Profile » Tools » Namecheap API Access</b>,
    lalu whitelist IP server kamu di halaman yang sama. IP itu yang diisi di field <b>Client IP</b>.
  </div>

  <div id="hint-liquid" class="provider-hint d-none rounded-3 px-3 py-2 mb-3" style="max-width:48rem;background:#eef2ff;border:1px solid #c7d2fe;font-size:12px;color:#4338ca">
    <i class="fa-solid fa-circle-info"></i>
    <b>Liqu.id:</b> isi <b>Reseller ID</b> dan <b>API Key</b> dari reseller control panel
    (Autentikasi memakai HTTP Basic Auth). API URL boleh dikosongkan — otomatis memakai
    <code>api.liqu.id/v1</code> (produksi) atau <code>api.domainsas.com/v1</code> (sandbox)
    sesuai centang Mode Sandbox. Rate limit ±100 request / 15 menit.
  </div>

  <div id="hint-resellbiz" class="provider-hint d-none rounded-3 px-3 py-2 mb-3" style="max-width:48rem;background:#fffbeb;border:1px solid #fde68a;font-size:12px;color:#92400e">
    <i class="fa-solid fa-triangle-exclamation"></i>
    <b>ResellBiz / UK2Group:</b> integrasi belum diimplementasikan. Struktur sudah
    disiapkan, tinggal diisi begitu dokumentasi API tersedia.
  </div>

  <div id="hint-dnama" class="provider-hint d-none rounded-3 px-3 py-2 mb-3" style="max-width:48rem;background:#eef2ff;border:1px solid #c7d2fe;font-size:12px;color:#4338ca">
    <i class="fa-solid fa-circle-info"></i>
    <b>DNAMA (Daftar Nama):</b> isi <b>API Key</b> dari dashboard reseller DNAMA-mu
    (Autentikasi memakai header <code>X-API-Key</code>). API URL boleh dikosongkan — otomatis memakai
    <code>api.dnama.id</code>. Mendukung domain <b>.id</b> dan turunannya, plus manajemen DNS &amp; DNSSEC langsung lewat API.
  </div>

  <form method="POST" action="<?php echo e($registrar->exists ? route('admin.registrars.update', $registrar) : route('admin.registrars.store')); ?>" class="card border rounded-4 p-4" style="max-width:48rem">
    <?php echo csrf_field(); ?>
    <?php if($registrar->exists): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>

    <div class="row g-3 mb-3">
      <div class="col-sm-6">
        <label class="form-label small fw-medium text-dark">Nama / Label</label>
        <input type="text" name="name" value="<?php echo e(old('name', $registrar->name)); ?>" placeholder="Namecheap - Utama" class="form-control form-control-sm" required>
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
        <label class="form-label small fw-medium text-dark">Provider</label>
        <select name="provider" id="providerSelect" class="form-select form-select-sm">
          <option value="namecheap" <?php if(old('provider', $registrar->provider ?? 'namecheap') === 'namecheap'): echo 'selected'; endif; ?>>Namecheap</option>
          <option value="liquid" <?php if(old('provider', $registrar->provider) === 'liquid'): echo 'selected'; endif; ?>>Liqu.id</option>
          <option value="resellbiz" <?php if(old('provider', $registrar->provider) === 'resellbiz'): echo 'selected'; endif; ?>>ResellBiz / UK2Group (segera)</option>
          <option value="dnama" <?php if(old('provider', $registrar->provider) === 'dnama'): echo 'selected'; endif; ?>>DNAMA (Daftar Nama)</option>
        </select>
      </div>
    </div>

    
    <div id="fieldApiUrl" class="d-none mb-3">
      <label class="form-label small fw-medium text-dark">API URL <span class="text-muted fw-normal">(opsional)</span></label>
      <input type="url" name="api_url" value="<?php echo e(old('api_url', $registrar->api_url)); ?>" placeholder="https://api.liqu.id/v1" class="form-control form-control-sm">
      <?php $__errorArgs = ['api_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      <p class="text-muted mt-1 mb-0" style="font-size:11px">Kosongkan untuk memakai endpoint default sesuai mode sandbox/produksi. Isi hanya kalau instance Liqu.id kamu memakai domain sendiri.</p>
    </div>

    <div class="row g-3 mb-3">
      <div class="col-sm-6">
        <label class="form-label small fw-medium text-dark"><span id="labelApiUser">API User</span></label>
        <input type="text" name="api_username" value="<?php echo e(old('api_username', $registrar->api_username)); ?>" class="form-control form-control-sm" required>
        <?php $__errorArgs = ['api_username'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      </div>
      <div class="col-sm-6">
        <label class="form-label small fw-medium text-dark">API Key <?php echo e($registrar->exists ? '(kosongkan jika tidak diganti)' : ''); ?></label>
        <input type="password" name="api_key" placeholder="<?php echo e($registrar->exists ? '••••••••••••' : ''); ?>" class="form-control form-control-sm" <?php echo e($registrar->exists ? '' : 'required'); ?>>
        <?php $__errorArgs = ['api_key'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      </div>
    </div>

    
    <div id="fieldsNamecheap" class="row g-3 mb-3">
      <div class="col-sm-6">
        <label class="form-label small fw-medium text-dark">UserName (opsional, default = API User)</label>
        <input type="text" name="username" value="<?php echo e(old('username', $registrar->username)); ?>" class="form-control form-control-sm">
      </div>
      <div class="col-sm-6">
        <label class="form-label small fw-medium text-dark">Client IP (wajib di-whitelist di Namecheap)</label>
        <input type="text" name="client_ip" value="<?php echo e(old('client_ip', $registrar->client_ip)); ?>" placeholder="203.0.113.10" class="form-control form-control-sm">
        <?php $__errorArgs = ['client_ip'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      </div>
    </div>

    <div class="row g-3 mb-1">
      <div class="col-sm-6">
        <label class="form-label small fw-medium text-dark">Nameserver Default 1</label>
        <input type="text" name="default_ns1" value="<?php echo e(old('default_ns1', $registrar->default_ns1)); ?>" placeholder="ns1.dyna-ns.net" class="form-control form-control-sm">
        <?php $__errorArgs = ['default_ns1'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      </div>
      <div class="col-sm-6">
        <label class="form-label small fw-medium text-dark">Nameserver Default 2</label>
        <input type="text" name="default_ns2" value="<?php echo e(old('default_ns2', $registrar->default_ns2)); ?>" placeholder="ns2.dyna-ns.net" class="form-control form-control-sm">
        <?php $__errorArgs = ['default_ns2'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      </div>
    </div>
    <p class="text-muted mb-3" style="font-size:11px">
      Dipakai otomatis saat domain baru didaftarkan — cek di dashboard registrar-mu (biasanya menu "Default Nameserver" di Settings). Kosongkan kalau tidak mau ada default sama sekali.
    </p>

    <div class="mb-3">
      <label class="form-label small fw-medium text-dark">Email Verifikasi Dokumen</label>
      <input type="email" name="documents_email" value="<?php echo e(old('documents_email', $registrar->documents_email)); ?>" placeholder="verifikasi@registrar.com" class="form-control form-control-sm">
      <?php $__errorArgs = ['documents_email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      <p class="text-muted mt-1 mb-0" style="font-size:11px">
        Terisi otomatis sebagai tujuan bawaan saat mengirim dokumen persyaratan domain (KTP/NPWP dll) ke registrar ini
        dari halaman detail domain. Boleh dikosongkan — admin tetap bisa mengetik alamat lain manual saat mengirim.
      </p>
    </div>

    <div class="d-flex align-items-center gap-4 flex-wrap pt-3 border-top mb-3">
      <label id="labelSandbox" class="d-flex align-items-center gap-2 small text-dark mb-0">
        <input type="checkbox" name="sandbox" value="1" <?php if(old('sandbox', $registrar->sandbox ?? true)): echo 'checked'; endif; ?> class="form-check-input" style="margin-top:0">
        Mode Sandbox (testing)
      </label>
      <label class="d-flex align-items-center gap-2 small text-dark mb-0">
        <input type="checkbox" name="is_active" value="1" <?php if(old('is_active', $registrar->is_active ?? true)): echo 'checked'; endif; ?> class="form-check-input" style="margin-top:0">
        Aktif
      </label>
      <label class="d-flex align-items-center gap-2 small text-dark mb-0">
        <input type="checkbox" name="is_default" value="1" <?php if(old('is_default', $registrar->is_default)): echo 'checked'; endif; ?> class="form-check-input" style="margin-top:0">
        Jadikan Default
      </label>
    </div>

    <div class="d-flex align-items-center gap-2 pt-2">
      <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-check" style="font-size:11px"></i> Simpan</button>
      <a href="<?php echo e(route('admin.registrars.index')); ?>" class="btn btn-outline-secondary btn-sm">Batal</a>
    </div>
  </form>

  <script <?php echo 'nonce="' . e(app(\App\Support\CspNonce::class)->value()) . '"'; ?>>
    (function () {
      const select        = document.getElementById('providerSelect');
      const fieldApiUrl   = document.getElementById('fieldApiUrl');
      const fieldsNc      = document.getElementById('fieldsNamecheap');
      const labelApiUser  = document.getElementById('labelApiUser');
      const labelSandbox  = document.getElementById('labelSandbox');

      function sync() {
        const provider = select.value;
        const isNamecheap = provider === 'namecheap';

        labelApiUser.textContent = provider === 'liquid' ? 'Reseller ID' : (provider === 'dnama' ? 'API User (tidak dipakai DNAMA)' : 'API User');

        fieldApiUrl.classList.toggle('d-none', isNamecheap);
        fieldsNc.classList.toggle('d-none', !isNamecheap);
        labelSandbox.classList.toggle('d-none', provider === 'resellbiz');

        document.querySelectorAll('.provider-hint').forEach(el => el.classList.add('d-none'));
        document.getElementById('hint-' + provider)?.classList.remove('d-none');
      }

      select.addEventListener('change', sync);
      sync();
    })();
  </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/admin/registrars/form.blade.php ENDPATH**/ ?>