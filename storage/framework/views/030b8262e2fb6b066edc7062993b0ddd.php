<?php $__env->startSection('title', 'DNS — ' . $domain->domain_name); ?>

<?php $__env->startSection('content'); ?>
  <a href="<?php echo e(route('client.domains.show', $domain)); ?>" class="text-decoration-none text-muted" style="font-size:12px">
    &larr; Kembali ke <?php echo e($domain->domain_name); ?>

  </a>

  <div class="mt-2 mb-4">
    <h1 class="h4 fw-bold text-dark mb-1">Kelola DNS — <?php echo e($domain->domain_name); ?></h1>
    <p class="text-muted mb-0">
      Hanya berlaku kalau domain memakai nameserver bawaan registrar. Kalau nameserver sudah
      diarahkan ke penyedia lain (mis. server hosting Anda), kelola DNS di sana, bukan di sini.
    </p>
  </div>

  <?php if($warning): ?>
    <div class="card-public p-4 mb-4" style="border-color:#fde68a!important;background:#fffbeb">
      <p class="mb-0" style="font-size:14px;color:#92400e">
        <i class="fa-solid fa-triangle-exclamation"></i>
        Sebagian data tidak bisa diambil: <?php echo e($warning); ?>

      </p>
    </div>
  <?php endif; ?>

  <div class="row g-4">
    <div class="col-12 col-lg-8">
      <div class="card-public overflow-hidden">
        <div class="px-4 py-3 border-bottom">
          <h2 class="small fw-bold text-dark mb-0">Record Saat Ini</h2>
        </div>

        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead>
              <tr class="small text-uppercase text-muted" style="background:#f8fafc">
                <th class="px-4 py-3">Tipe</th>
                <th class="py-3">Host</th>
                <th class="py-3">Nilai</th>
                <th class="text-end px-4 py-3">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php $__empty_1 = true; $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                  <td class="px-4 py-3"><span class="badge badge-soft-secondary"><?php echo e($record['type']); ?></span></td>
                  <td class="py-3" style="font-family:monospace;font-size:12px"><?php echo e($record['hostname']); ?></td>
                  <td class="py-3" style="font-family:monospace;font-size:12px;word-break:break-all">
                    <?php echo e($record['value']); ?>

                    <?php if($record['priority']): ?>
                      <span class="text-muted">(prioritas <?php echo e($record['priority']); ?>)</span>
                    <?php endif; ?>
                  </td>
                  <td class="text-end px-4 py-3">
                    <form method="POST" action="<?php echo e(route('client.domains.dns.delete', $domain)); ?>"
                          data-confirm="Hapus record <?php echo e($record['type']); ?> <?php echo e($record['hostname']); ?>?"
                          data-confirm-title="Hapus Record DNS" data-confirm-style="danger" data-confirm-label="Ya, Hapus">
                      <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                      <input type="hidden" name="type" value="<?php echo e($record['type']); ?>">
                      <input type="hidden" name="hostname" value="<?php echo e($record['hostname']); ?>">
                      <input type="hidden" name="value" value="<?php echo e($record['value']); ?>">
                      <button type="submit" class="btn btn-outline-danger btn-sm d-inline-flex align-items-center justify-content-center" style="width:28px;height:28px;padding:0">
                        <i class="fa-regular fa-trash-can" style="font-size:11px"></i>
                      </button>
                    </form>
                  </td>
                </tr>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="4" class="text-center text-muted py-5">Belum ada record DNS kustom.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div class="col-12 col-lg-4">
      <div class="card-public p-4">
        <h2 class="small fw-bold text-dark mb-3">Tambah Record</h2>

        <form method="POST" action="<?php echo e(route('client.domains.dns.add', $domain)); ?>" class="d-flex flex-column gap-3" id="dnsForm">
          <?php echo csrf_field(); ?>

          <div>
            <label class="form-label">Tipe</label>
            <select name="type" id="dnsType" class="form-select">
              <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($t); ?>"><?php echo e($t); ?></option>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
          </div>

          <div>
            <label class="form-label">Host</label>
            <input type="text" name="hostname" required placeholder="@ atau www" class="form-control">
            <p class="text-muted mt-1 mb-0" style="font-size:11px">Isi "@" untuk domain utama.</p>
          </div>

          <div>
            <label class="form-label">Nilai</label>
            <input type="text" name="value" required placeholder="192.0.2.1" class="form-control">
          </div>

          <div id="priorityField" class="d-none">
            <label class="form-label">Prioritas (khusus MX)</label>
            <input type="number" name="priority" min="0" value="10" class="form-control">
          </div>

          <?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          <?php $__errorArgs = ['hostname'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          <?php $__errorArgs = ['value'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

          <button type="submit" class="btn btn-theme w-100">
            <i class="fa-solid fa-plus" style="font-size:11px"></i> Tambah Record
          </button>
        </form>
      </div>

      <div class="card-public p-4 mt-4 text-muted" style="font-size:12px;line-height:1.7">
        <p class="fw-bold text-dark mb-1">Contoh nilai per tipe</p>
        <p class="mb-1"><b>A</b> — alamat IP server, mis. <code>203.0.113.10</code></p>
        <p class="mb-1"><b>AAAA</b> — alamat IPv6 server, mis. <code>2001:db8::1</code></p>
        <p class="mb-1"><b>CNAME</b> — nama domain lain, mis. <code>contoh.com</code></p>
        <p class="mb-1"><b>MX</b> — server email, mis. <code>mail.contoh.com</code></p>
        <p class="mb-0"><b>TXT</b> — teks verifikasi, mis. <code>v=spf1 include:_spf.google.com ~all</code></p>
      </div>
    </div>
  </div>

  <script <?php echo 'nonce="' . e(app(\App\Support\CspNonce::class)->value()) . '"'; ?>>
    // Kolom prioritas hanya relevan untuk MX.
    (function () {
      const type = document.getElementById('dnsType');
      const field = document.getElementById('priorityField');

      function sync() { field.classList.toggle('d-none', type.value !== 'MX'); }

      type.addEventListener('change', sync);
      sync();
    })();
  </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('client.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/themes/client-themes/modern/client/domains/dns.blade.php ENDPATH**/ ?>