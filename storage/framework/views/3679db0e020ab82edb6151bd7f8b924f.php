<?php $__env->startSection('title', 'Tambah VPS'); ?>

<?php $__env->startSection('content'); ?>

  <a href="<?php echo e(route('admin.vps')); ?>" class="text-decoration-none text-muted" style="font-size:12px">
    <i class="fa-solid fa-arrow-left"></i> Kembali ke Layanan VPS
  </a>
  <h1 class="h4 fw-bold text-dark mt-1 mb-1">Tambah VPS</h1>
  <p class="small text-muted mb-4">
    Buat VM baru langsung di provider cloud. Berbeda dari Hosting Account biasa —
    di sini yang dibuat adalah mesin virtual utuh, bukan akun di server yang sudah ada.
  </p>

  <?php if($servers->isEmpty()): ?>
    <div class="card border rounded-4 p-5 text-center" style="max-width:42rem">
      <i class="fa-solid fa-cloud text-muted mb-3" style="font-size:1.75rem"></i>
      <p class="fw-medium text-dark mb-1">Belum ada server cloud terhubung</p>
      <p class="text-muted mb-3" style="font-size:14px">
        Tambahkan dulu server bertipe VM / VPS (pilih VPS Provider-nya), lengkap dengan API Token.
      </p>
      <a href="<?php echo e(route('admin.servers.create')); ?>" class="btn btn-primary btn-sm mx-auto" style="width:fit-content">Tambah Server Cloud</a>
    </div>
  <?php else: ?>
    <form method="POST" action="<?php echo e(route('admin.vps.store')); ?>" style="max-width:52rem">
      <?php echo csrf_field(); ?>

      <div class="row g-3 mb-3">
        <div class="col-sm-6">
          <label class="form-label small fw-medium">Provider Size (opsional)</label>
          <input name="provider_size" value="<?php echo e(old('provider_size')); ?>" class="form-control form-control-sm" placeholder="DigitalOcean: s-2vcpu-4gb">
        </div>
        <div class="col-sm-6">
          <label class="form-label small fw-medium">Provider Image ID/Slug (opsional)</label>
          <input name="provider_image_id" value="<?php echo e(old('provider_image_id')); ?>" class="form-control form-control-sm" placeholder="Ubuntu image ID/slug">
        </div>
      </div>

      <div class="row g-3">
        <div class="col-12 col-lg-8">
          <div class="card border rounded-4 p-4 mb-3">
            <h2 class="small fw-bold text-dark mb-3">Pemilik &amp; Server</h2>
            <div class="row g-3">
              <div class="col-sm-6">
                <label class="form-label small fw-medium text-dark">Klien</label>
                <select name="client_id" class="form-select" required>
                  <option value="">— Pilih klien —</option>
                  <?php $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($client->id); ?>" <?php if(old('client_id') == $client->id): echo 'selected'; endif; ?>>
                      <?php echo e($client->name); ?> (saldo Rp <?php echo e(number_format((float) $client->balance, 0, ',', '.')); ?>)
                    </option>
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
                <label class="form-label small fw-medium text-dark">Server Cloud</label>
                <select name="server_id" id="serverSelect" class="form-select" required>
                  <?php $__currentLoopData = $servers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $srv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php $r = \App\Services\Billing\HourlyRateCalculator::effectiveRates($srv); ?>
                    <option value="<?php echo e($srv->id); ?>" <?php if(old('server_id') == $srv->id): echo 'selected'; endif; ?>
                            data-rates="<?php echo e(json_encode([
                              'vcpu' => $r['vcpu'], 'ram' => $r['ram'], 'disk' => $r['storage'],
                              'backup' => $r['backup'], 'windows' => $r['windows'],
                            ])); ?>">
                      <?php echo e($srv->name); ?>

                    </option>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
              </div>
              <div class="col-12">
                <label class="form-label small fw-medium text-dark">Nama VM</label>
                <input type="text" name="domain" value="<?php echo e(old('domain')); ?>" class="form-control" placeholder="vps-klien-01" required>
                <p class="text-muted mt-1 mb-0" style="font-size:11px">
                  Huruf, angka, dan strip saja. Ini label VM di provider — bukan domain sungguhan.
                </p>
                <?php $__errorArgs = ['domain'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
              </div>
            </div>
          </div>

          <div class="card border rounded-4 p-4 mb-3">
            <h2 class="small fw-bold text-dark mb-3">Spesifikasi</h2>

            <?php if($products->isNotEmpty()): ?>
              <div class="mb-3">
                <label class="form-label small fw-medium text-dark">Pakai Paket yang Sudah Ada <span class="text-muted fw-normal">(opsional)</span></label>
                <select id="productPreset" class="form-select">
                  <option value="">— Isi manual di bawah —</option>
                  <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php $ps = json_decode((string) $product->panel_package, true); ?>
                    <?php if(is_array($ps) && isset($ps['vcpu'])): ?>
                      <option value="<?php echo e($product->id); ?>" data-spec="<?php echo e(json_encode($ps)); ?>">
                        <?php echo e($product->name); ?> — <?php echo e($ps['vcpu']); ?> vCPU / <?php echo e($ps['ram']); ?> MB / <?php echo e($ps['disk']); ?> GB
                      </option>
                    <?php endif; ?>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <p class="text-muted mt-1 mb-0" style="font-size:11px">Memilih paket akan mengisi kolom di bawah otomatis.</p>
              </div>
            <?php endif; ?>

            <div class="row g-3">
              <div class="col-6 col-lg-3">
                <label class="form-label small fw-medium text-dark">vCPU</label>
                <input type="number" name="vcpu" id="fVcpu" min="<?php echo e($limits['vcpu']['min']); ?>" max="<?php echo e($limits['vcpu']['max']); ?>" value="<?php echo e(old('vcpu', $limits['vcpu']['min'])); ?>" class="form-control" required>
                <p class="text-muted mt-1 mb-0" style="font-size:10px">Provider: <?php echo e($limits['vcpu']['min']); ?>–<?php echo e($limits['vcpu']['max']); ?></p>
              </div>
              <div class="col-6 col-lg-3">
                <label class="form-label small fw-medium text-dark">RAM (MB)</label>
                <input type="number" name="ram" id="fRam" min="<?php echo e($limits['ram']['min']); ?>" max="<?php echo e($limits['ram']['max']); ?>" step="512" value="<?php echo e(old('ram', max(1024, $limits['ram']['min']))); ?>" class="form-control" required>
                <p class="text-muted mt-1 mb-0" style="font-size:10px">Provider: <?php echo e($limits['ram']['min']); ?>–<?php echo e(number_format($limits['ram']['max'])); ?> MB</p>
              </div>
              <div class="col-6 col-lg-3">
                <label class="form-label small fw-medium text-dark">Disk (GB)</label>
                <input type="number" name="disk" id="fDisk" min="<?php echo e($limits['disks']['min']); ?>" max="<?php echo e($limits['disks']['max']); ?>" value="<?php echo e(old('disk', max(20, $limits['disks']['min']))); ?>" class="form-control" required>
                <p class="text-muted mt-1 mb-0" style="font-size:10px">Provider: <?php echo e($limits['disks']['min']); ?>–<?php echo e($limits['disks']['max']); ?> GB</p>
              </div>
              <div class="col-6 col-lg-3">
                <label class="form-label small fw-medium text-dark">Backup</label>
                <select name="backup_enabled" id="fBackup" class="form-select">
                  <option value="0">Tidak</option>
                  <option value="1" <?php if(old('backup_enabled')): echo 'selected'; endif; ?>>Ya</option>
                </select>
              </div>
              <div class="col-sm-6">
                <label class="form-label small fw-medium text-dark">OS / Aplikasi</label>
                <select name="os_name" id="fOs" class="form-select" required>
                  <option value="">— Pilih OS atau Aplikasi —</option>
                  <?php
                    $plain = collect($osImages)->where('is_app_catalog', false);
                    $apps = collect($osImages)->where('is_app_catalog', true);
                  ?>
                  <?php if($plain->isNotEmpty()): ?>
                    <optgroup label="Sistem Operasi">
                      <?php $__currentLoopData = $plain; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($img['os_name'] ?? ''); ?>"
                                data-versions="<?php echo e(json_encode(collect($img['versions'] ?? [])->pluck('os_version'))); ?>"
                                <?php if(old('os_name') === ($img['os_name'] ?? '')): echo 'selected'; endif; ?>><?php echo e($img['display_name'] ?? $img['os_name']); ?></option>
                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </optgroup>
                  <?php endif; ?>
                  <?php if($apps->isNotEmpty()): ?>
                    <optgroup label="Aplikasi Siap Pakai">
                      <?php $__currentLoopData = $apps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($img['os_name'] ?? ''); ?>"
                                data-versions="<?php echo e(json_encode(collect($img['versions'] ?? [])->pluck('os_version'))); ?>"
                                <?php if(old('os_name') === ($img['os_name'] ?? '')): echo 'selected'; endif; ?>><?php echo e($img['display_name'] ?? $img['os_name']); ?></option>
                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </optgroup>
                  <?php endif; ?>
                </select>
                <?php if($apiError): ?>
                  <p class="mt-1 mb-0" style="font-size:11px;color:#b45309">
                    <i class="fa-solid fa-triangle-exclamation"></i> Daftar OS gagal diambil: <?php echo e($apiError); ?>

                  </p>
                <?php endif; ?>
              </div>
              <div class="col-sm-6">
                <label class="form-label small fw-medium text-dark">Versi</label>
                <select name="os_version" id="fOsVer" class="form-select" required>
                  <option value="">— Pilih OS dulu —</option>
                </select>
              </div>

              <?php if($locations): ?>
                <div class="col-sm-6">
                  <label class="form-label small fw-medium text-dark">Lokasi Datacenter</label>
                  <select name="location" class="form-select">
                    <?php $__currentLoopData = $locations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $loc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                      <option value="<?php echo e($loc['slug'] ?? ''); ?>" <?php if(($loc['is_default'] ?? false) || old('location') === ($loc['slug'] ?? '')): echo 'selected'; endif; ?>>
                        <?php echo e($loc['display_name'] ?? $loc['slug']); ?><?php echo e(! empty($loc['is_default']) ? ' (default)' : ''); ?>

                      </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                  </select>
                  <p class="text-muted mt-1 mb-0" style="font-size:11px">Mengikuti lokasi server kalau dikosongkan.</p>
                </div>
              <?php endif; ?>

              <?php if($pools): ?>
                <div class="col-sm-6">
                  <label class="form-label small fw-medium text-dark">Kelas Server</label>
                  <select name="pool_uuid" class="form-select">
                    <option value="">— Default —</option>
                    <?php $__currentLoopData = $pools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pool): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                      <option value="<?php echo e($pool['uuid'] ?? ''); ?>" <?php if(old('pool_uuid') === ($pool['uuid'] ?? '')): echo 'selected'; endif; ?>>
                        <?php echo e($pool['name'] ?? '?'); ?><?php echo e(! empty($pool['is_default_designated']) ? ' (default)' : ''); ?>

                      </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                  </select>
                  <p class="text-muted mt-1 mb-0" style="font-size:11px"><?php echo e(collect($pools)->pluck('description')->filter()->implode(' · ')); ?></p>
                </div>
              <?php endif; ?>
            </div>
            <p class="text-muted mt-2 mb-0" style="font-size:11px">
              <i class="fa-solid fa-circle-info"></i>
              Daftar OS, lokasi, dan kelas server ditarik langsung dari provider — tidak perlu mengetik manual.
            </p>
          </div>

          <div class="card border rounded-4 p-4 mb-3">
            <h2 class="small fw-bold text-dark mb-3">Login ke Dalam VM</h2>
            <div class="row g-3">
              <div class="col-sm-6">
                <label class="form-label small fw-medium text-dark">Username</label>
                <input type="text" name="username" value="<?php echo e(old('username', 'ubuntu')); ?>" class="form-control" required>
              </div>
              <div class="col-sm-6">
                <label class="form-label small fw-medium text-dark">Password</label>
                <div class="d-flex gap-2">
                  <input type="text" name="password" id="fPass" value="<?php echo e(old('password')); ?>" class="form-control" required minlength="8">
                  <button type="button" data-action="call" data-call="genPass" class="btn btn-outline-secondary text-nowrap flex-shrink-0">
                    <i class="fa-solid fa-dice" style="font-size:11px"></i> Buatkan
                  </button>
                </div>
                <p class="text-muted mt-1 mb-0" style="font-size:11px">Min. 8 karakter, harus ada huruf besar, kecil, dan angka.</p>
              </div>
            </div>
          </div>
        </div>

        <div class="col-12 col-lg-4">
          <div class="card border rounded-4 p-4 mb-3" style="position:sticky;top:5rem">
            <h2 class="small fw-bold text-dark mb-3">Tagihan</h2>

            <label class="form-label small fw-medium text-dark">Mode</label>
            <select name="billing_mode" id="fMode" class="form-select mb-3">
              <option value="deposit" <?php if(old('billing_mode', 'deposit') === 'deposit'): echo 'selected'; endif; ?>>Potong Saldo per Jam</option>
              <option value="invoice" <?php if(old('billing_mode') === 'invoice'): echo 'selected'; endif; ?>>Invoice Berkala</option>
            </select>

            <div id="depositInfo">
              <div class="rounded-3 p-3 mb-2" style="background:rgba(79,70,229,.06);border:1px solid #c7d2fe">
                <p class="text-muted mb-0" style="font-size:11px">Estimasi Tarif Jual</p>
                <p class="fw-bold text-dark mb-0" id="estHour" style="font-size:1.25rem">—</p>
                <p class="text-muted mb-0" id="estMonth" style="font-size:11px">—</p>
              </div>
              <p class="text-muted mb-0" style="font-size:11px">
                Dihitung dari kartu harga server × spesifikasi. Saldo klien dipotong otomatis tiap jam,
                VM di-suspend kalau saldo habis.
              </p>
            </div>

            <div id="invoiceInfo" class="d-none">
              <label class="form-label small fw-medium text-dark">Harga per Siklus</label>
              <input type="number" step="0.01" name="price" value="<?php echo e(old('price', 0)); ?>" class="form-control mb-2">
              <label class="form-label small fw-medium text-dark">Siklus</label>
              <select name="billing_cycle" class="form-select">
                <option value="monthly">Bulanan</option>
                <option value="quarterly">3 Bulan</option>
                <option value="semi_annually">6 Bulan</option>
                <option value="annually">Tahunan</option>
              </select>
            </div>

            <label class="d-flex align-items-start gap-2 small text-dark mt-3 pt-3 border-top mb-0">
              <input type="checkbox" name="provision_now" value="1" checked class="form-check-input flex-shrink-0" style="margin-top:2px">
              <span>
                <b>Buat VM sekarang</b>
                <span class="d-block text-muted" style="font-size:11px">Hilangkan centang kalau cuma mau mencatat VM yang sudah dibuat manual.</span>
              </span>
            </label>

            <button type="submit" class="btn btn-primary w-100 mt-3">
              <i class="fa-solid fa-cloud-arrow-up" style="font-size:11px"></i> Buat VPS
            </button>
          </div>
        </div>
      </div>
    </form>

    <script <?php echo 'nonce="' . e(app(\App\Support\CspNonce::class)->value()) . '"'; ?>>
      // Estimasi tarif langsung berubah saat spek diubah -- supaya admin
      // tahu berapa yang akan ditagihkan SEBELUM VM dibuat.
      // Versi OS mengikuti OS yang dipilih -- daftarnya dari API, jadi
      // tidak mungkin memilih kombinasi yang tidak ada di provider.
      const osSelect = document.getElementById('fOs');
      const verSelect = document.getElementById('fOsVer');

      function isiVersi() {
        const raw = osSelect.selectedOptions[0]?.dataset.versions;
        const versions = raw ? JSON.parse(raw) : [];
        const before = verSelect.value;

        verSelect.innerHTML = versions.length
          ? ''
          : '<option value="">— Tidak ada versi —</option>';

        versions.forEach(function (v) {
          const opt = document.createElement('option');
          opt.value = v;
          opt.textContent = v;
          if (v === before || v === <?php echo json_encode(old('os_version'), 15, 512) ?>) opt.selected = true;
          verSelect.appendChild(opt);
        });

        hitungEstimasi();
      }

      osSelect.addEventListener('change', isiVersi);
      isiVersi();

      function hitungEstimasi() {
        const opt = document.getElementById('serverSelect').selectedOptions[0];
        if (! opt) return;

        const r = JSON.parse(opt.dataset.rates || '{}');
        const vcpu = parseFloat(document.getElementById('fVcpu').value) || 0;
        const ramGb = (parseFloat(document.getElementById('fRam').value) || 0) / 1024;
        const disk = parseFloat(document.getElementById('fDisk').value) || 0;
        const backup = document.getElementById('fBackup').value === '1';
        const isWin = document.getElementById('fOs').value.toLowerCase().includes('windows');

        let perJam = vcpu * (r.vcpu || 0) + ramGb * (r.ram || 0) + disk * (r.disk || 0);
        if (backup) perJam += disk * (r.backup || 0);
        if (isWin) perJam += vcpu * (r.windows || 0);

        const fmt = (n) => 'Rp ' + n.toLocaleString('id-ID', { maximumFractionDigits: 2 });

        document.getElementById('estHour').textContent = perJam > 0 ? fmt(perJam) + ' / jam' : 'Kartu harga belum diisi';
        document.getElementById('estMonth').textContent = perJam > 0 ? '± ' + fmt(perJam * 730) + ' / bulan (730 jam)' : '';
      }

      ['fVcpu', 'fRam', 'fDisk', 'fBackup', 'fOs', 'serverSelect'].forEach(function (id) {
        document.getElementById(id).addEventListener('input', hitungEstimasi);
        document.getElementById(id).addEventListener('change', hitungEstimasi);
      });

      document.getElementById('fMode').addEventListener('change', function () {
        const deposit = this.value === 'deposit';
        document.getElementById('depositInfo').classList.toggle('d-none', ! deposit);
        document.getElementById('invoiceInfo').classList.toggle('d-none', deposit);
      });

      document.getElementById('productPreset')?.addEventListener('change', function () {
        const opt = this.selectedOptions[0];
        if (! opt || ! opt.dataset.spec) return;

        const s = JSON.parse(opt.dataset.spec);
        document.getElementById('fVcpu').value = s.vcpu ?? 1;
        document.getElementById('fRam').value = s.ram ?? 1024;
        document.getElementById('fDisk').value = s.disk ?? 20;
        document.getElementById('fOs').value = s.os_name ?? 'ubuntu';
        document.getElementById('fOsVer').value = s.os_version ?? '';
        document.getElementById('fBackup').value = s.backup_enabled ? '1' : '0';
        hitungEstimasi();
      });

      function genPass() {
        const U = 'ABCDEFGHJKLMNPQRSTUVWXYZ', L = 'abcdefghijkmnpqrstuvwxyz', D = '23456789';
        const all = U + L + D;
        const pick = (s) => s[Math.floor(Math.random() * s.length)];
        let p = [pick(U), pick(L), pick(D)];
        for (let i = 0; i < 9; i++) p.push(pick(all));
        document.getElementById('fPass').value = p.sort(() => Math.random() - 0.5).join('');
      }

      (window.LumoraActions = window.LumoraActions || {}).genPass = genPass;
      hitungEstimasi();
    </script>
  <?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/admin/vps/create.blade.php ENDPATH**/ ?>