<?php
  use Illuminate\Support\Str;
  $seoTitle = $product->name . ' — ' . $category->name;
  $seoDescription = $product->tagline ?: Str::limit(strip_tags((string) $product->description), 155);
  $cycles = $product->availableCycles();
  $unit = ['monthly' => '/bulan', 'quarterly' => '/3 bulan', 'semi_annually' => '/6 bulan', 'annually' => '/tahun'];
?>

<?php $__env->startSection('content'); ?>

  <nav class="text-muted mb-3" style="font-size:12px">
    <a href="<?php echo e(route('catalog.index')); ?>" class="text-decoration-none text-muted">Hosting</a> /
    <a href="<?php echo e($category->publicUrl()); ?>" class="text-decoration-none text-muted"><?php echo e($category->name); ?></a> /
    <?php echo e($product->name); ?>

  </nav>

  <?php if($errors->any()): ?>
    <div class="rounded-3 px-3 py-2 mb-4" style="background:#fef2f2;border:1px solid #fecaca;font-size:14px;color:#b91c1c">
      <?php echo e($errors->first()); ?>

    </div>
  <?php endif; ?>

  <div class="row g-4">
    <div class="col-12 col-lg-8">
      <h1 class="fw-bold text-dark mb-1" style="font-size:1.6rem"><?php echo e($product->name); ?></h1>
      <?php if($product->tagline): ?>
        <p class="text-muted mb-4"><?php echo e($product->tagline); ?></p>
      <?php endif; ?>

      <?php if($product->description): ?>
        <div class="prose-content mb-4"><?php echo nl2br(e($product->description)); ?></div>
      <?php endif; ?>

      <?php if($product->features): ?>
        <div class="card-public p-4">
          <h2 class="small fw-bold text-dark mb-3">Fitur yang Anda Dapatkan</h2>
          <div class="row g-2">
            <?php $__currentLoopData = $product->features; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <div class="col-sm-6">
                <div class="d-flex align-items-start gap-2 text-muted" style="font-size:14px">
                  <i class="fa-solid fa-circle-check text-success flex-shrink-0" style="width:14px;margin-top:3px;text-align:center"></i>
                  <span class="min-w-0"><?php echo e($feature); ?></span>
                </div>
              </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </div>
        </div>
      <?php endif; ?>
    </div>

    
    <div class="col-12 col-lg-4">
      <div class="card-public p-4" style="position:sticky;top:6rem">
        <?php if(! $product->isInStock()): ?>
          <div class="text-center py-4">
            <i class="fa-solid fa-box-open text-muted mb-3" style="font-size:2rem"></i>
            <p class="fw-semibold text-dark mb-1">Stok Habis</p>
            <p class="text-muted mb-0" style="font-size:14px">Paket ini sedang tidak tersedia untuk pemesanan baru.</p>
          </div>
        <?php else: ?>
        <form method="POST" action="<?php echo e(route('cart.add-product')); ?>" id="orderForm">
          <?php echo csrf_field(); ?>
          <input type="hidden" name="product_id" value="<?php echo e($product->id); ?>">

          <?php if($product->isDepositBilled()): ?>
            <div class="rounded-3 px-3 py-2 mb-3 d-flex align-items-start gap-2" style="background:#eef2ff;border:1px solid #e0e7ff">
              <i class="fa-solid fa-bolt text-primary mt-1" style="font-size:11px"></i>
              <p class="mb-0" style="font-size:12.5px;color:#3730a3;line-height:1.5">
                <b>Bayar per jam sesuai pemakaian.</b> Harga di bawah untuk memilih paket saja —
                tagihan sebenarnya dipotong otomatis dari saldo Anda tiap jam setelah layanan aktif,
                bukan lewat invoice per siklus. Pastikan saldo cukup sebelum aktivasi.
                <?php if($hourly = $product->estimatedHourlyRate()): ?>
                  <br>Tarif paket ini <b>Rp <?php echo e(number_format($hourly, 2, ',', '.')); ?> / jam</b>
                  (± Rp <?php echo e(number_format($hourly * 730, 0, ',', '.')); ?> / bulan bila menyala terus).
                <?php endif; ?>
              </p>
            </div>
          <?php endif; ?>

          <p class="fw-bold text-muted mb-2" style="font-size:11px;text-transform:uppercase;letter-spacing:.03em">Pilih Siklus Tagihan</p>
          <div class="mb-4">
            <?php $__currentLoopData = $cycles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cycleKey => $price): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <label class="d-flex align-items-center justify-content-between p-3 rounded-3 border mb-2" style="cursor:pointer">
                <span class="d-flex align-items-center gap-2">
                  <input type="radio" name="billing_cycle" value="<?php echo e($cycleKey); ?>" data-price="<?php echo e($price); ?>" <?php echo e($loop->first ? 'checked' : ''); ?> required class="cycle-radio" style="margin:0">
                  <span class="text-dark" style="font-size:14px"><?php echo e($product->cycleLabel($cycleKey)); ?></span>
                </span>
                <span class="fw-semibold text-dark" style="font-size:14px">Rp <?php echo e(number_format($price, 0, ',', '.')); ?></span>
              </label>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </div>

          <?php if($product->setup_fee > 0): ?>
            <p class="text-muted mb-3" style="font-size:12px">+ Biaya setup Rp <?php echo e(number_format($product->setup_fee, 0, ',', '.')); ?> (sekali bayar)</p>
          <?php endif; ?>

          <?php if($product->optionGroups->isNotEmpty()): ?>
            <div class="border-top pt-3 mb-3" id="optionsSection">
              <?php $__currentLoopData = $product->optionGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if($group->options->isEmpty()) continue; ?>
                <div class="mb-3">
                  <p class="fw-bold text-muted mb-2" style="font-size:11px;text-transform:uppercase;letter-spacing:.03em">
                    <?php echo e($group->name); ?>

                    <?php if($group->isRadio() && $group->is_required): ?>
                      <span class="text-danger">*</span>
                    <?php endif; ?>
                  </p>

                  <?php if($group->isRadio() && ! $group->is_required): ?>
                    <label class="d-flex align-items-center gap-2 text-muted mb-2" style="font-size:13px;cursor:pointer">
                      <input type="radio" name="options[<?php echo e($group->id); ?>]" value="" checked class="option-radio-none" data-group="<?php echo e($group->id); ?>" style="margin:0">
                      Tidak perlu
                    </label>
                  <?php endif; ?>

                  <?php $__currentLoopData = $group->options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <label class="d-flex align-items-center justify-content-between p-2 rounded-3 border mb-2 option-choice"
                           data-prices="<?php echo e(json_encode(['monthly' => $option->price_monthly, 'quarterly' => $option->price_quarterly, 'semi_annually' => $option->price_semi_annually, 'annually' => $option->price_annually, 'custom' => $option->price_custom])); ?>">
                      <span class="d-flex align-items-center gap-2">
                        <input type="<?php echo e($group->isRadio() ? 'radio' : 'checkbox'); ?>"
                               name="options[<?php echo e($group->id); ?>]<?php echo e($group->isRadio() ? '' : '[]'); ?>"
                               value="<?php echo e($option->id); ?>"
                               class="option-input" data-group="<?php echo e($group->id); ?>"
                               <?php echo e($group->isRadio() && $group->is_required && $loop->first ? 'checked' : ''); ?>

                               style="margin:0">
                        <span class="text-dark" style="font-size:13px"><?php echo e($option->name); ?></span>
                      </span>
                      <span class="option-price fw-medium text-muted flex-shrink-0" style="font-size:12px"></span>
                    </label>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
          <?php endif; ?>

          <div class="d-flex align-items-center justify-content-between border-top pt-3 mb-3">
            <span class="text-muted" style="font-size:13px">Total per siklus</span>
            <span id="orderTotal" class="fw-bold text-dark" style="font-size:18px">Rp 0</span>
          </div>

          <?php if($product->allowsDomain()): ?>
            <div class="border-top pt-3 mb-3">
              <p class="fw-bold text-muted mb-2" style="font-size:11px;text-transform:uppercase;letter-spacing:.03em">
                Domain <?php echo e($product->requiresDomain() ? '(Wajib)' : '(Opsional)'); ?>

              </p>

              <div>
                <?php if (! ($product->requiresDomain())): ?>
                  <label class="d-flex align-items-center gap-2 text-muted mb-2" style="font-size:14px;cursor:pointer">
                    <input type="radio" name="domain_mode" value="" checked class="domain-mode-radio" style="margin:0">
                    Tidak perlu domain
                  </label>
                <?php endif; ?>
                <label class="d-flex align-items-center gap-2 text-muted mb-2" style="font-size:14px;cursor:pointer">
                  <input type="radio" name="domain_mode" value="register" <?php echo e($product->requiresDomain() ? 'checked' : ''); ?> class="domain-mode-radio" style="margin:0">
                  Daftarkan domain baru
                </label>
                <label class="d-flex align-items-center gap-2 text-muted mb-2" style="font-size:14px;cursor:pointer">
                  <input type="radio" name="domain_mode" value="transfer" class="domain-mode-radio" style="margin:0">
                  Transfer domain dari registrar lain
                </label>
                <label class="d-flex align-items-center gap-2 text-muted mb-0" style="font-size:14px;cursor:pointer">
                  <input type="radio" name="domain_mode" value="existing" class="domain-mode-radio" style="margin:0">
                  Saya sudah punya domain ini (arahkan nameserver saja)
                </label>
              </div>

              <div id="domainNameField" class="mt-3 <?php echo e($product->requiresDomain() ? '' : 'd-none'); ?>">
                <input type="text" name="domain_name" value="<?php echo e(old('domain_name')); ?>" placeholder="contoh.com" class="form-control form-control-sm">
                <p id="domainNameHint" class="text-muted mt-1 mb-0" style="font-size:11px">
                  Ketersediaan domain baru dicek ulang saat checkout.
                  Untuk cek dulu, pakai <a href="<?php echo e(route('domain.search')); ?>" class="text-theme" target="_blank">halaman Cek Domain</a>.
                </p>
              </div>

              <div id="transferAuthField" class="mt-3 d-none">
                <label class="fw-medium text-muted mb-1 d-block" style="font-size:12px">Kode EPP / Auth Code</label>
                <input type="text" name="transfer_auth_code" value="<?php echo e(old('transfer_auth_code')); ?>" placeholder="Diminta dari registrar domain Anda saat ini" class="form-control form-control-sm">
                <p class="text-muted mt-1 mb-0" style="font-size:11px">
                  Proses transfer butuh persetujuan pemilik domain (email dari registrar lama)
                  dan biasanya memakan waktu 5–7 hari, bukan langsung aktif detik itu juga.
                </p>
              </div>
            </div>
          <?php endif; ?>

          <button type="submit" class="btn btn-theme w-100">
            <i class="fa-solid fa-cart-plus" style="font-size:12px"></i> Tambah ke Keranjang
          </button>
        </form>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <?php if($related->isNotEmpty()): ?>
    <div class="mt-5">
      <h2 class="fw-bold text-dark mb-3" style="font-size:1.15rem">Paket Lain di <?php echo e($category->name); ?></h2>
      <div class="row g-3">
        <?php $__currentLoopData = $related; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <div class="col-sm-6 col-lg-4">
            <?php echo $__env->make('public.catalog._product-card', ['product' => $rp], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
          </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </div>
    </div>
  <?php endif; ?>

  <script <?php echo 'nonce="' . e(app(\App\Support\CspNonce::class)->value()) . '"'; ?>>
    (function () {
      const radios = document.querySelectorAll('.domain-mode-radio');
      const field = document.getElementById('domainNameField');
      const hint = document.getElementById('domainNameHint');
      const transferField = document.getElementById('transferAuthField');

      function syncDomainMode() {
        if (!radios.length || !field) return;
        const checked = document.querySelector('.domain-mode-radio:checked');
        const mode = checked ? checked.value : '';
        const needsInput = mode === 'register' || mode === 'transfer' || mode === 'existing';

        field.classList.toggle('d-none', !needsInput);
        field.querySelector('input').required = !!needsInput;

        transferField.classList.toggle('d-none', mode !== 'transfer');
        transferField.querySelector('input').required = mode === 'transfer';

        if (hint) {
          hint.textContent = mode === 'transfer'
            ? 'Domain harus sudah "unlock" di registrar lama sebelum bisa ditransfer.'
            : (mode === 'existing'
                ? 'Kami akan minta Anda mengarahkan nameserver domain ini setelah checkout.'
                : 'Ketersediaan domain baru dicek ulang saat checkout.');
        }
      }

      radios.forEach(r => r.addEventListener('change', syncDomainMode));

      // ── Opsi konfigurasi: sembunyikan opsi yang tidak dijual untuk
      // siklus tagihan aktif, tampilkan harganya, dan hitung total. ──
      const idr = (n) => 'Rp ' + Number(n).toLocaleString('id-ID');
      const cycleRadios = document.querySelectorAll('.cycle-radio');
      const optionChoices = document.querySelectorAll('.option-choice');
      const totalEl = document.getElementById('orderTotal');

      function currentCycle() {
        const c = document.querySelector('.cycle-radio:checked');
        return c ? c.value : null;
      }

      function syncOptionsForCycle() {
        const cycle = currentCycle();

        optionChoices.forEach((label) => {
          const prices = JSON.parse(label.dataset.prices || '{}');
          const price = prices[cycle];
          const input = label.querySelector('.option-input');
          const priceEl = label.querySelector('.option-price');
          const available = price !== null && price !== undefined;

          label.classList.toggle('d-none', !available);

          if (!available && input.checked) {
            input.checked = false;
          }

          if (available) {
            priceEl.textContent = Number(price) > 0 ? '+ ' + idr(price) : 'Gratis';
          }
        });
      }

      function updateTotal() {
        const cycleRadio = document.querySelector('.cycle-radio:checked');
        let total = cycleRadio ? Number(cycleRadio.dataset.price || 0) : 0;

        document.querySelectorAll('.option-input:checked').forEach((input) => {
          const label = input.closest('.option-choice');
          const prices = JSON.parse(label.dataset.prices || '{}');
          const price = prices[currentCycle()];
          if (price !== null && price !== undefined) total += Number(price);
        });

        if (totalEl) totalEl.textContent = idr(total);
      }

      function syncAll() {
        syncOptionsForCycle();
        updateTotal();
      }

      cycleRadios.forEach(r => r.addEventListener('change', syncAll));
      document.querySelectorAll('.option-input, .option-radio-none').forEach(el => el.addEventListener('change', updateTotal));

      syncDomainMode();
      syncAll();
    })();
  </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('public.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/themes/public-themes/default/public/catalog/product.blade.php ENDPATH**/ ?>