<?php $__env->startSection('title', 'Diagnosa Registrar — ' . $registrar->name); ?>

<?php $__env->startSection('content'); ?>

  <a href="<?php echo e(route('admin.registrars.index')); ?>" class="text-decoration-none text-muted" style="font-size:12px">
    <i class="fa-solid fa-arrow-left"></i> Kembali ke Registrar
  </a>
  <h1 class="h4 fw-bold text-dark mt-1 mb-2">Diagnosa — <?php echo e($registrar->name); ?></h1>
  <p class="small text-muted mb-4">
    Data langsung dari API DNAMA — cuma membaca, tidak mengubah apa pun di akunmu.
  </p>

  <?php if(! empty($apiErrors)): ?>
    <?php $__currentLoopData = $apiErrors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $err): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <div class="card border rounded-3 p-3 mb-3" style="border-color:#fecaca!important;background:#fef2f2">
        <p class="mb-0" style="font-size:14px;color:#991b1b"><i class="fa-solid fa-circle-exclamation"></i> <?php echo e($err); ?></p>
      </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  <?php endif; ?>

  <div class="row g-3">

    
    <div class="col-12 col-lg-4">
      <div class="card border rounded-4 p-4 h-100" style="<?php echo e($details && $details['selling_currency'] ? 'border-color:#a7f3d0!important;background:rgba(16,185,129,.04)' : ''); ?>">
        <h2 class="small fw-bold text-dark mb-3">Mata Uang Akun</h2>
        <?php if($details && $details['selling_currency']): ?>
          <p class="fw-bold text-dark mb-2" style="font-size:1.75rem"><?php echo e($details['selling_currency']); ?></p>
          <p class="text-muted mb-0" style="font-size:12px">
            Semua angka harga &amp; saldo dari API DNAMA dalam satuan <b class="text-dark"><?php echo e($details['selling_currency']); ?></b> --
            tidak perlu dikonversi, langsung Rupiah.
          </p>
        <?php else: ?>
          <p class="fw-bold text-dark mb-2" style="font-size:1.75rem">IDR</p>
          <p class="text-muted mb-0" style="font-size:12px">DNAMA selalu memakai Rupiah untuk semua transaksinya.</p>
        <?php endif; ?>
      </div>
    </div>

    
    <div class="col-12 col-lg-4">
      <div class="card border rounded-4 p-4 h-100">
        <h2 class="small fw-bold text-dark mb-3">Saldo Deposit</h2>
        <?php if($balance): ?>
          <p class="fw-bold mb-2 <?php echo e($balance['balance'] < 50000 ? 'text-danger' : 'text-dark'); ?>" style="font-size:1.75rem">
            Rp <?php echo e(number_format($balance['balance'], 0, ',', '.')); ?>

          </p>
          <?php if($balance['balance'] < 50000): ?>
            <p class="text-danger mb-0" style="font-size:12px">
              <i class="fa-solid fa-triangle-exclamation"></i>
              Saldo tipis — pastikan deposit cukup sebelum ada klien yang membeli,
              karena registrasi domain dipotong langsung dari saldo ini.
            </p>
          <?php endif; ?>
        <?php else: ?>
          <p class="text-muted mb-0" style="font-size:14px">Tidak bisa diambil — lihat pesan galat di atas.</p>
        <?php endif; ?>
      </div>
    </div>
  </div>

  
  <div class="card border rounded-4 p-4 mt-3">
    <h2 class="small fw-bold text-dark mb-1">Statistik & Contoh Harga (data mentah)</h2>
    <p class="text-muted mb-3" style="font-size:12px">
      DNAMA mengirim baris TERPISAH untuk varian premium dari ekstensi yang sama
      (mis. ".id" biasa Rp 215rb DAN ".id" premium 2-karakter Rp 585 juta, sama-sama
      bertuliskan "tld": ".id"). Baris premium otomatis dilewati saat sinkronisasi --
      angka di bawah ini menunjukkan persis berapa yang dilewati dan berapa yang benar-benar disinkron.
    </p>

    <?php if(isset($priceStats)): ?>
      <div class="row g-2 mb-3">
        <div class="col-4">
          <div class="rounded-3 border p-2 text-center">
            <p class="fw-bold text-dark mb-0"><?php echo e($priceStats['total']); ?></p>
            <p class="text-muted mb-0" style="font-size:10px">Total baris dari API</p>
          </div>
        </div>
        <div class="col-4">
          <div class="rounded-3 border p-2 text-center" style="background:rgba(180,83,9,.06)">
            <p class="fw-bold mb-0" style="color:#b45309"><?php echo e($priceStats['premium']); ?></p>
            <p class="text-muted mb-0" style="font-size:10px">Baris premium (dilewati)</p>
          </div>
        </div>
        <div class="col-4">
          <div class="rounded-3 border p-2 text-center" style="background:rgba(16,185,129,.06)">
            <p class="fw-bold mb-0" style="color:#047857"><?php echo e($priceStats['unique']); ?></p>
            <p class="text-muted mb-0" style="font-size:10px">Ekstensi unik (yang disinkron)</p>
          </div>
        </div>
      </div>
      <p class="text-muted mb-3" style="font-size:11px">
        Angka "Ekstensi unik" inilah jumlah TLD yang akan masuk saat Sinkron TLD —
        kalau jauh lebih sedikit dari harapan, berarti akun reseller-mu di DNAMA
        memang baru diberi akses sebanyak itu (bukan masalah di sistem ini).
      </p>
    <?php endif; ?>

    
    <?php
      $parsed = collect(is_array($priceSample) ? $priceSample : [])->map(function ($row) {
        $satu = collect($row['pricings'] ?? [])->firstWhere('duration', 1);
        return [
          'tld' => $row['tld'] ?? '—',
          'premium' => ! empty($row['is_premium']),
          'chars' => $row['max_premium_character'] ?? null,
          'register' => $satu['register_price'] ?? null,
          'renew' => $satu['renewal_price'] ?? null,
          'transfer' => $satu['transfer_price'] ?? null,
          'restore' => $satu['restore_price'] ?? null,
          'durasi' => count($row['pricings'] ?? []),
        ];
      });
    ?>

    <?php if($parsed->isNotEmpty()): ?>
      <div class="table-responsive mb-3">
        <table class="table table-sm align-middle mb-0" style="font-size:12px">
          <thead>
            <tr class="text-uppercase text-muted" style="background:#f8fafc;font-size:10px">
              <th class="py-2">TLD</th>
              <th class="text-end py-2">Register (1thn)</th>
              <th class="text-end py-2">Renew</th>
              <th class="text-end py-2">Transfer</th>
              <th class="text-end py-2">Restore</th>
              <th class="text-center py-2">Durasi</th>
              <th class="py-2">Catatan</th>
            </tr>
          </thead>
          <tbody>
            <?php $__currentLoopData = $parsed; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <tr style="<?php echo e($row['premium'] ? 'background:rgba(180,83,9,.05)' : ''); ?>">
                <td class="py-2 fw-medium text-dark"><?php echo e($row['tld']); ?></td>
                <td class="text-end py-2"><?php echo e($row['register'] !== null ? 'Rp ' . number_format($row['register'], 0, ',', '.') : '—'); ?></td>
                <td class="text-end py-2"><?php echo e($row['renew'] !== null ? 'Rp ' . number_format($row['renew'], 0, ',', '.') : '—'); ?></td>
                <td class="text-end py-2"><?php echo e($row['transfer'] !== null ? 'Rp ' . number_format($row['transfer'], 0, ',', '.') : '—'); ?></td>
                <td class="text-end py-2 text-muted"><?php echo e($row['restore'] !== null ? 'Rp ' . number_format($row['restore'], 0, ',', '.') : '—'); ?></td>
                <td class="text-center py-2 text-muted"><?php echo e($row['durasi']); ?></td>
                <td class="py-2">
                  <?php if($row['premium']): ?>
                    <span class="badge" style="font-size:9px;background:#fef3c7;color:#b45309">
                      Premium<?php echo e($row['chars'] ? ' ' . $row['chars'] . ' karakter' : ''); ?> — dilewati
                    </span>
                  <?php else: ?>
                    <span class="text-muted" style="font-size:10px">disinkron</span>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </tbody>
        </table>
      </div>
      <p class="text-muted mb-2" style="font-size:11px">
        Ini 3 baris pertama saja. Baris premium (latar oranye) sengaja dilewati saat sinkronisasi —
        harganya bisa ratusan juta dan cuma berlaku untuk domain super-pendek.
      </p>
    <?php endif; ?>

    <details>
      <summary class="text-muted" style="font-size:11px;cursor:pointer">Lihat JSON mentah</summary>
      <pre class="rounded-3 p-3 mt-2 mb-0" style="background:#1e293b;color:#f1f5f9;font-size:11px;overflow-x:auto;max-height:24rem"><?php echo e(json_encode($priceSample, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)); ?></pre>
    </details>
  </div>

  
  <?php if($supportsCustomerLookup ?? false): ?>
    <div class="card border rounded-4 p-4 mt-3">
      <h2 class="small fw-bold text-dark mb-1">Cari Customer</h2>
      <p class="text-muted mb-3" style="font-size:12px">
        DNAMA tidak menyediakan endpoint "daftar semua customer", tapi customer bisa dicari
        satu per satu lewat username-nya (biasanya alamat email klien).
      </p>

      <form method="GET" class="d-flex gap-2 mb-3" style="max-width:28rem">
        <input type="text" name="customer" value="<?php echo e($customerLookup['query'] ?? ''); ?>"
               placeholder="username / email customer" class="form-control form-control-sm">
        <button type="submit" class="btn btn-outline-secondary btn-sm">Cari</button>
      </form>

      <?php if($customerLookup): ?>
        <?php if($customerLookup['found'] && $customerLookup['data']): ?>
          <?php $c = $customerLookup['data']; ?>
          <div class="rounded-3 p-3" style="background:rgba(16,185,129,.05);border:1px solid #a7f3d0">
            <p class="fw-medium text-dark mb-1" style="font-size:14px"><?php echo e($c['name'] ?? '(tanpa nama)'); ?></p>
            <p class="text-muted mb-2" style="font-size:12px">
              <?php echo e($c['email'] ?? '—'); ?>

              <?php if(! empty($c['company_name'])): ?> &middot; <?php echo e($c['company_name']); ?> <?php endif; ?>
            </p>
            <div class="text-muted" style="font-size:11px">
              <?php if(! empty($c['address_1'])): ?>
                <p class="mb-0"><?php echo e(collect([$c['address_1'] ?? null, $c['address_2'] ?? null, $c['address_3'] ?? null])->filter()->implode(', ')); ?></p>
              <?php endif; ?>
              <p class="mb-0">
                <?php echo e(collect([$c['city'] ?? null, $c['province'] ?? null, $c['postal_code'] ?? null, $c['country'] ?? null])->filter()->implode(' · ')); ?>

              </p>
              <?php if(! empty($c['phone_number'])): ?>
                <p class="mb-0">Telp: <?php echo e($c['phone_number']); ?><?php if(! empty($c['mobile_phone_number']) && $c['mobile_phone_number'] !== $c['phone_number']): ?> &middot; HP: <?php echo e($c['mobile_phone_number']); ?> <?php endif; ?></p>
              <?php endif; ?>
            </div>
          </div>
        <?php else: ?>
          <div class="rounded-3 p-3" style="background:#fef2f2;border:1px solid #fecaca">
            <p class="mb-0" style="font-size:13px;color:#991b1b">
              <i class="fa-solid fa-circle-exclamation"></i>
              Customer "<?php echo e($customerLookup['query']); ?>" tidak ditemukan.
              <?php if($customerLookup['message']): ?>
                <span class="d-block text-muted mt-1" style="font-size:11px"><?php echo e($customerLookup['message']); ?></span>
              <?php endif; ?>
            </p>
          </div>
        <?php endif; ?>
      <?php endif; ?>
    </div>
  <?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/admin/registrars/diagnostics-dnama.blade.php ENDPATH**/ ?>