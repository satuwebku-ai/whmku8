<?php $__env->startSection('title', $domain->exists ? 'Edit Domain' : 'Tambah Domain'); ?>

<?php $__env->startSection('content'); ?>

  <div class="mb-4">
    <h1 class="h4 fw-bold text-dark mb-1"><?php echo e($domain->exists ? 'Edit Domain' : 'Tambah Domain'); ?></h1>
    <p class="small text-muted mb-0">
      <?php if($domain->exists && $domain->provision_message): ?>
        Status registrasi terakhir:
        <span class="fw-medium <?php echo e($domain->provision_status === 'registered' ? 'text-success' : ($domain->provision_status === 'failed' ? 'text-danger' : 'text-muted')); ?>">
          <?php echo e($domain->provision_message); ?>

        </span>
      <?php else: ?>
        Centang "Registrasi Otomatis" untuk langsung mendaftarkan domain lewat registrar.
      <?php endif; ?>
    </p>
  </div>

  <form method="POST" action="<?php echo e($domain->exists ? route('admin.domain.update', $domain) : route('admin.domain.add')); ?>" class="card border rounded-4 p-4" style="max-width:42rem">
    <?php echo csrf_field(); ?>

    <?php $selectStyle = 'padding:.25rem .6rem;font-size:.875rem;border-radius:.375rem'; ?>

    <div class="row g-3 mb-3">
      <div class="col-sm-6">
        <label class="form-label small fw-medium text-dark">Klien</label>
        <select name="client_id" class="form-select" style="<?php echo e($selectStyle); ?>" required>
          <option value="">Pilih klien</option>
          <?php $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($client->id); ?>" <?php if(old('client_id', $domain->client_id) == $client->id): echo 'selected'; endif; ?>><?php echo e($client->name); ?></option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <?php $__errorArgs = ['client_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      </div>
      <div class="col-sm-6">
        <label class="form-label small fw-medium text-dark">Nama Domain</label>
        <input type="text" name="domain_name" value="<?php echo e(old('domain_name', $domain->domain_name ?? request('domain'))); ?>" placeholder="contoh.com" class="form-control form-control-sm" required>
        <?php $__errorArgs = ['domain_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      </div>
    </div>

    <div class="row g-3 mb-3">
      <div class="col-sm-4">
        <label class="form-label small fw-medium text-dark">Registrar (opsional)</label>
        <select name="registrar_id" class="form-select" style="<?php echo e($selectStyle); ?>">
          <option value="">— Manual —</option>
          <?php $__currentLoopData = $registrars; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($r->id); ?>" <?php if(old('registrar_id', $domain->registrar_id) == $r->id): echo 'selected'; endif; ?>><?php echo e($r->name); ?></option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
      </div>
      <div class="col-sm-4">
        <label class="form-label small fw-medium text-dark">TLD (opsional, untuk harga)</label>
        <select name="tld_id" class="form-select" style="<?php echo e($selectStyle); ?>">
          <option value="">—</option>
          <?php $__currentLoopData = $tlds; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($t->id); ?>" <?php if(old('tld_id', $domain->tld_id) == $t->id): echo 'selected'; endif; ?>><?php echo e($t->extension); ?></option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
      </div>
      <div class="col-sm-4">
        <label class="form-label small fw-medium text-dark">Lama (tahun)</label>
        <input type="number" name="years" min="1" max="10" value="<?php echo e(old('years', $domain->years ?? 1)); ?>" class="form-control form-control-sm" required>
      </div>
    </div>

    <div class="row g-3 mb-3">
      <div class="col-sm-6">
        <label class="form-label small fw-medium text-dark">Harga</label>
        <input type="number" step="0.01" name="price" value="<?php echo e(old('price', $domain->price)); ?>" class="form-control form-control-sm" required>
        <?php $__errorArgs = ['price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      </div>
      <div class="col-sm-6">
        <label class="form-label small fw-medium text-dark">Jatuh Tempo (kalau sudah tahu)</label>
        <input type="date" name="expiry_date" value="<?php echo e(old('expiry_date', optional($domain->expiry_date)->format('Y-m-d'))); ?>" class="form-control form-control-sm">
      </div>
    </div>

    <div class="d-flex align-items-center gap-4 mb-3">
      <label class="d-flex align-items-center gap-2 small text-dark" style="cursor:pointer">
        <input type="checkbox" name="auto_renew" value="1" <?php if(old('auto_renew', $domain->auto_renew ?? true)): echo 'checked'; endif; ?>>
        Auto Renew
      </label>
      <label class="d-flex align-items-center gap-2 small text-dark" style="cursor:pointer">
        <input type="checkbox" name="whois_privacy" value="1" <?php if(old('whois_privacy', $domain->whois_privacy)): echo 'checked'; endif; ?>>
        WHOIS Privacy
      </label>
    </div>

    <div class="mb-3">
      <label class="form-label small fw-medium text-dark">Status</label>
      <select name="status" class="form-select" style="<?php echo e($selectStyle); ?>;max-width:16rem">
        <option value="pending" <?php if(old('status', $domain->status) === 'pending'): echo 'selected'; endif; ?>>Pending</option>
        <option value="active" <?php if(old('status', $domain->status) === 'active'): echo 'selected'; endif; ?>>Aktif</option>
        <option value="expired" <?php if(old('status', $domain->status) === 'expired'): echo 'selected'; endif; ?>>Expired</option>
        <option value="cancelled" <?php if(old('status', $domain->status) === 'cancelled'): echo 'selected'; endif; ?>>Cancelled</option>
      </select>
    </div>

    <?php if (! ($domain->exists)): ?>
      <div class="rounded-3 border border-primary p-3 mb-3" style="border-style:dashed!important;background:rgba(79,70,229,.05)">
        <label class="d-flex align-items-center gap-2 small fw-bold text-accent mb-2" style="cursor:pointer">
          <input type="checkbox" name="register_now" value="1" id="registerNow" <?php if(old('register_now')): echo 'checked'; endif; ?>>
          Registrasi Otomatis lewat Registrar Sekarang
        </label>

        <p class="text-muted mb-3" style="font-size:11px">Wajib diisi kalau opsi di atas dicentang — registrar butuh data kontak WHOIS lengkap.</p>

        <div class="row g-2 mb-2">
          <div class="col-sm-6"><input type="text" name="contact_first_name" value="<?php echo e(old('contact_first_name')); ?>" placeholder="Nama Depan" class="form-control form-control-sm"></div>
          <div class="col-sm-6"><input type="text" name="contact_last_name" value="<?php echo e(old('contact_last_name')); ?>" placeholder="Nama Belakang" class="form-control form-control-sm"></div>
        </div>
        <input type="text" name="contact_address" value="<?php echo e(old('contact_address')); ?>" placeholder="Alamat" class="form-control form-control-sm mb-2">
        <div class="row g-2 mb-2">
          <div class="col-sm-4"><input type="text" name="contact_city" value="<?php echo e(old('contact_city')); ?>" placeholder="Kota" class="form-control form-control-sm"></div>
          <div class="col-sm-4"><input type="text" name="contact_state" value="<?php echo e(old('contact_state')); ?>" placeholder="Provinsi" class="form-control form-control-sm"></div>
          <div class="col-sm-4"><input type="text" name="contact_postal_code" value="<?php echo e(old('contact_postal_code')); ?>" placeholder="Kode Pos" class="form-control form-control-sm"></div>
        </div>
        <div class="row g-2">
          <div class="col-sm-4"><input type="text" name="contact_country" value="<?php echo e(old('contact_country', 'ID')); ?>" maxlength="2" placeholder="Kode Negara (ID)" class="form-control form-control-sm"></div>
          <div class="col-sm-4"><input type="text" name="contact_phone" value="<?php echo e(old('contact_phone')); ?>" placeholder="+62.8123456789" class="form-control form-control-sm"></div>
          <div class="col-sm-4"><input type="email" name="contact_email" value="<?php echo e(old('contact_email')); ?>" placeholder="email@contoh.com" class="form-control form-control-sm"></div>
        </div>
      </div>
    <?php endif; ?>

    <div class="d-flex align-items-center gap-2 pt-2">
      <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-check" style="font-size:11px"></i> Simpan</button>
      <a href="<?php echo e(route('admin.domains')); ?>" class="btn btn-outline-secondary btn-sm">Batal</a>
    </div>
  </form>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/admin/domains/form.blade.php ENDPATH**/ ?>