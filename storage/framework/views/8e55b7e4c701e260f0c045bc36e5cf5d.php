<?php $__env->startSection('title', 'Layanan VPS'); ?>

<?php $__env->startSection('content'); ?>

  <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <div>
      <h1 class="h4 fw-bold text-dark mb-1">Layanan VPS</h1>
      <p class="small text-muted mb-0">Semua layanan yang berjalan di provider cloud (VM/VPS), beserta status tagihan per jamnya.</p>
    </div>
    <div class="d-flex align-items-center gap-2">
      <a href="<?php echo e(route('admin.servers.index')); ?>" class="btn btn-outline-secondary btn-sm">
        <i class="fa-solid fa-server" style="font-size:11px"></i> Kelola Server Cloud
      </a>
      <a href="<?php echo e(route('admin.vps.create')); ?>" class="btn btn-primary btn-sm">
        <i class="fa-solid fa-plus" style="font-size:11px"></i> Tambah VPS
      </a>
    </div>
  </div>

  <div class="row g-3 mb-4">
    <?php
      $cards = [
        ['label' => 'Total Layanan VPS', 'value' => $stats['total'], 'icon' => 'fa-desktop', 'fg' => '#4f46e5'],
        ['label' => 'Aktif Berjalan', 'value' => $stats['active'], 'icon' => 'fa-circle-play', 'fg' => '#047857'],
        ['label' => 'Mode Deposit', 'value' => $stats['deposit'], 'icon' => 'fa-wallet', 'fg' => '#b45309'],
        ['label' => 'Estimasi Pendapatan / Jam', 'value' => 'Rp ' . number_format($stats['hourly_revenue'], 2, ',', '.'), 'icon' => 'fa-coins', 'fg' => '#0891b2'],
      ];
    ?>
    <?php $__currentLoopData = $cards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $card): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <div class="col-6 col-lg-3">
        <div class="card border rounded-4 p-3 h-100">
          <i class="fa-solid <?php echo e($card['icon']); ?> mb-2" style="font-size:14px;color:<?php echo e($card['fg']); ?>"></i>
          <p class="fw-bold text-dark mb-0" style="font-size:1.25rem"><?php echo e($card['value']); ?></p>
          <p class="text-muted mb-0" style="font-size:11px"><?php echo e($card['label']); ?></p>
        </div>
      </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </div>

  <div class="card border rounded-4 overflow-hidden">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0" style="font-size:13px">
        <thead>
          <tr class="small text-uppercase text-muted" style="background:#f8fafc">
            <th class="px-4 py-3">Layanan</th>
            <th class="py-3">Klien</th>
            <th class="py-3">Server</th>
            <th class="py-3">Spesifikasi</th>
            <th class="py-3">Mode Tagihan</th>
            <th class="text-end py-3">Tarif / Jam</th>
            <th class="py-3">Saldo Klien</th>
            <th class="py-3">Status</th>
            <th class="text-end px-4 py-3">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php $__empty_1 = true; $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php
              $spec = $account->hasVmSpec() ? $account->vmSpec() : null;
              $rate = $rates[$account->id] ?? null;
              $balance = (float) ($account->client->balance ?? 0);
              $hoursLeft = ($rate && $rate > 0) ? floor($balance / $rate) : null;
            ?>
            <tr>
              <td class="px-4 py-3">
                <span class="fw-medium text-dark"><?php echo e($account->domain); ?></span>
                <p class="text-muted mb-0" style="font-size:10px"><?php echo e($account->serverModel->name ?? ''); ?></p>
                <?php if($account->provision_status === 'failed' && $account->provision_message): ?>
                  <p class="mb-0 mt-1" style="font-size:10px;color:#b91c1c">
                    <i class="fa-solid fa-circle-exclamation"></i> <?php echo e(Str::limit($account->provision_message, 90)); ?>

                  </p>
                <?php endif; ?>
              </td>
              <td class="py-3 text-muted"><?php echo e($account->client->name ?? '—'); ?></td>
              <td class="py-3 text-muted" style="font-size:12px"><?php echo e($account->serverModel->name ?? '—'); ?></td>
              <td class="py-3 text-muted" style="font-size:11px">
                <?php if($spec): ?>
                  <?php echo e($spec['vcpu']); ?> vCPU · <?php echo e($spec['ram']); ?> MB · <?php echo e($spec['disk']); ?> GB
                  <span class="d-block"><?php echo e($spec['os_name']); ?><?php if($spec['backup_enabled']): ?> · backup <?php endif; ?></span>
                <?php else: ?>
                  <span style="color:#b45309">Spek JSON belum diisi</span>
                <?php endif; ?>
              </td>
              <td class="py-3">
                <span class="badge <?php echo e($account->billing_mode === 'deposit' ? 'badge-soft-warning' : 'badge-soft-secondary'); ?>">
                  <?php echo e($account->billing_mode === 'deposit' ? 'Deposit / jam' : 'Invoice'); ?>

                </span>
              </td>
              <td class="text-end py-3 fw-semibold text-dark">
                <?php echo e($rate !== null ? 'Rp ' . number_format($rate, 2, ',', '.') : '—'); ?>

              </td>
              <td class="py-3">
                <span class="<?php echo e($balance <= 0 ? 'text-danger fw-semibold' : 'text-dark'); ?>">
                  Rp <?php echo e(number_format($balance, 0, ',', '.')); ?>

                </span>
                <?php if($hoursLeft !== null && $account->billing_mode === 'deposit'): ?>
                  <span class="d-block text-muted" style="font-size:10px">± <?php echo e($hoursLeft); ?> jam lagi</span>
                <?php endif; ?>
              </td>
              <td class="py-3">
                <span class="badge <?php echo e(['active' => 'badge-soft-success', 'pending' => 'badge-soft-warning', 'suspended' => 'badge-soft-danger'][$account->status] ?? 'badge-soft-secondary'); ?>">
                  <?php echo e(ucfirst($account->status)); ?>

                </span>
              </td>
              <td class="text-end px-4 py-3">
                <div class="d-flex align-items-center justify-content-end gap-2">
                  <?php if($account->provision_status === 'failed'): ?>
                    <button type="button" class="btn btn-primary btn-sm"
                            data-action="toggle" data-target="retry-<?php echo e($account->id); ?>">
                      <i class="fa-solid fa-rotate" style="font-size:11px"></i> Coba Lagi
                    </button>
                  <?php elseif($account->provision_status === 'provisioned' && $account->username): ?>
                    <form method="POST" action="<?php echo e(route('admin.vps.power', $account)); ?>">
                      <?php echo csrf_field(); ?>
                      <input type="hidden" name="action" value="<?php echo e($account->status === 'active' ? 'stop' : 'start'); ?>">
                      <button type="submit" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center justify-content-center"
                              style="width:32px;height:32px;padding:0"
                              title="<?php echo e($account->status === 'active' ? 'Matikan VM' : 'Nyalakan VM'); ?>">
                        <i class="fa-solid <?php echo e($account->status === 'active' ? 'fa-power-off' : 'fa-play'); ?>" style="font-size:11px"></i>
                      </button>
                    </form>
                  <?php endif; ?>

                  <?php if($account->provision_status === 'provisioned' && $account->username): ?>
                    <form method="POST" action="<?php echo e(route('admin.vps.attach-ip', $account)); ?>"
                          data-confirm="Alokasikan & pasang IP publik untuk <?php echo e($account->domain); ?>? IP publik menambah biaya di provider."
                          data-confirm-title="Pasang IP Publik" data-confirm-style="warn" data-confirm-label="Ya, Pasang">
                      <?php echo csrf_field(); ?>
                      <button type="submit" class="btn btn-outline-warning btn-sm d-inline-flex align-items-center justify-content-center"
                              style="width:32px;height:32px;padding:0" title="Pasang IP publik">
                        <i class="fa-solid fa-globe" style="font-size:11px"></i>
                      </button>
                    </form>
                  <?php endif; ?>

                  <?php if($account->serverModel): ?>
                    <a href="<?php echo e(route('admin.servers.diagnostics', $account->serverModel)); ?>"
                       class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center justify-content-center"
                       style="width:32px;height:32px;padding:0" title="Diagnosa server & daftar VM">
                      <i class="fa-solid fa-stethoscope" style="font-size:11px"></i>
                    </a>
                  <?php endif; ?>

                  <button type="button" class="btn btn-outline-danger btn-sm d-inline-flex align-items-center justify-content-center"
                          style="width:32px;height:32px;padding:0" title="Hapus"
                          data-action="toggle" data-target="del-<?php echo e($account->id); ?>">
                    <i class="fa-regular fa-trash-can" style="font-size:11px"></i>
                  </button>
                </div>
              </td>
            </tr>

            
            <tr id="del-<?php echo e($account->id); ?>" class="d-none">
              <td colspan="9" class="px-4 py-3" style="background:#fef2f2">
                <p class="fw-semibold mb-2" style="font-size:13px;color:#b91c1c">
                  Hapus "<?php echo e($account->domain); ?>" — pilih tindakan:
                </p>
                <div class="d-flex gap-2 flex-wrap">
                  <?php if($account->provision_status === 'provisioned'): ?>
                    <form method="POST" action="<?php echo e(route('admin.vps.destroy', $account)); ?>"
                          data-confirm="HAPUS PERMANEN VM <?php echo e($account->domain); ?> beserta seluruh datanya di provider? Tidak bisa dikembalikan."
                          data-confirm-title="Hapus VM Permanen" data-confirm-style="danger" data-confirm-label="Ya, Hapus VM">
                      <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                      <input type="hidden" name="hapus_vm" value="1">
                      <button type="submit" class="btn btn-danger btn-sm">
                        <i class="fa-solid fa-triangle-exclamation" style="font-size:11px"></i> Hapus VM + Catatan
                      </button>
                    </form>
                  <?php endif; ?>
                  <form method="POST" action="<?php echo e(route('admin.vps.destroy', $account)); ?>"
                        data-confirm="Hapus catatan saja? VM di provider TIDAK akan disentuh dan tetap menagih biaya ke akunmu."
                        data-confirm-title="Hapus Catatan Saja" data-confirm-style="warn" data-confirm-label="Ya, Hapus Catatan">
                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                    <input type="hidden" name="hapus_vm" value="0">
                    <button type="submit" class="btn btn-outline-danger btn-sm">Hapus Catatan Saja</button>
                  </form>
                  <button type="button" class="btn btn-outline-secondary btn-sm"
                          data-action="hide" data-target="del-<?php echo e($account->id); ?>">Batal</button>
                </div>
                <p class="text-muted mt-2 mb-0" style="font-size:11px">
                  "Hapus Catatan Saja" dipakai kalau VM sudah dihapus manual di provider — jangan dipakai untuk VM yang masih berjalan.
                </p>
              </td>
            </tr>

            <?php if($account->provision_status === 'failed'): ?>
              <tr id="retry-<?php echo e($account->id); ?>" class="d-none">
                <td colspan="9" class="px-4 py-3" style="background:#fffbeb">
                  <form method="POST" action="<?php echo e(route('admin.vps.retry', $account)); ?>" class="d-flex align-items-end gap-2 flex-wrap">
                    <?php echo csrf_field(); ?>
                    <?php $rs = $account->hasVmSpec() ? $account->vmSpec() : ['vcpu' => 2, 'ram' => 1024, 'disk' => 20]; ?>
                    <div>
                      <label class="form-label small fw-medium text-dark mb-1">vCPU</label>
                      <input type="number" name="vcpu" value="<?php echo e(max(2, $rs['vcpu'])); ?>" min="1" class="form-control form-control-sm" style="width:5rem">
                    </div>
                    <div>
                      <label class="form-label small fw-medium text-dark mb-1">RAM (MB)</label>
                      <input type="number" name="ram" value="<?php echo e($rs['ram']); ?>" step="512" class="form-control form-control-sm" style="width:7rem">
                    </div>
                    <div>
                      <label class="form-label small fw-medium text-dark mb-1">Disk (GB)</label>
                      <input type="number" name="disk" value="<?php echo e($rs['disk']); ?>" class="form-control form-control-sm" style="width:6rem">
                    </div>
                    <div>
                      <label class="form-label small fw-medium text-dark mb-1">Username VM</label>
                      <input type="text" name="username" value="ubuntu" class="form-control form-control-sm" style="width:9rem" required>
                    </div>
                    <div>
                      <label class="form-label small fw-medium text-dark mb-1">Password VM</label>
                      <input type="text" name="password" id="rp-<?php echo e($account->id); ?>" class="form-control form-control-sm" style="width:13rem" required minlength="8">
                    </div>
                    <button type="button" data-action="call" data-call="genPass" data-args="<?php echo e(json_encode(['rp-' . $account->id])); ?>" class="btn btn-outline-secondary btn-sm">
                      <i class="fa-solid fa-dice" style="font-size:11px"></i>
                    </button>
                    <button type="submit" class="btn btn-primary btn-sm">Buat VM Sekarang</button>
                  </form>
                  <p class="text-muted mt-2 mb-0" style="font-size:11px">
                    <i class="fa-solid fa-shield-halved text-success"></i>
                    <b>Aman diklik</b> — kalau VM bernama "<?php echo e($account->domain); ?>" sudah ada di provider,
                    sistem memakai VM itu (tidak membuat baru), dan spek yang tersimpan otomatis disesuaikan
                    dengan mesin aslinya. Spek di atas hanya dipakai kalau VM benar-benar belum ada.
                  </p>
                </td>
              </tr>
            <?php endif; ?>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
              <td colspan="9" class="text-center py-5">
                <p class="text-muted mb-1" style="font-size:14px">Belum ada layanan VPS.</p>
                <p class="text-muted mb-0" style="font-size:11px">
                  Layanan akan muncul di sini setelah ada hosting account yang menunjuk ke server bertipe cloud (mis. IDCloudHost).
                </p>
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
    <?php if($accounts->hasPages()): ?>
      <div class="px-4 py-3 border-top"><?php echo e($accounts->links('pagination.bootstrap')); ?></div>
    <?php endif; ?>
  </div>

  <p class="text-muted mt-3 mb-0" style="font-size:11px">
    <i class="fa-solid fa-circle-info"></i>
    Tarif dihitung otomatis dari kartu harga server × spesifikasi VM. Potongan saldo dijalankan tiap jam lewat cron
    <code>lumora:charge-hourly-usage</code> — cek statusnya di Pengaturan → Cron Jobs.
  </p>

  <script <?php echo 'nonce="' . e(app(\App\Support\CspNonce::class)->value()) . '"'; ?>>
    function genPass(fieldId) {
      const U = 'ABCDEFGHJKLMNPQRSTUVWXYZ', L = 'abcdefghijkmnpqrstuvwxyz', D = '23456789';
      const all = U + L + D;
      const pick = (s) => s[Math.floor(Math.random() * s.length)];
      let p = [pick(U), pick(L), pick(D)];
      for (let i = 0; i < 9; i++) p.push(pick(all));
      document.getElementById(fieldId).value = p.sort(() => Math.random() - 0.5).join('');
    }
    (window.LumoraActions = window.LumoraActions || {}).genPass = genPass;
  </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/admin/vps/index.blade.php ENDPATH**/ ?>