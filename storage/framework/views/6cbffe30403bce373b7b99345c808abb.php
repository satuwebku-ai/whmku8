<?php $__env->startSection('title', $vps->domain); ?>

<?php $__env->startSection('content'); ?>
  <a href="<?php echo e(route('client.vps')); ?>" class="text-decoration-none text-muted" style="font-size:12px">&larr; Kembali ke VPS Saya</a>

  <?php
    $status = $vmInfo['status'] ?? null;
    $isRunning = $status === 'running';
    $spec = $vps->hasVmSpec() ? $vps->vmSpec() : null;
  ?>

  <div class="d-flex align-items-center justify-content-between mt-2 mb-4 flex-wrap gap-3">
    <div>
      <h1 class="h4 fw-bold text-dark mb-0"><?php echo e($vps->domain); ?></h1>
      <?php if($status): ?>
        <span class="badge <?php echo e($isRunning ? 'badge-soft-success' : 'badge-soft-secondary'); ?> mt-1">
          <i class="fa-solid fa-circle" style="font-size:7px"></i> <?php echo e($isRunning ? 'Menyala' : ucfirst($status)); ?>

        </span>
      <?php endif; ?>
    </div>

    <?php if($vps->serverModel && $vps->username): ?>
      <div class="d-flex align-items-center gap-2">
        <?php if(! $isRunning): ?>
          <form method="POST" action="<?php echo e(route('client.vps.power', $vps)); ?>">
            <?php echo csrf_field(); ?> <input type="hidden" name="action" value="start">
            <button type="submit" class="btn btn-theme btn-sm"><i class="fa-solid fa-play" style="font-size:11px"></i> Nyalakan</button>
          </form>
        <?php else: ?>
          <form method="POST" action="<?php echo e(route('client.vps.power', $vps)); ?>"
                data-confirm="Nyalakan ulang VPS ini? Layanan akan terputus sesaat." data-confirm-title="Restart VPS" data-confirm-style="warn" data-confirm-label="Ya, Restart">
            <?php echo csrf_field(); ?> <input type="hidden" name="action" value="restart">
            <button type="submit" class="btn btn-outline-secondary btn-sm"><i class="fa-solid fa-rotate" style="font-size:11px"></i> Restart</button>
          </form>
          <form method="POST" action="<?php echo e(route('client.vps.power', $vps)); ?>"
                data-confirm="Matikan VPS ini? Website/aplikasi di dalamnya akan berhenti." data-confirm-title="Matikan VPS" data-confirm-style="danger" data-confirm-label="Ya, Matikan">
            <?php echo csrf_field(); ?> <input type="hidden" name="action" value="stop">
            <button type="submit" class="btn btn-outline-danger btn-sm"><i class="fa-solid fa-power-off" style="font-size:11px"></i> Matikan</button>
          </form>
          <form method="POST" action="<?php echo e(route('client.vps.power', $vps)); ?>"
                data-confirm="MATIKAN PAKSA? Ini seperti mencabut listrik — data yang belum tersimpan bisa rusak. Pakai hanya kalau VPS tidak merespons perintah matikan biasa."
                data-confirm-title="Matikan Paksa" data-confirm-style="danger" data-confirm-label="Ya, Paksa">
            <?php echo csrf_field(); ?> <input type="hidden" name="action" value="force_stop">
            <button type="submit" class="btn btn-outline-danger btn-sm" title="Untuk VPS yang tidak merespons">
              <i class="fa-solid fa-plug-circle-xmark" style="font-size:11px"></i> Paksa
            </button>
          </form>
        <?php endif; ?>
      </div>
    <?php endif; ?>
  </div>

  <?php if($apiError): ?>
    <div class="card-public p-4 mb-4" style="border-color:#fde68a!important;background:#fffbeb">
      <p class="mb-0" style="font-size:14px;color:#92400e">
        <i class="fa-solid fa-triangle-exclamation"></i>
        Status terkini tidak bisa diambil: <?php echo e($apiError); ?>

      </p>
    </div>
  <?php endif; ?>

  <?php $ipPublik = $vmInfo['public_ipv4'] ?? $vmInfo['public_ipv6'] ?? null; ?>
  <?php if($vmInfo && ! $ipPublik): ?>
    <div class="card-public p-4 mb-4" style="border-color:#fde68a!important;background:#fffbeb">
      <p class="mb-0" style="font-size:14px;color:#92400e">
        <i class="fa-solid fa-globe"></i>
        <b>Belum ada IP publik.</b> VPS ini baru punya alamat internal, jadi belum bisa diakses dari
        internet (belum bisa SSH atau membuka website di dalamnya). Hubungi support kami untuk
        meminta pemasangan IP publik.
      </p>
    </div>
  <?php endif; ?>

  
  <?php if($vps->billing_mode === 'deposit' && $hoursLeft !== null && $hoursLeft < 48): ?>
    <div class="card-public p-4 mb-4" style="border-color:#fecaca!important;background:#fef2f2">
      <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap">
        <p class="mb-0" style="font-size:14px;color:#b91c1c">
          <i class="fa-solid fa-circle-exclamation"></i>
          Saldo Anda tinggal cukup untuk <b>± <?php echo e($hoursLeft); ?> jam</b> lagi.
          VPS akan otomatis dimatikan kalau saldo habis.
        </p>
        <a href="<?php echo e(route('client.balance')); ?>" class="btn btn-theme btn-sm flex-shrink-0">Isi Saldo</a>
      </div>
    </div>
  <?php endif; ?>

  <div class="row g-4">
    <div class="col-12 col-lg-8 d-flex flex-column gap-4">
      <div class="card-public p-4">
        <h2 class="small fw-bold text-dark mb-3">Spesifikasi</h2>
        <div class="row g-3">
          <div class="col-6 col-lg-3">
            <p class="text-muted mb-0" style="font-size:11px">vCPU</p>
            <p class="fw-semibold text-dark mb-0"><?php echo e($vmInfo['vcpu'] ?? $spec['vcpu'] ?? '—'); ?> Core</p>
          </div>
          <div class="col-6 col-lg-3">
            <p class="text-muted mb-0" style="font-size:11px">RAM</p>
            <p class="fw-semibold text-dark mb-0"><?php echo e($vmInfo['memory'] ?? $spec['ram'] ?? '—'); ?> MB</p>
          </div>
          <div class="col-6 col-lg-3">
            <p class="text-muted mb-0" style="font-size:11px">Disk</p>
            <p class="fw-semibold text-dark mb-0">
              <?php echo e(collect($vmInfo['storage'] ?? [])->firstWhere('primary', true)['size'] ?? $spec['disk'] ?? '—'); ?> GB
            </p>
          </div>
          <div class="col-6 col-lg-3">
            <p class="text-muted mb-0" style="font-size:11px">OS</p>
            <p class="fw-semibold text-dark mb-0" style="font-size:14px">
              <?php echo e($vmInfo['os_name'] ?? '—'); ?> <?php echo e($vmInfo['os_version'] ?? ''); ?>

            </p>
          </div>
        </div>

        <div class="mt-4 pt-4 border-top">
          <h3 class="small fw-bold text-dark mb-3">Alamat Jaringan</h3>
          <div class="row g-3">
            <div class="col-sm-6">
              <p class="text-muted mb-0" style="font-size:11px">IP Publik</p>
              <p class="fw-medium text-dark mb-0" style="font-family:monospace;font-size:14px">
                <?php echo e($vmInfo['public_ipv4'] ?? $vmInfo['public_ipv6'] ?? '—'); ?>

              </p>
            </div>
            <div class="col-sm-6">
              <p class="text-muted mb-0" style="font-size:11px">IP Privat</p>
              <p class="fw-medium text-dark mb-0" style="font-family:monospace;font-size:14px">
                <?php echo e($vmInfo['private_ipv4'] ?? '—'); ?>

              </p>
            </div>
          </div>
        </div>

        <?php if($vps->client_details): ?>
          <div class="mt-4 pt-4 border-top">
            <h3 class="small fw-bold text-dark mb-2"><i class="fa-solid fa-key"></i> Info Akses</h3>
            <div class="rounded-3 p-3" style="background:#1e293b;color:#f1f5f9;font-family:monospace;font-size:12px;white-space:pre-line;word-break:break-word"><?php echo e($vps->client_details); ?></div>
            <p class="text-muted mt-2 mb-0" style="font-size:11px">Jaga kerahasiaan info ini. Ganti password lewat panel di samping.</p>
          </div>
        <?php endif; ?>
      </div>

      <?php if($vps->serverModel && $vps->username): ?>
        <?php
          $ipAkses = $vmInfo['public_ipv4'] ?? $vmInfo['private_ipv4'] ?? null;
          $userVm = $vmInfo['username'] ?? 'ubuntu';
          $isWindows = str_contains(strtolower($vmInfo['os_name'] ?? ''), 'windows');
        ?>

        
        <?php if($ipAkses): ?>
          <div class="card-public p-4">
            <h2 class="small fw-bold text-dark mb-2">
              <i class="fa-solid fa-terminal"></i> Cara Masuk ke VPS
            </h2>
            <?php if($isWindows): ?>
              <?php
                $rdpContent = "full address:s:{$ipAkses}\nusername:s:{$userVm}\nprompt for credentials:i:1\n";
              ?>
              <p class="text-muted mb-2" style="font-size:14px">Klik tombol di bawah untuk membuka Remote Desktop Connection langsung ke VPS ini:</p>
              <a href="data:application/x-rdp;charset=utf-8,<?php echo e(rawurlencode($rdpContent)); ?>" download="<?php echo e(Str::slug($vps->domain)); ?>.rdp" class="btn btn-theme btn-sm">
                <i class="fa-solid fa-desktop" style="font-size:11px"></i> Masuk ke VPS (Unduh file RDP)
              </a>
              <p class="text-muted mt-2 mb-0" style="font-size:11px">Membutuhkan aplikasi <b>Remote Desktop Connection</b> (sudah bawaan Windows). Username: <b><?php echo e($userVm); ?></b> — password sesuai yang Anda atur.</p>
              <details class="mt-2">
                <summary class="text-muted" style="font-size:11px;cursor:pointer">Atau masuk manual</summary>
                <div class="rounded-3 p-3 mt-2" style="background:#1e293b;color:#f1f5f9;font-family:monospace;font-size:13px"><?php echo e($ipAkses); ?></div>
              </details>
            <?php else: ?>
              <p class="text-muted mb-2" style="font-size:14px">Klik tombol di bawah untuk membuka Terminal SSH ke VPS ini (butuh aplikasi SSH terpasang, mis. Terminal bawaan macOS/Linux, Windows Terminal dengan OpenSSH, atau Termius):</p>
              <a href="ssh://<?php echo e($userVm); ?>{{ $ipAkses }}" class="btn btn-theme btn-sm">
                <i class="fa-solid fa-terminal" style="font-size:11px"></i> Masuk ke VPS (SSH)
              </a>
              <p class="text-muted mt-2 mb-0" style="font-size:11px">Tidak terbuka? Salin perintah ini dan jalankan manual di Terminal / PowerShell:</p>
              <div class="rounded-3 p-3 d-flex align-items-center justify-content-between gap-2 mt-1" style="background:#1e293b;color:#f1f5f9;font-family:monospace;font-size:13px">
                <span id="sshCmd">ssh <?php echo e($userVm); ?>{{ $ipAkses }}</span>
                <button type="button" data-action="call" data-call="salinSsh" class="btn btn-sm btn-outline-light" style="font-size:11px">Salin</button>
              </div>
            <?php endif; ?>
            <?php if(str_starts_with((string) $ipAkses, '10.')): ?>
              <p class="mt-2 mb-0" style="font-size:11px;color:#b45309">
                <i class="fa-solid fa-triangle-exclamation"></i>
                Alamat ini masih internal — belum bisa diakses dari luar sampai IP publik dipasang.
              </p>
            <?php endif; ?>
          </div>
        <?php endif; ?>

        <?php if($caps['password']): ?>
        <div class="card-public p-4">
          <h2 class="small fw-bold text-dark mb-1">Ganti Password VPS</h2>
          <p class="text-muted mb-3" style="font-size:12px">VPS harus dalam keadaan <b>menyala</b> agar password bisa diganti.</p>
          <form method="POST" action="<?php echo e(route('client.vps.password', $vps)); ?>" class="row g-2"
                data-confirm="Ganti password VPS sekarang?" data-confirm-title="Ganti Password" data-confirm-style="warn" data-confirm-label="Ya, Ganti">
            <?php echo csrf_field(); ?>
            <div class="col-sm-5">
              <input type="text" name="vm_username" value="<?php echo e($userVm); ?>" placeholder="Username di dalam VM" class="form-control form-control-sm" required>
            </div>
            <div class="col-sm-5">
              <input type="text" name="new_password" id="vpsPass" placeholder="Password baru" class="form-control form-control-sm" required minlength="8">
            </div>
            <div class="col-sm-2 d-flex gap-1">
              <button type="button" data-action="call" data-call="genVpsPass" class="btn btn-outline-secondary btn-sm" title="Buatkan password"><i class="fa-solid fa-dice" style="font-size:11px"></i></button>
              <button type="submit" class="btn btn-theme btn-sm flex-grow-1">Ganti</button>
            </div>
          </form>
        </div>
        <?php endif; ?>

        
        <?php if($caps['resize']): ?>
        <div class="card-public p-4">
          <h2 class="small fw-bold text-dark mb-1">Ubah Spesifikasi</h2>
          <p class="text-muted mb-3" style="font-size:12px">
            VPS harus <b>dimatikan</b> dulu. Mengubah spesifikasi langsung mengubah tarif per jam Anda.
          </p>
          <?php if($isRunning): ?>
            <p class="mb-0 rounded-3 px-3 py-2" style="font-size:12px;color:#92400e;background:#fffbeb;border:1px solid #fde68a">
              Matikan VPS terlebih dulu untuk bisa mengubah spesifikasi.
            </p>
          <?php else: ?>
            <form method="POST" action="<?php echo e(route('client.vps.resize', $vps)); ?>" class="row g-2 align-items-end"
                  data-confirm="Ubah spesifikasi VPS? Tarif per jam Anda akan menyesuaikan." data-confirm-title="Ubah Spesifikasi" data-confirm-style="warn" data-confirm-label="Ya, Ubah">
              <?php echo csrf_field(); ?>
              <div class="col-sm-4">
                <label class="form-label small fw-medium text-dark mb-1">vCPU</label>
                <input type="number" name="vcpu" value="<?php echo e($vmInfo['vcpu'] ?? $spec['vcpu'] ?? 2); ?>" min="2" max="32" class="form-control form-control-sm" required>
              </div>
              <div class="col-sm-4">
                <label class="form-label small fw-medium text-dark mb-1">RAM (MB)</label>
                <input type="number" name="ram" value="<?php echo e($vmInfo['memory'] ?? $spec['ram'] ?? 2048); ?>" step="512" min="1024" class="form-control form-control-sm" required>
              </div>
              <div class="col-sm-4">
                <button type="submit" class="btn btn-theme btn-sm w-100">Ubah Spesifikasi</button>
              </div>
            </form>
            <p class="text-muted mt-2 mb-0" style="font-size:11px">
              Ukuran disk tidak bisa diubah dari sini — hubungi support kalau butuh ruang tambahan.
            </p>
          <?php endif; ?>
        </div>
        <?php endif; ?>

        
        <?php if($caps['reinstall']): ?>
        <div class="card-public p-4" style="border-color:#fecaca!important">
          <h2 class="small fw-bold mb-1" style="color:#b91c1c">Instal Ulang OS</h2>
          <p class="mb-3" style="font-size:12px;color:#b91c1c">
            <b>SELURUH DATA di VPS ini akan HILANG PERMANEN</b> dan diganti sistem operasi baru.
            Pastikan Anda sudah mencadangkan data penting.
          </p>
          <form method="POST" action="<?php echo e(route('client.vps.reinstall', $vps)); ?>" class="row g-2 align-items-end">
            <?php echo csrf_field(); ?>
            <div class="col-sm-4">
              <label class="form-label small fw-medium text-dark mb-1">OS / Aplikasi</label>
              <select name="os_name" id="reOs" class="form-select form-select-sm" required>
                <option value="">— Pilih —</option>
                <?php $__currentLoopData = $osImages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <option value="<?php echo e($img['os_name'] ?? ''); ?>"
                          data-versions="<?php echo e(json_encode(collect($img['versions'] ?? [])->pluck('os_version'))); ?>">
                    <?php echo e($img['display_name'] ?? $img['os_name']); ?>

                  </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </select>
            </div>
            <div class="col-sm-3">
              <label class="form-label small fw-medium text-dark mb-1">Versi</label>
              <select name="os_version" id="reVer" class="form-select form-select-sm" required>
                <option value="">— Pilih OS dulu —</option>
              </select>
            </div>
            <div class="col-sm-3">
              <label class="form-label small fw-medium text-dark mb-1">Ketik "<?php echo e($vps->domain); ?>"</label>
              <input type="text" name="konfirmasi" class="form-control form-control-sm" placeholder="<?php echo e($vps->domain); ?>" required>
            </div>
            <div class="col-sm-2">
              <button type="submit" class="btn btn-danger btn-sm w-100">Instal Ulang</button>
            </div>
          </form>
        </div>
        <?php endif; ?>
      <?php endif; ?>
    </div>

    <div class="col-12 col-lg-4 d-flex flex-column gap-4">
      <div class="card-public p-4">
        <h2 class="small fw-bold text-dark mb-3">Tagihan</h2>

        <?php if($vps->billing_mode === 'deposit'): ?>
          <div class="rounded-3 p-3 mb-3" style="background:rgba(79,70,229,.06);border:1px solid #c7d2fe">
            <p class="text-muted mb-0" style="font-size:11px">Tarif Berjalan</p>
            <p class="fw-bold text-dark mb-0" style="font-size:1.25rem">
              <?php echo e($rate ? 'Rp ' . number_format($rate, 2, ',', '.') : '—'); ?>

              <span class="text-muted fw-normal" style="font-size:12px">/ jam</span>
            </p>
            <?php if($rate): ?>
              <p class="text-muted mb-0" style="font-size:11px">± Rp <?php echo e(number_format($rate * 730, 0, ',', '.')); ?> / bulan</p>
            <?php endif; ?>
          </div>

          <?php if(! empty($breakdown)): ?>
            <details class="mb-3">
              <summary class="text-muted" style="font-size:11px;cursor:pointer">Lihat rincian tarif</summary>
              <div class="mt-2">
                <?php $__currentLoopData = $breakdown; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label => $nominal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <div class="d-flex justify-content-between" style="font-size:12px">
                    <span class="text-muted"><?php echo e($label); ?></span>
                    <span class="text-dark">Rp <?php echo e(number_format($nominal, 2, ',', '.')); ?>/jam</span>
                  </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </div>
            </details>
          <?php endif; ?>

          <div class="d-flex justify-content-between mb-2" style="font-size:14px">
            <span class="text-muted">Saldo Anda</span>
            <span class="fw-semibold <?php echo e((float) $client->balance <= 0 ? 'text-danger' : 'text-dark'); ?>">
              Rp <?php echo e(number_format((float) $client->balance, 0, ',', '.')); ?>

            </span>
          </div>
          <?php if($hoursLeft !== null): ?>
            <div class="d-flex justify-content-between mb-3" style="font-size:14px">
              <span class="text-muted">Cukup untuk</span>
              <span class="fw-semibold text-dark">± <?php echo e($hoursLeft); ?> jam</span>
            </div>
          <?php endif; ?>

          <a href="<?php echo e(route('client.balance')); ?>" class="btn btn-theme w-100">
            <i class="fa-solid fa-wallet" style="font-size:11px"></i> Isi Saldo
          </a>
          <p class="text-muted mt-2 mb-0" style="font-size:11px">
            Saldo dipotong tiap jam selama VPS menyala. Matikan VPS untuk berhenti menagih.
          </p>
        <?php else: ?>
          <div class="d-flex justify-content-between mb-2" style="font-size:14px">
            <span class="text-muted">Harga</span>
            <span class="fw-semibold text-dark">Rp <?php echo e(number_format((float) $vps->price, 0, ',', '.')); ?></span>
          </div>
          <div class="d-flex justify-content-between mb-2" style="font-size:14px">
            <span class="text-muted">Siklus</span>
            <span class="text-dark text-capitalize"><?php echo e(str_replace('_', ' ', $vps->billing_cycle)); ?></span>
          </div>
          <div class="d-flex justify-content-between" style="font-size:14px">
            <span class="text-muted">Jatuh Tempo</span>
            <span class="text-dark"><?php echo e($vps->next_due_date?->format('d M Y') ?? '—'); ?></span>
          </div>
        <?php endif; ?>
      </div>

      <div class="card-public p-4">
        <h2 class="small fw-bold text-dark mb-2">Butuh Bantuan?</h2>
        <p class="text-muted mb-3" style="font-size:14px">Ada kendala dengan VPS ini?</p>
        <a href="<?php echo e(route('client.tickets.create')); ?>" class="btn btn-theme w-100">
          <i class="fa-solid fa-headset" style="font-size:11px"></i> Hubungi Support
        </a>
      </div>
    </div>
  </div>

  <script <?php echo 'nonce="' . e(app(\App\Support\CspNonce::class)->value()) . '"'; ?>>
    function genVpsPass() {
      const U = 'ABCDEFGHJKLMNPQRSTUVWXYZ', L = 'abcdefghijkmnpqrstuvwxyz', D = '23456789';
      const all = U + L + D;
      const pick = (s) => s[Math.floor(Math.random() * s.length)];
      let p = [pick(U), pick(L), pick(D)];
      for (let i = 0; i < 9; i++) p.push(pick(all));
      document.getElementById('vpsPass').value = p.sort(() => Math.random() - 0.5).join('');
    }

    (window.LumoraActions = window.LumoraActions || {}).genVpsPass = genVpsPass;
    function salinSsh() {
      const el = document.getElementById('sshCmd');
      if (el) navigator.clipboard.writeText(el.textContent.trim());
    }

    (window.LumoraActions = window.LumoraActions || {}).salinSsh = salinSsh;
    // Versi OS mengikuti OS yang dipilih di form instal ulang.
    (function () {
      const os = document.getElementById('reOs');
      const ver = document.getElementById('reVer');
      if (! os || ! ver) return;

      os.addEventListener('change', function () {
        const raw = os.selectedOptions[0]?.dataset.versions;
        const list = raw ? JSON.parse(raw) : [];

        ver.innerHTML = list.length ? '' : '<option value="">— Tidak ada versi —</option>';
        list.forEach(function (v) {
          const o = document.createElement('option');
          o.value = v;
          o.textContent = v;
          ver.appendChild(o);
        });
      });
    })();
  </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('client.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/themes/client-themes/modern/client/vps/show.blade.php ENDPATH**/ ?>