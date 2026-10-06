<?php $__env->startSection('title', 'Registrar Domain'); ?>

<?php $__env->startSection('content'); ?>

  <?php echo $__env->make('admin.domains._nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

  <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <div>
      <h1 class="h4 fw-bold text-dark mb-1">Registrar Domain</h1>
      <p class="small text-muted mb-0">Kelola koneksi ke Namecheap, Liqu.id, atau ResellBiz untuk registrasi domain otomatis.</p>
    </div>
    <a href="<?php echo e(route('admin.registrars.create')); ?>" class="btn btn-primary btn-sm">
      <i class="fa-solid fa-plus" style="font-size:11px"></i> Tambah Registrar
    </a>
  </div>

  <div class="card border rounded-4 overflow-hidden">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead>
          <tr class="small text-uppercase text-muted" style="background:#f8fafc">
            <th class="px-4 py-3">Nama</th>
            <th class="py-3">Provider</th>
            <th class="py-3">Mode / Endpoint</th>
            <th class="text-center py-3">TLD</th>
            <th class="text-center py-3">Domain</th>
            <th class="py-3">Saldo</th>
            <th class="py-3">Cek Terakhir</th>
            <th class="py-3">Status</th>
            <th class="text-end px-4 py-3">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php $__empty_1 = true; $__currentLoopData = $registrars; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $registrar): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
              <td class="px-4 py-3 fw-medium text-dark">
                <?php echo e($registrar->name); ?>

                <?php if($registrar->is_default): ?>
                  <span class="badge badge-soft-success ms-1">Default</span>
                <?php endif; ?>
              </td>
              <td class="text-muted py-3">
                <?php echo e(['namecheap' => 'Namecheap', 'liquid' => 'Liqu.id', 'resellbiz' => 'ResellBiz', 'dnama' => 'DNAMA'][$registrar->provider] ?? ucfirst($registrar->provider)); ?>

              </td>
              <td class="text-muted py-3" style="font-size:12px">
                <?php if($registrar->provider === 'namecheap'): ?>
                  <?php echo e($registrar->sandbox ? 'Sandbox' : 'Production'); ?>

                <?php else: ?>
                  <?php echo e(\Illuminate\Support\Str::limit(preg_replace('#^https?://#', '', (string) $registrar->api_url), 28) ?: '—'); ?>

                <?php endif; ?>
              </td>
              <td class="text-center text-muted py-3"><?php echo e($registrar->tlds_count); ?></td>
              <td class="text-center text-muted py-3"><?php echo e($registrar->domains_count); ?></td>
              <td class="py-3" style="font-size:12px">
                <?php if(isset($balances[$registrar->id])): ?>
                  <?php if($balances[$registrar->id]): ?>
                    <?php
                      $bal = $balances[$registrar->id];
                      $mataUang = $bal['currency'] ?? 'USD';
                      // Rupiah konvensinya tanpa desimal & pemisah ribuan
                      // titik -- mata uang lain (USD dkk) pakai 2 desimal
                      // seperti sebelumnya. Liqu.id TIDAK PERNAH mengirim
                      // info mata uang di endpoint saldo (dikonfirmasi
                      // dari dashboard mereka: akun ini pakai USD), jadi
                      // default 'USD' di atas khusus menjaga baris Liqu.id
                      // tetap tampil seperti sebelumnya.
                    ?>
                    <?php if($mataUang === 'IDR'): ?>
                      <span class="fw-semibold <?php echo e($bal['balance'] < 50000 ? 'text-danger' : 'text-dark'); ?>">
                        Rp <?php echo e(number_format($bal['balance'], 0, ',', '.')); ?>

                      </span>
                      <?php if($bal['balance'] < 50000): ?>
                        <br><span class="text-danger">Menipis</span>
                      <?php endif; ?>
                    <?php else: ?>
                      <span class="fw-semibold <?php echo e($bal['balance'] < 5 ? 'text-danger' : 'text-dark'); ?>">
                        $<?php echo e(number_format($bal['balance'], 2)); ?>

                      </span>
                      <?php if($bal['balance'] < 5): ?>
                        <br><span class="text-danger">Menipis</span>
                      <?php endif; ?>
                    <?php endif; ?>
                  <?php else: ?>
                    <span class="text-muted">Gagal diambil</span>
                  <?php endif; ?>
                <?php else: ?>
                  <span class="text-muted">—</span>
                <?php endif; ?>
              </td>
              <td class="text-muted py-3" style="font-size:12px">
                <?php if($registrar->last_checked_at): ?>
                  <?php echo e($registrar->last_checked_at->diffForHumans()); ?>

                  <br>
                  <span class="<?php echo e($registrar->last_check_status === 'ok' ? 'text-success' : 'text-danger'); ?>">
                    <?php echo e($registrar->last_check_status === 'ok' ? 'Terhubung' : \Illuminate\Support\Str::limit($registrar->last_check_status, 40)); ?>

                  </span>
                <?php else: ?>
                  Belum pernah dicek
                <?php endif; ?>
              </td>
              <td class="py-3">
                <span class="badge <?php echo e($registrar->is_active ? 'badge-soft-success' : 'badge-soft-secondary'); ?>"><?php echo e($registrar->is_active ? 'Aktif' : 'Nonaktif'); ?></span>
              </td>
              <td class="text-end px-4 py-3">
                <div class="d-flex align-items-center justify-content-end gap-2">
                  <form method="POST" action="<?php echo e(route('admin.registrars.test-connection', $registrar)); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center justify-content-center" style="width:32px;height:32px;padding:0" title="Tes Koneksi">
                      <i class="fa-solid fa-plug" style="font-size:11px"></i>
                    </button>
                  </form>
                  <?php if($supportsSync[$registrar->id] ?? false): ?>
                    <form method="POST" action="<?php echo e(route('admin.registrars.sync-tlds', $registrar)); ?>"
                          data-confirm="Impor daftar TLD dari registrar ini? Harga TLD yang sudah ada tidak akan diubah." data-confirm-title="Sinkronkan TLD" data-confirm-style="info" data-confirm-label="Ya, Impor">
                      <?php echo csrf_field(); ?>
                      <button type="submit" class="btn btn-outline-primary btn-sm d-inline-flex align-items-center justify-content-center" style="width:32px;height:32px;padding:0" title="Sinkronkan daftar TLD">
                        <i class="fa-solid fa-rotate" style="font-size:11px"></i>
                      </button>
                    </form>
                    <a href="<?php echo e(route('admin.registrars.transactions', $registrar)); ?>" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center justify-content-center" style="width:32px;height:32px;padding:0" title="Riwayat Transaksi">
                      <i class="fa-solid fa-receipt" style="font-size:11px"></i>
                    </a>
                    <a href="<?php echo e(route('admin.registrars.diagnostics', $registrar)); ?>" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center justify-content-center" style="width:32px;height:32px;padding:0" title="Diagnosa (mata uang, saldo, format harga)">
                      <i class="fa-solid fa-stethoscope" style="font-size:11px"></i>
                    </a>
                  <?php endif; ?>
                  <?php if($supportsCustomers[$registrar->id] ?? false): ?>
                    <a href="<?php echo e(route('admin.registrars.customers.export', $registrar)); ?>" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center justify-content-center" style="width:32px;height:32px;padding:0" title="Tarik Data Customer (unduh CSV, tidak menyentuh database)">
                      <i class="fa-solid fa-users" style="font-size:11px"></i>
                    </a>
                    <form method="POST" action="<?php echo e(route('admin.registrars.customers.import', $registrar)); ?>"
                          data-confirm="Tarik semua customer dari <?php echo e($registrar->name); ?> dan simpan ke tabel Klien? Email yang SUDAH ADA di database akan dilewati (tidak ditimpa), hanya email baru yang akan dibuat." data-confirm-title="Impor Customer ke Database" data-confirm-style="info" data-confirm-label="Ya, Impor">
                      <?php echo csrf_field(); ?>
                      <button type="submit" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center justify-content-center" style="width:32px;height:32px;padding:0" title="Impor Customer ke Database (untuk pemulihan kalau data hilang)">
                        <i class="fa-solid fa-database" style="font-size:11px"></i>
                      </button>
                    </form>
                  <?php endif; ?>
                  <a href="<?php echo e(route('admin.registrars.edit', $registrar)); ?>" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center justify-content-center" style="width:32px;height:32px;padding:0" title="Edit">
                    <i class="fa-regular fa-pen-to-square" style="font-size:11px"></i>
                  </a>
                  <form method="POST" action="<?php echo e(route('admin.registrars.destroy', $registrar)); ?>" data-confirm="Hapus registrar ini?" data-confirm-title="Hapus Data" data-confirm-style="danger" data-confirm-label="Ya, Hapus">
                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-outline-danger btn-sm d-inline-flex align-items-center justify-content-center" style="width:32px;height:32px;padding:0" title="Hapus">
                      <i class="fa-regular fa-trash-can" style="font-size:11px"></i>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr><td colspan="9" class="text-center text-muted py-5">Belum ada registrar terhubung.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <?php if($registrars->hasPages()): ?>
      <div class="px-4 py-3 border-top"><?php echo e($registrars->links('pagination.bootstrap')); ?></div>
    <?php endif; ?>
  </div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/admin/registrars/index.blade.php ENDPATH**/ ?>