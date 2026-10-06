<?php $__env->startSection('title', 'TLD Pricing'); ?>

<?php $__env->startSection('content'); ?>

  <?php echo $__env->make('admin.domains._nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

  <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <div>
      <h1 class="h4 fw-bold text-dark mb-1">TLD Pricing</h1>
      <p class="small text-muted mb-0">
        Pilih registrar dulu, baru atur harga jualnya -- sejak satu ekstensi (mis. ".com") bisa
        dimiliki beberapa registrar sekaligus, harga per registrar diatur terpisah.
      </p>
    </div>
    <div class="d-flex align-items-center gap-2">
      <?php if($selected): ?>
        <button type="button" data-action="toggle" data-target="importPanel" class="btn btn-outline-secondary btn-sm">
          <i class="fa-solid fa-cloud-arrow-down" style="font-size:11px"></i> Tarik Harga Registrar
        </button>
        <button type="button" data-action="toggle" data-target="markupPanel" class="btn btn-outline-secondary btn-sm">
          <i class="fa-solid fa-percent" style="font-size:11px"></i> Markup Massal
        </button>
      <?php endif; ?>
      <a href="<?php echo e(route('admin.tlds.create')); ?>" class="btn btn-primary btn-sm">
        <i class="fa-solid fa-plus" style="font-size:11px"></i> Tambah TLD
      </a>
    </div>
  </div>

  
  <div class="d-flex flex-wrap gap-2 mb-4">
    <?php $__currentLoopData = $registrars; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <a href="<?php echo e(route('admin.tlds.pricing', ['registrar' => $r->id])); ?>"
         class="d-flex align-items-center gap-2 px-3 py-2 rounded-3 text-decoration-none border <?php echo e(is_object($selected) && $selected->id === $r->id ? 'border-primary' : ''); ?>"
         style="font-size:13px;<?php echo e(is_object($selected) && $selected->id === $r->id ? 'background:rgba(79,70,229,.06);color:#4338ca;border-color:#4f46e5!important' : 'color:#334155'); ?>">
        <i class="fa-solid fa-server" style="font-size:11px"></i>
        <?php echo e($r->name); ?>

        <span class="badge badge-soft-secondary" style="font-size:10px"><?php echo e($r->tlds_count); ?></span>
      </a>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <a href="<?php echo e(route('admin.tlds.pricing', ['registrar' => 'none'])); ?>"
       class="d-flex align-items-center gap-2 px-3 py-2 rounded-3 text-decoration-none border <?php echo e($selected === 'none' ? 'border-primary' : ''); ?>"
       style="font-size:13px;<?php echo e($selected === 'none' ? 'background:rgba(79,70,229,.06);color:#4338ca;border-color:#4f46e5!important' : 'color:#334155'); ?>">
      <i class="fa-solid fa-user-slash" style="font-size:11px"></i> Manual (Tidak Ditentukan)
    </a>
  </div>

  <?php if($selected): ?>
    
    <?php if(is_object($selected)): ?>
      <div id="importPanel" class="d-none card border rounded-4 p-4 mb-4" style="border-color:#a7f3d0!important;background:rgba(16,185,129,.04)">
        <h2 class="small fw-bold text-dark mb-1">Tarik Harga Modal dari <?php echo e($selected->name); ?></h2>
        <p class="text-muted mb-3" style="font-size:12px">
          Mengambil harga modal langsung lewat API registrar, lalu menampilkannya sebagai
          <b>tabel pratinjau</b> — di sana harga bisa diperiksa dan disesuaikan satu per satu
          sebelum benar-benar disimpan. Tidak ada yang tersimpan sampai kamu menekan Terapkan.
        </p>

        <form method="POST" action="<?php echo e(route('admin.tld.sync-preview')); ?>">
          <?php echo csrf_field(); ?>
          <input type="hidden" name="registrar_id" value="<?php echo e($selected->id); ?>">
          <div class="row g-3 align-items-end">
            <div class="col-sm-4">
              <label class="form-label small fw-medium text-dark">Markup Awal (%)</label>
              <input type="number" step="0.1" min="0" name="markup" value="30" class="form-control form-control-sm">
              <p class="text-muted mt-1 mb-0" style="font-size:11px">Masih bisa diubah di pratinjau.</p>
            </div>
            <div class="col-sm-4">
              <label class="form-label small fw-medium text-dark">Pembulatan</label>
              <select name="round_to" class="form-select form-select-sm">
                <option value="1000" selected>Ribuan</option>
                <option value="0">Tanpa</option>
                <option value="5000">5 ribu</option>
                <option value="10000">10 ribu</option>
              </select>
            </div>
            <div class="col-sm-4">
              <label class="d-flex align-items-center gap-2 small text-dark mb-2">
                <input type="checkbox" name="only_sellable" value="1" checked class="form-check-input" style="margin-top:0">
                Hanya "Sell"
              </label>
              <button type="submit" class="btn btn-primary btn-sm w-100">
                <i class="fa-solid fa-cloud-arrow-down" style="font-size:11px"></i> Tarik &amp; Pratinjau
              </button>
            </div>
          </div>

          <p class="text-muted mt-2 mb-0" style="font-size:11px">
            "Hanya yang berstatus Sell" menyaring TLD yang sudah kamu aktifkan untuk dijual di panel
            registrar — biasanya jauh lebih sedikit dari total TLD yang tersedia.
          </p>
        </form>
      </div>
    <?php endif; ?>

    <div id="markupPanel" class="d-none card border rounded-4 p-4 mb-4" style="border-color:#c7d2fe!important;background:rgba(79,70,229,.04)">
      <h2 class="small fw-bold text-dark mb-1">Tentukan Harga Jual dari Margin</h2>
      <p class="text-muted mb-3" style="font-size:12px">
        Harga jual dihitung dari <b>harga modal</b>, jadi aman dijalankan berulang kali.
        Cuma berlaku untuk TLD milik <b><?php echo e(is_object($selected) ? $selected->name : 'Manual (Tidak Ditentukan)'); ?></b>.
      </p>

      <form method="POST" action="<?php echo e(route('admin.tld.bulk-markup')); ?>" id="markupForm"
            data-confirm="Terapkan margin ini? Harga jual yang ada akan ditimpa."
            data-confirm-title="Terapkan Margin" data-confirm-style="warn" data-confirm-label="Ya, Terapkan">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="search" value="<?php echo e(request('search')); ?>">
        <input type="hidden" name="selected_ids" id="selectedIds">
        
        <input type="hidden" name="registrar_scope" value="<?php echo e(is_object($selected) ? $selected->id : 'none'); ?>">

        <div class="d-flex align-items-center gap-4 mb-3" style="font-size:14px">
          <span class="fw-medium text-dark">Hitung profit dalam:</span>
          <label class="d-flex align-items-center gap-2 text-muted mb-0">
            <input type="radio" name="profit_type" value="percent" checked style="margin:0">
            Persen (%)
          </label>
          <label class="d-flex align-items-center gap-2 text-muted mb-0">
            <input type="radio" name="profit_type" value="fixed" style="margin:0">
            Rupiah tetap (Rp)
          </label>
        </div>

        <div class="row g-3 mb-3">
          <div class="col-sm-4">
            <label class="form-label small fw-medium text-dark">Margin Register</label>
            <input type="number" step="0.01" min="0" name="margin_register" value="30" class="form-control form-control-sm" required>
          </div>
          <div class="col-sm-4">
            <label class="form-label small fw-medium text-dark">Margin Renew <span class="text-muted fw-normal">(opsional)</span></label>
            <input type="number" step="0.01" min="0" name="margin_renew" class="form-control form-control-sm" placeholder="ikut Register">
          </div>
          <div class="col-sm-4">
            <label class="form-label small fw-medium text-dark">Margin Transfer <span class="text-muted fw-normal">(opsional)</span></label>
            <input type="number" step="0.01" min="0" name="margin_transfer" class="form-control form-control-sm" placeholder="ikut Register">
          </div>
        </div>

        <div class="row g-3 mb-3 pt-3 border-top align-items-end">
          <div class="col-sm-4">
            <label class="form-label small fw-medium text-dark">Pembulatan</label>
            <select name="round_mode" id="roundMode" class="form-select form-select-sm">
              <option value="multiple" selected>Bulatkan ke kelipatan</option>
              <option value="ending">Akhiri dengan angka tertentu</option>
              <option value="none">Tanpa pembulatan</option>
            </select>
          </div>
          <div class="col-sm-4" data-round-field>
            <label class="form-label small fw-medium text-dark">Kelipatan (Rp)</label>
            <select name="round_step" class="form-select form-select-sm">
              <option value="1000" selected>1.000</option>
              <option value="5000">5.000</option>
              <option value="10000">10.000</option>
              <option value="50000">50.000</option>
            </select>
          </div>
          <div class="col-sm-4 d-none" data-round-field data-tail>
            <label class="form-label small fw-medium text-dark">Akhiran (Rp)</label>
            <input type="number" name="round_tail" value="9000" min="0" class="form-control form-control-sm">
            <p class="text-muted mt-1 mb-0" style="font-size:11px">Mis. kelipatan 10.000 + akhiran 9.000 → 219.000</p>
          </div>
        </div>

        <div class="row g-3 pt-3 border-top align-items-end">
          <div class="col-sm-4">
            <label class="form-label small fw-medium text-dark">Terapkan ke</label>
            <select name="scope" class="form-select form-select-sm">
              <option value="all">Semua TLD registrar ini</option>
              <option value="filtered" <?php if(request('search')): echo 'selected'; endif; ?>>Hasil pencarian "<?php echo e(request('search') ?: '—'); ?>"</option>
            </select>
          </div>
          <div class="col-sm-4 d-flex flex-column gap-1">
            <label class="d-flex align-items-center gap-2 text-muted mb-0" style="font-size:14px">
              <input type="checkbox" name="only_empty" value="1" style="margin:0">
              Hanya yang belum berharga
            </label>
            
            <p class="text-muted mb-0" style="font-size:11px">
              Aktifkan TLD lewat <a href="<?php echo e(route('admin.tlds.index')); ?>" class="text-accent">Status TLD</a>.
            </p>
          </div>
          <div class="col-sm-4 text-sm-end">
            <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-check" style="font-size:11px"></i> Terapkan Margin</button>
          </div>
        </div>
      </form>
    </div>
  <?php endif; ?>

  <?php if(! $selected): ?>
    <div class="card border rounded-4 p-5 text-center">
      <i class="fa-solid fa-hand-pointer text-muted mb-3" style="font-size:1.75rem"></i>
      <p class="fw-medium text-dark mb-1">Pilih registrar di atas untuk mulai atur harga</p>
      <p class="text-muted mb-0" style="font-size:13px">
        Belum ada registrar? Tambahkan dulu lewat <a href="<?php echo e(route('admin.registrars.create')); ?>" class="text-accent">Registrar &rarr; Tambah Registrar</a>.
      </p>
    </div>
  <?php else: ?>
    <form method="GET" class="d-flex flex-wrap gap-2 mb-3">
      <input type="hidden" name="registrar" value="<?php echo e(is_object($selected) ? $selected->id : 'none'); ?>">
      <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari ekstensi, mis. .com" class="form-control form-control-sm" style="max-width:14rem">
      <button type="submit" class="btn btn-outline-secondary btn-sm">Cari</button>
      <?php if(request('search')): ?>
        <a href="<?php echo e(route('admin.tlds.pricing', ['registrar' => is_object($selected) ? $selected->id : 'none'])); ?>" class="btn btn-outline-secondary btn-sm">Reset</a>
      <?php endif; ?>
    </form>

    <form method="POST" action="<?php echo e(route('admin.tld.update-pricing')); ?>" id="pricingForm">
      <?php echo csrf_field(); ?>
      <input type="hidden" name="registrar_id" value="<?php echo e(is_object($selected) ? $selected->id : 'none'); ?>">

      <div class="card border rounded-4 overflow-hidden">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0" style="font-size:13px">
            <thead>
              <tr class="small text-uppercase text-muted" style="background:#f8fafc">
                <th class="py-3 px-3">Ekstensi</th>
                <th class="text-end py-3">Modal Register</th>
                <th class="text-end py-3">Harga Register</th>
                <th class="text-end py-3">Modal Renew</th>
                <th class="text-end py-3">Harga Renew</th>
                <th class="text-end py-3">Modal Transfer</th>
                <th class="text-end py-3">Harga Transfer</th>
                <th class="text-end py-3">Margin</th>
                <th class="text-center py-3">1-10 Thn</th>
              </tr>
            </thead>
            <tbody>
              <?php $__empty_1 = true; $__currentLoopData = $tlds; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tld): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr data-row>
                  <td class="py-2 px-3 fw-medium text-dark text-nowrap">
                    <?php echo e($tld->extension); ?>

                    <?php if($tld->is_demo): ?>
                      <span class="badge" style="font-size:9px;background:#fef3c7;color:#b45309">DEMO</span>
                    <?php endif; ?>
                    <?php if(! $tld->is_active): ?>
                      <span class="badge badge-soft-secondary" style="font-size:9px">Nonaktif</span>
                    <?php endif; ?>
                  </td>

                  <td class="py-2">
                    <input type="number" step="1" min="0" data-cost
                           name="rows[<?php echo e($tld->id); ?>][cost_register]"
                           value="<?php echo e((int) $tld->cost_register ?: ''); ?>" placeholder="0"
                           class="form-control form-control-sm text-end" style="width:7rem">
                  </td>
                  <td class="py-2">
                    <input type="number" step="1" min="0" data-register
                           name="rows[<?php echo e($tld->id); ?>][register_price]"
                           value="<?php echo e((int) $tld->register_price ?: ''); ?>" placeholder="0"
                           class="form-control form-control-sm text-end" style="width:7rem;<?php echo e($tld->register_price > 0 ? '' : 'border-color:#fca5a5;background:#fef2f2'); ?>">
                  </td>

                  <td class="py-2">
                    <input type="number" step="1" min="0"
                           name="rows[<?php echo e($tld->id); ?>][cost_renew]"
                           value="<?php echo e((int) $tld->cost_renew ?: ''); ?>" placeholder="0"
                           class="form-control form-control-sm text-end" style="width:7rem">
                  </td>
                  <td class="py-2">
                    <input type="number" step="1" min="0"
                           name="rows[<?php echo e($tld->id); ?>][renew_price]"
                           value="<?php echo e((int) $tld->renew_price ?: ''); ?>" placeholder="0"
                           class="form-control form-control-sm text-end" style="width:7rem">
                  </td>

                  <td class="py-2">
                    <input type="number" step="1" min="0"
                           name="rows[<?php echo e($tld->id); ?>][cost_transfer]"
                           value="<?php echo e((int) $tld->cost_transfer ?: ''); ?>" placeholder="0"
                           class="form-control form-control-sm text-end" style="width:7rem">
                  </td>
                  <td class="py-2">
                    <input type="number" step="1" min="0"
                           name="rows[<?php echo e($tld->id); ?>][transfer_price]"
                           value="<?php echo e((int) $tld->transfer_price ?: ''); ?>" placeholder="0"
                           class="form-control form-control-sm text-end" style="width:7rem">
                  </td>

                  <td class="text-end py-2 text-nowrap" data-margin>
                    <span class="text-muted">—</span>
                  </td>

                  <td class="text-center py-2">
                    <?php
                      $isiTahun = collect($tld->year_prices ?: [])->filter(fn ($v) => (float) $v > 0)->count();
                      $adaModalTahun = collect($tld->cost_year_prices ?: [])->filter(fn ($v) => (float) $v > 0)->count();
                    ?>
                    <button type="button" class="btn btn-sm year-price-btn <?php echo e($isiTahun > 0 ? 'btn-outline-primary' : 'btn-outline-secondary'); ?>"
                            data-tld-id="<?php echo e($tld->id); ?>"
                            data-extension="<?php echo e($tld->extension); ?>"
                            data-max-years="<?php echo e($tld->max_years); ?>"
                            data-year-prices='<?php echo json_encode($tld->year_prices ?: [], 15, 512) ?>'
                            data-year-renew-prices='<?php echo json_encode($tld->year_renew_prices ?: [], 15, 512) ?>'
                            data-cost-year-prices='<?php echo json_encode($tld->cost_year_prices ?: [], 15, 512) ?>'
                            title="<?php echo e($isiTahun > 0 ? $isiTahun . ' durasi sudah punya harga sendiri' : ($adaModalTahun > 0 ? 'Belum diisi — jalankan Markup Massal atau isi manual' : 'Registrar ini tidak menyediakan harga modal per tahun')); ?>">
                      <i class="fa-regular fa-calendar" style="font-size:11px"></i>
                      <?php if($isiTahun > 0): ?>
                        <span style="font-size:10px"><?php echo e($isiTahun); ?></span>
                      <?php elseif($adaModalTahun === 0): ?>
                        <span class="text-muted" style="font-size:10px">—</span>
                      <?php endif; ?>
                    </button>
                  </td>
                </tr>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                  <td colspan="9" class="text-center py-5">
                    <p class="text-muted mb-1" style="font-size:14px">Belum ada TLD untuk registrar ini.</p>
                    <p class="text-muted mb-0" style="font-size:11px">
                      Buka <a href="<?php echo e(route('admin.registrars.index')); ?>" class="text-accent">Registrar</a>
                      lalu klik ikon <i class="fa-solid fa-rotate" style="font-size:10px"></i> (Sinkronkan daftar TLD).
                    </p>
                  </td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

        <?php if($tlds->isNotEmpty()): ?>
          <div class="px-4 py-3 border-top d-flex align-items-center justify-content-between flex-wrap gap-2">
            <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-check" style="font-size:11px"></i> Simpan Harga</button>
            <?php echo e($tlds->links('pagination.bootstrap')); ?>

          </div>
        <?php endif; ?>
      </div>
    </form>

    
    <div id="yearPriceModal" class="d-none" style="position:fixed;inset:0;background:rgba(15,23,42,.5);z-index:1050;align-items:center;justify-content:center">
      <div class="bg-white rounded-4 p-4" style="width:32rem;max-width:92vw;max-height:85vh;overflow-y:auto">
        <div class="d-flex align-items-center justify-content-between mb-3">
          <h2 class="small fw-bold text-dark mb-0">Harga per Tahun — <span id="yearPriceExt"></span></h2>
          <button type="button" data-action="call" data-call="closeYearPriceModal" class="btn-close" style="font-size:11px"></button>
        </div>
        <p class="text-muted mb-3" style="font-size:11px">
          Kosongkan supaya tahun itu dihitung otomatis (harga 1 tahun × jumlah tahun). Angka abu-abu
          adalah harga modal dari registrar. <b>Markup Massal otomatis mengisi kolom ini</b> dari harga
          modal per tahun — jalankan Markup Massal dulu kalau kolomnya masih kosong.
        </p>
        <div id="yearPriceRows" class="d-flex flex-column gap-2"></div>
        <button type="button" data-action="call" data-call="closeYearPriceModal" data-args='[true]' class="btn btn-primary btn-sm w-100 mt-3">Terapkan ke Form</button>
      </div>
    </div>

    <script <?php echo 'nonce="' . e(app(\App\Support\CspNonce::class)->value()) . '"'; ?>>
      // Panel Pembulatan di Markup Massal -- sembunyikan/tampilkan field
      // sesuai mode yang dipilih.
      (function () {
        const mode = document.getElementById('roundMode');

        if (! mode) return;

        function syncRound() {
          document.querySelectorAll('[data-round-field]').forEach(function (el) {
            const isTail = el.hasAttribute('data-tail');
            if (mode.value === 'none') {
              el.classList.add('d-none');
            } else if (mode.value === 'ending') {
              el.classList.remove('d-none');
            } else {
              el.classList.toggle('d-none', isTail);
            }
          });
        }

        mode.addEventListener('change', syncRound);
        syncRound();
      })();

      let currentYearPriceTld = null;

      function openYearPriceModal(btn) {
        currentYearPriceTld = btn.dataset.tldId;
        const maxYears = parseInt(btn.dataset.maxYears || '10', 10);
        const yearPrices = JSON.parse(btn.dataset.yearPrices || '{}');
        const yearRenewPrices = JSON.parse(btn.dataset.yearRenewPrices || '{}');
        const costYearPrices = JSON.parse(btn.dataset.costYearPrices || '{}');

        document.getElementById('yearPriceExt').textContent = btn.dataset.extension;

        const rows = document.getElementById('yearPriceRows');
        rows.innerHTML = '';

        for (let y = 2; y <= Math.max(maxYears, 2); y++) {
          const cost = costYearPrices[y] ? Number(costYearPrices[y]).toLocaleString('id-ID') : '—';
          // Margin per tahun ditampilkan langsung supaya kelihatan
          // kalau ada durasi yang ternyata tipis/rugi -- harga modal
          // durasi panjang sering BUKAN kelipatan lurus dari 1 tahun.
          const costNum = Number(costYearPrices[y] || 0);
          const sellNum = Number(yearPrices[y] || 0);
          let marginHtml = '';

          if (costNum > 0 && sellNum > 0) {
            const m = sellNum - costNum;
            const pct = (m / costNum * 100).toFixed(1);
            marginHtml = `<span style="font-size:10px;color:${m >= 0 ? '#047857' : '#b91c1c'}">
                            &middot; margin Rp ${Math.round(m).toLocaleString('id-ID')} (${pct}%)
                          </span>`;
          }

          rows.insertAdjacentHTML('beforeend', `
            <div class="d-flex align-items-center gap-2">
              <span class="text-muted" style="font-size:12px;width:4rem">${y} Thn</span>
              <div class="flex-grow-1">
                <input type="number" step="1" min="0" class="form-control form-control-sm year-price-input" data-year="${y}" data-kind="register" placeholder="otomatis" value="${yearPrices[y] ?? ''}">
                <span class="text-muted" style="font-size:10px">modal ${cost}</span>${marginHtml}
              </div>
              <div class="flex-grow-1">
                <input type="number" step="1" min="0" class="form-control form-control-sm year-price-input" data-year="${y}" data-kind="renew" placeholder="otomatis (renew)" value="${yearRenewPrices[y] ?? ''}">
              </div>
            </div>
          `);
        }

        document.getElementById('yearPriceModal').classList.remove('d-none');
        document.getElementById('yearPriceModal').style.display = 'flex';
      }

      function closeYearPriceModal(apply) {
        if (apply && currentYearPriceTld) {
          const form = document.getElementById('pricingForm');

          // Hapus input tersembunyi lama untuk TLD ini (kalau modal dibuka berkali-kali).
          form.querySelectorAll(`input[data-year-hidden="${currentYearPriceTld}"]`).forEach(el => el.remove());

          document.querySelectorAll('.year-price-input').forEach(input => {
            if (! input.value) return;
            const kind = input.dataset.kind === 'renew' ? 'year_renew_prices' : 'year_prices';
            const hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.dataset.yearHidden = currentYearPriceTld;
            hidden.name = `rows[${currentYearPriceTld}][${kind}][${input.dataset.year}]`;
            hidden.value = input.value;
            form.appendChild(hidden);
          });
        }

        document.getElementById('yearPriceModal').classList.add('d-none');
        document.getElementById('yearPriceModal').style.display = 'none';
        currentYearPriceTld = null;
      }

      (window.LumoraActions = window.LumoraActions || {}).closeYearPriceModal = closeYearPriceModal;
      document.querySelectorAll('.year-price-btn').forEach(btn => {
        btn.addEventListener('click', () => openYearPriceModal(btn));
      });

      // Margin real-time, sama seperti halaman Status TLD.
      document.querySelectorAll('[data-row]').forEach(row => {
        const costInput = row.querySelector('[data-cost]');
        const registerInput = row.querySelector('[data-register]');
        const marginCell = row.querySelector('[data-margin]');

        function updateMargin() {
          const cost = parseFloat(costInput.value) || 0;
          const register = parseFloat(registerInput.value) || 0;

          if (cost <= 0 || register <= 0) {
            marginCell.innerHTML = '<span class="text-muted">—</span>';
            return;
          }

          const margin = register - cost;
          const percent = (margin / cost * 100).toFixed(1);
          const tone = margin >= 0 ? 'text-success' : 'text-danger';
          marginCell.innerHTML = `<span class="${tone}">Rp ${Math.round(margin).toLocaleString('id-ID')}<br>${percent}%</span>`;
        }

        costInput?.addEventListener('input', updateMargin);
        registerInput?.addEventListener('input', updateMargin);
        updateMargin();
      });
    </script>
  <?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/admin/tlds/pricing.blade.php ENDPATH**/ ?>