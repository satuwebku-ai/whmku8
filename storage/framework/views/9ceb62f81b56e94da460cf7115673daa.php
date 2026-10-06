<?php $__env->startSection('title', 'Pratinjau Impor Harga'); ?>

<?php $__env->startSection('content'); ?>

  <?php echo $__env->make('admin.domains._nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

  <div class="mb-5">
    <a href="<?php echo e(route('admin.tlds.pricing', isset($registrarId) ? ['registrar' => $registrarId] : [])); ?>" class="text-xs text-slate-400 hover:text-slate-600">
      <i class="fa-solid fa-arrow-left"></i> Kembali ke TLD Pricing
    </a>
    <h1 class="text-xl font-bold text-slate-800 mt-1">Pratinjau Impor Harga</h1>
    <p class="text-sm text-slate-500 mt-1">
      <?php echo e(count($rows)); ?> ekstensi terbaca<?php echo e(!empty($source) ? ' dari ' . $source : ''); ?>.
      Belum ada yang disimpan — periksa dan sesuaikan dulu, lalu klik Terapkan di bawah.
    </p>
  </div>

  <form method="POST" action="<?php echo e(route('admin.tld.import-apply')); ?>">
    <?php echo csrf_field(); ?>
    
    <input type="hidden" name="registrar_id" value="<?php echo e($registrarId ?? ''); ?>">

    
    <div class="card p-4 mb-4 border-indigo-200 bg-indigo-50/40">
      <div class="grid sm:grid-cols-4 gap-3 items-end">
        <div>
          <label class="form-label">Ubah Markup Semua Baris (%)</label>
          <input type="number" step="0.1" min="0" id="massMarkup" value="<?php echo e($markup); ?>" class="form-input">
        </div>
        <div>
          <label class="form-label">Pembulatan (Rp)</label>
          <select id="massRound" class="form-input">
            <option value="0" <?php if($roundTo === 0): echo 'selected'; endif; ?>>Tanpa pembulatan</option>
            <option value="1000" <?php if($roundTo === 1000): echo 'selected'; endif; ?>>Ribuan terdekat</option>
            <option value="5000" <?php if($roundTo === 5000): echo 'selected'; endif; ?>>5 ribu terdekat</option>
            <option value="10000" <?php if($roundTo === 10000): echo 'selected'; endif; ?>>10 ribu terdekat</option>
          </select>
        </div>
        <button type="button" id="applyMarkup" class="btn btn-outline">
          <i class="fa-solid fa-wand-magic-sparkles text-xs"></i> Hitung Ulang Harga Jual
        </button>
        <div class="flex items-center gap-4 text-sm text-slate-600">
          <label class="flex items-center gap-2">
            <input type="checkbox" id="checkAllInclude" checked class="rounded border-slate-300 text-accent focus:ring-accent/40">
            Pilih semua
          </label>
          <label class="flex items-center gap-2">
            <input type="checkbox" id="checkAllActive" class="rounded border-slate-300 text-accent focus:ring-accent/40">
            Aktifkan semua
          </label>
        </div>
      </div>
      <p class="text-[11px] text-slate-500 mt-2">
        "Hitung Ulang" menimpa kolom Harga Jual di semua baris. Kalau ada baris yang sudah kamu
        atur sendiri, aturlah setelah menekan tombol ini.
      </p>
    </div>

    <div class="card overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead>
            <tr class="text-left text-xs text-slate-400 uppercase tracking-wide bg-slate-50">
              <th class="px-4 py-2.5 font-semibold text-center">Impor</th>
              <th class="px-4 py-2.5 font-semibold">Ekstensi</th>
              <th class="px-3 py-2.5 font-semibold">Status</th>
              <th class="px-3 py-2.5 font-semibold text-right">Modal (Rp)</th>
              <th class="px-3 py-2.5 font-semibold text-right">Harga Jual (Rp)</th>
              <th class="px-3 py-2.5 font-semibold text-right">Margin</th>
              <th class="px-3 py-2.5 font-semibold text-center">Aktifkan</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100">
            <?php $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <tr class="hover:bg-slate-50/60" data-row>
                <td class="px-4 py-2 text-center">
                  <input type="checkbox" name="include[]" value="<?php echo e($i); ?>" checked
                         data-include class="rounded border-slate-300 text-accent focus:ring-accent/40">
                </td>

                <td class="px-4 py-2 font-medium text-slate-700 whitespace-nowrap">
                  <?php echo e($row['extension']); ?>

                  <input type="hidden" name="rows[<?php echo e($i); ?>][extension]" value="<?php echo e($row['extension']); ?>">
                </td>

                <td class="px-3 py-2">
                  <?php if($row['exists']): ?>
                    <span class="badge badge-inactive">Sudah ada</span>
                    <?php if($row['old_price'] > 0): ?>
                      <span class="block text-[10px] text-slate-400 mt-0.5">
                        jual lama: Rp <?php echo e(number_format($row['old_price'], 0, ',', '.')); ?>

                      </span>
                    <?php endif; ?>
                  <?php else: ?>
                    <span class="badge badge-active">Baru</span>
                  <?php endif; ?>
                </td>

                <td class="px-3 py-2">
                  <input type="number" step="1" min="0" data-cost
                         name="rows[<?php echo e($i); ?>][cost]" value="<?php echo e((int) $row['cost']); ?>"
                         class="w-32 px-2 py-1.5 rounded-lg border border-slate-200 text-right text-sm outline-none focus:ring-2 focus:ring-accent/40 focus:border-accent">
                </td>

                <td class="px-3 py-2">
                  <input type="number" step="1" min="0" data-selling
                         name="rows[<?php echo e($i); ?>][selling]" value="<?php echo e((int) $row['selling']); ?>"
                         class="w-32 px-2 py-1.5 rounded-lg border border-slate-200 text-right text-sm font-medium outline-none focus:ring-2 focus:ring-accent/40 focus:border-accent">
                </td>

                <td class="px-3 py-2 text-right text-xs whitespace-nowrap" data-margin>—</td>

                <td class="px-3 py-2 text-center">
                  <input type="checkbox" name="active[]" value="<?php echo e($i); ?>" <?php if($row['active']): echo 'checked'; endif; ?>
                         data-active class="rounded border-slate-300 text-accent focus:ring-accent/40">
                </td>
              </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </tbody>
        </table>
      </div>

      <div class="px-5 py-4 border-t border-slate-100 flex items-center justify-between gap-3 flex-wrap bg-slate-50/60">
        <p class="text-xs text-slate-500">
          Hanya baris yang dicentang di kolom <b>Impor</b> yang akan disimpan.
          TLD dengan harga jual 0 tidak akan diaktifkan meski dicentang.
        </p>
        <div class="flex items-center gap-2">
          <a href="<?php echo e(route('admin.tlds.pricing', isset($registrarId) ? ['registrar' => $registrarId] : [])); ?>" class="btn btn-outline">Batal</a>
          <button type="submit" class="btn btn-primary">
            <i class="fa-solid fa-check text-xs"></i> Terapkan ke TLD Pricing
          </button>
        </div>
      </div>
    </div>
  </form>

  <script <?php echo 'nonce="' . e(app(\App\Support\CspNonce::class)->value()) . '"'; ?>>
    (function () {
      const rupiah = new Intl.NumberFormat('id-ID');
      const rows = Array.from(document.querySelectorAll('[data-row]'));

      function updateMargin(row) {
        const cost = parseFloat(row.querySelector('[data-cost]').value) || 0;
        const sell = parseFloat(row.querySelector('[data-selling]').value) || 0;
        const cell = row.querySelector('[data-margin]');

        if (cost <= 0 || sell <= 0) {
          cell.innerHTML = '<span class="text-slate-300">—</span>';
          return;
        }

        const margin = sell - cost;
        const percent = (margin / cost * 100).toFixed(1);
        const tone = margin > 0 ? 'text-emerald-600' : 'text-rose-600';

        cell.innerHTML = '<span class="' + tone + '">Rp ' + rupiah.format(Math.round(margin)) +
                         '<br><span class="text-slate-400">' + percent + '%</span></span>';
      }

      // Hitung ulang harga jual semua baris dari markup yang diketik.
      document.getElementById('applyMarkup').addEventListener('click', function () {
        const markup = parseFloat(document.getElementById('massMarkup').value) || 0;
        const round = parseInt(document.getElementById('massRound').value, 10) || 0;

        rows.forEach(function (row) {
          const cost = parseFloat(row.querySelector('[data-cost]').value) || 0;

          if (cost <= 0) return;

          let sell = cost * (1 + markup / 100);
          if (round > 0) sell = Math.ceil(sell / round) * round;

          row.querySelector('[data-selling]').value = Math.round(sell);
          updateMargin(row);
        });
      });

      function toggleAll(sourceId, selector) {
        document.getElementById(sourceId).addEventListener('change', function () {
          const on = this.checked;
          rows.forEach(function (row) { row.querySelector(selector).checked = on; });
        });
      }

      toggleAll('checkAllInclude', '[data-include]');
      toggleAll('checkAllActive', '[data-active]');

      rows.forEach(function (row) {
        row.querySelectorAll('[data-cost], [data-selling]').forEach(function (input) {
          input.addEventListener('input', function () { updateMargin(row); });
        });
        updateMargin(row);
      });
    })();
  </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/admin/tlds/import-preview.blade.php ENDPATH**/ ?>