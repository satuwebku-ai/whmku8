<?php $__env->startSection('title', $tld->exists ? 'Edit TLD' : 'Tambah TLD'); ?>

<?php $__env->startSection('content'); ?>

  <div class="mb-4">
    <a href="<?php echo e(route('admin.tlds.index')); ?>" class="text-decoration-none text-muted" style="font-size:12px"><i class="fa-solid fa-arrow-left"></i> Kembali ke TLD Pricing</a>
    <h1 class="h4 fw-bold text-dark mt-1 mb-0"><?php echo e($tld->exists ? 'Edit TLD' : 'Tambah TLD'); ?></h1>
  </div>

  <form method="POST" action="<?php echo e($tld->exists ? route('admin.tlds.update', $tld) : route('admin.tlds.store')); ?>" class="card border rounded-4 p-4" style="max-width:48rem">
    <?php echo csrf_field(); ?>
    <?php if($tld->exists): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>

    <div class="row g-3 mb-3">
      <div class="col-sm-6">
        <label class="form-label small fw-medium text-dark">Ekstensi</label>
        <input type="text" name="extension" value="<?php echo e(old('extension', $tld->extension)); ?>" placeholder=".com" class="form-control form-control-sm" required>
        <?php $__errorArgs = ['extension'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      </div>
      <div class="col-sm-6">
        <label class="form-label small fw-medium text-dark">Registrar (opsional)</label>
        <select name="registrar_id" class="form-select form-select-sm">
          <option value="">— Tidak ditentukan —</option>
          <?php $__currentLoopData = $registrars; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($r->id); ?>" <?php if(old('registrar_id', $tld->registrar_id) == $r->id): echo 'selected'; endif; ?>><?php echo e($r->name); ?></option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
      </div>
    </div>

    <?php if($tld->exists && $tld->hasCost()): ?>
      <div class="rounded-3 px-3 py-2 mb-3" style="background:#f8fafc;border:1px solid #e2e8f0;font-size:12px;color:#475569">
        <b>Harga modal dari registrar:</b>
        Register Rp <?php echo e(number_format($tld->cost_register, 0, ',', '.')); ?> ·
        Renew Rp <?php echo e(number_format($tld->cost_renew, 0, ',', '.')); ?> ·
        Transfer Rp <?php echo e(number_format($tld->cost_transfer, 0, ',', '.')); ?>

        <?php if($tld->cost_synced_at): ?>
          <span class="text-muted">(disinkronkan <?php echo e($tld->cost_synced_at->diffForHumans()); ?>)</span>
        <?php endif; ?>
        <br>Harga modal diperbarui otomatis saat sinkronisasi, jadi tidak bisa diedit manual di sini.
      </div>
    <?php endif; ?>

    <div class="row g-3 mb-3">
      <div class="col-sm-4">
        <label class="form-label small fw-medium text-dark">Harga Register</label>
        <input type="number" step="0.01" name="register_price" value="<?php echo e(old('register_price', $tld->register_price)); ?>" class="form-control form-control-sm" required>
      </div>
      <div class="col-sm-4">
        <label class="form-label small fw-medium text-dark">Harga Renew</label>
        <input type="number" step="0.01" name="renew_price" value="<?php echo e(old('renew_price', $tld->renew_price)); ?>" class="form-control form-control-sm" required>
      </div>
      <div class="col-sm-4">
        <label class="form-label small fw-medium text-dark">Harga Transfer</label>
        <input type="number" step="0.01" name="transfer_price" value="<?php echo e(old('transfer_price', $tld->transfer_price)); ?>" class="form-control form-control-sm" required>
      </div>
    </div>

    <div class="row g-3 mb-3">
      <div class="col-sm-6">
        <label class="form-label small fw-medium text-dark">Min. Tahun</label>
        <input type="number" name="min_years" value="<?php echo e(old('min_years', $tld->min_years ?? 1)); ?>" class="form-control form-control-sm" required>
      </div>
      <div class="col-sm-6">
        <label class="form-label small fw-medium text-dark">Maks. Tahun</label>
        <input type="number" name="max_years" value="<?php echo e(old('max_years', $tld->max_years ?? 10)); ?>" class="form-control form-control-sm" required>
      </div>
    </div>

    
    <div class="pt-3 border-top mb-3">
      <h2 class="small fw-bold text-dark mb-1">Harga per Durasi</h2>
      <p class="text-muted mb-3" style="font-size:12px">
        Kosongkan untuk memakai perhitungan otomatis (harga 1 tahun × jumlah tahun).
        Isi hanya kalau ingin memberi diskon untuk pembelian jangka panjang.
      </p>

      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" style="font-size:13px">
          <thead>
            <tr class="small text-uppercase text-muted" style="background:#f8fafc">
              <th class="py-2">Durasi</th>
              <th class="text-end py-2">Harga Register (Rp)</th>
              <th class="text-end py-2">Harga Renew (Rp)</th>
              <th class="text-end py-2">Otomatis</th>
            </tr>
          </thead>
          <tbody>
            <?php
              $maxYears = (int) old('max_years', $tld->max_years ?? 10);
              $yearReg = old('year_prices', $tld->year_prices ?? []);
              $yearRen = old('year_renew_prices', $tld->year_renew_prices ?? []);
            ?>

            <?php for($y = 1; $y <= max($maxYears, 1); $y++): ?>
              <tr>
                <td class="py-2 fw-medium text-dark"><?php echo e($y); ?> tahun</td>
                <td class="text-end py-2">
                  <?php if($y === 1): ?>
                    <span class="text-muted" style="font-size:11px">pakai Harga Register di atas</span>
                  <?php else: ?>
                    <input type="number" step="1" min="0" name="year_prices[<?php echo e($y); ?>]"
                           value="<?php echo e($yearReg[$y] ?? $yearReg[(string) $y] ?? ''); ?>"
                           placeholder="<?php echo e(number_format((float) old('register_price', $tld->register_price ?? 0) * $y, 0, ',', '')); ?>"
                           class="form-control form-control-sm text-end" style="width:8rem;display:inline-block">
                  <?php endif; ?>
                </td>
                <td class="text-end py-2">
                  <?php if($y === 1): ?>
                    <span class="text-muted" style="font-size:11px">pakai Harga Renew di atas</span>
                  <?php else: ?>
                    <input type="number" step="1" min="0" name="year_renew_prices[<?php echo e($y); ?>]"
                           value="<?php echo e($yearRen[$y] ?? $yearRen[(string) $y] ?? ''); ?>"
                           placeholder="<?php echo e(number_format((float) old('renew_price', $tld->renew_price ?? 0) * $y, 0, ',', '')); ?>"
                           class="form-control form-control-sm text-end" style="width:8rem;display:inline-block">
                  <?php endif; ?>
                </td>
                <td class="text-end py-2 text-muted" style="font-size:11px">
                  Rp <?php echo e(number_format((float) old('register_price', $tld->register_price ?? 0) * $y, 0, ',', '.')); ?>

                </td>
              </tr>
            <?php endfor; ?>
          </tbody>
        </table>
      </div>
      <p class="text-muted mt-2 mb-0" style="font-size:11px">
        Angka abu-abu di kolom terakhir adalah harga otomatis. Kolom isian menampilkannya
        sebagai placeholder — kalau dibiarkan kosong, itulah yang dipakai.
      </p>
    </div>

    <label class="d-flex align-items-center gap-2 small text-dark mb-3">
      <input type="checkbox" name="is_active" value="1" <?php if(old('is_active', $tld->is_active ?? true)): echo 'checked'; endif; ?> class="form-check-input" style="margin-top:0">
      Aktif (tampil di halaman Cek Domain)
    </label>

    <div class="d-flex align-items-center gap-2 pt-2 border-top">
      <button type="submit" class="btn btn-primary btn-sm mt-2"><i class="fa-solid fa-check" style="font-size:11px"></i> Simpan</button>
      <a href="<?php echo e(route('admin.tlds.index')); ?>" class="btn btn-outline-secondary btn-sm mt-2">Batal</a>
    </div>
  </form>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/admin/tlds/form.blade.php ENDPATH**/ ?>