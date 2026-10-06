<?php
  use App\Models\Setting;
  $themeColor = Setting::get('theme_color', '#6366F1');

  $seoTitle = 'Cek Ketersediaan Domain';
  $seoDescription = 'Cari dan daftarkan domain impian Anda — proses cepat, harga transparan.';

  $availableCount = ($results && $results['success'])
    ? collect($results['results'])->filter(fn ($v) => $v === true)->count()
    : 0;

  $unknownCount = ($results && $results['success'])
    ? count($results['unknown'] ?? [])
    : 0;
?>

<?php $__env->startSection('full-width'); ?>

  
  <section class="position-relative overflow-hidden">
    <div class="position-absolute top-0 start-0 end-0 bottom-0" style="background:linear-gradient(160deg,#1e1b4b 0%,#312e81 40%,#4c1d95 75%,#1e1b4b 100%)"></div>

    <div class="position-relative container text-center py-5" style="max-width:48rem">
      <p class="text-white mb-2" style="opacity:.5;font-size:11px;font-weight:600;letter-spacing:.1em;text-transform:uppercase">Daftar Domain Baru</p>
      <h1 class="fw-bold text-white mb-4" style="font-size:1.9rem">
        Domain <span style="color:#fcd34d">keren</span> bikin websitemu gampang diingat
      </h1>

      <form method="GET" action="<?php echo e(route('domain.search')); ?>" id="searchForm"
            class="bg-white rounded-4 p-2 d-flex flex-column flex-sm-row gap-2 shadow">
        <div class="d-flex align-items-center gap-2 flex-grow-1 px-3">
          <i class="fa-solid fa-globe text-muted"></i>
          <input type="text" name="domain" value="<?php echo e($query); ?>"
                 placeholder="ketik nama domain yang kamu inginkan…"
                 class="w-100 py-2 border-0" style="outline:none;font-size:14px" required autofocus>
        </div>

        
        <div id="selectedMirror"></div>

        <button type="submit" class="btn btn-theme flex-shrink-0 py-2 px-4">
          <i class="fa-solid fa-magnifying-glass" style="font-size:12px"></i> Cari Domain
        </button>
      </form>

      <p class="text-white mt-3 mb-0" style="opacity:.4;font-size:12px">
        Sudah punya domain di tempat lain?
        <a href="<?php echo e(route('domains.transfer')); ?>" class="text-white text-decoration-underline" style="opacity:.7">Transfer ke sini</a>
      </p>
    </div>
  </section>

  
  <div class="container mt-4" style="max-width:72rem">
    <?php echo $__env->make('public._promo-banner-carousel', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
  </div>

  <div class="container py-5" style="max-width:72rem">
    <div class="row g-4">

      
      <div class="col-12 col-lg-4 d-flex flex-column gap-3">
        <div class="card-public overflow-hidden">
          <div class="px-3 py-3 border-bottom">
            <p class="fw-semibold text-dark mb-0" style="font-size:14px">Kategori</p>
            <p class="text-muted mb-0" style="font-size:11px">Klik untuk memilih sekelompok ekstensi</p>
          </div>
          <div>
            <?php $__empty_1 = true; $__currentLoopData = $groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $groupName => $tlds): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
              <button type="button" data-group="<?php echo e($groupName); ?>"
                      class="w-100 btn d-flex align-items-center justify-content-between px-3 py-2 text-muted border-0 border-bottom text-start" style="font-size:14px">
                <?php echo e($groupName); ?>

                <span class="text-muted" style="font-size:12px"><?php echo e($tlds->count()); ?></span>
              </button>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
              <p class="px-3 py-3 text-muted mb-0" style="font-size:12px">Belum ada ekstensi.</p>
            <?php endif; ?>
          </div>
        </div>

        <div class="card-public p-3">
          <p class="text-muted mb-0" style="font-size:12px;line-height:1.6">
            <i class="fa-solid fa-circle-info text-theme"></i>
            Biarkan kosong untuk mencari otomatis di semua ekstensi yang kami jual
            (maksimal 20 hasil), atau centang ekstensi tertentu untuk hasil yang lebih spesifik.
          </p>
        </div>
      </div>

      
      <div class="col-12 col-lg-8 d-flex flex-column gap-4">

        
        <?php if($tldPrices->isNotEmpty()): ?>
          <div class="card-public overflow-hidden">
            <div class="px-4 py-3 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
              <div>
                <p class="fw-semibold text-dark mb-0" style="font-size:14px">Ekstensi Domain</p>
                <p class="text-muted mb-0" style="font-size:11px">
                  <span id="selCount">0</span> dipilih —
                  <span class="text-muted">kosongkan untuk mencari otomatis di semua ekstensi</span>
                </p>
              </div>
              <div class="d-flex align-items-center gap-3" style="font-size:12px">
                <button type="button" id="selAll" class="btn btn-link p-0 text-theme" style="text-decoration:underline">Pilih semua</button>
                <button type="button" id="selNone" class="btn btn-link p-0 text-muted" style="text-decoration:underline">Kosongkan</button>
              </div>
            </div>

            <div class="p-4 row g-2">
              <?php $__currentLoopData = $tldPrices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ext => $tld): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-6 col-sm-4">
                  <label data-ext-group="<?php echo e($tld->search_group_label); ?>"
                         class="d-flex align-items-center justify-content-between gap-2 rounded-3 border px-3 py-2 h-100" style="cursor:pointer">
                    <span class="d-flex align-items-center gap-2 min-w-0">
                      <input type="checkbox" value="<?php echo e($ext); ?>" data-ext
                             <?php if(in_array($ext, $selected)): echo 'checked'; endif; ?>
                             class="form-check-input flex-shrink-0" style="margin:0">
                      <span class="text-dark text-truncate" style="font-size:14px"><?php echo e($ext); ?></span>
                      <?php if($tld->is_demo): ?>
                        <span class="badge flex-shrink-0" style="font-size:9px;background:#fef3c7;color:#b45309">DEMO</span>
                      <?php endif; ?>
                    </span>
                    <span class="text-muted flex-shrink-0" style="font-size:10px">
                      <?php echo e($tld->register_price >= 1000000
                          ? 'Rp' . number_format($tld->register_price / 1000000, 1) . 'jt'
                          : 'Rp' . number_format($tld->register_price / 1000, 0) . 'rb'); ?>

                    </span>
                  </label>
                </div>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
          </div>
        <?php else: ?>
          <div class="card-public p-5 text-center">
            <p class="text-muted mb-1" style="font-size:14px">Belum ada ekstensi yang ditampilkan.</p>
            <p class="text-muted mb-0" style="font-size:12px">
              Atur lewat admin: <b>Domain → TLD Pricing</b>, centang kolom "Tampil di Web".
            </p>
          </div>
        <?php endif; ?>

        
        <?php if($query): ?>
          <div>
            <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
              <h2 class="fw-semibold text-dark mb-0" style="font-size:16px">Hasil pencarian untuk "<?php echo e($query); ?>"</h2>
              <div class="d-flex align-items-center gap-3" style="font-size:12px">
                <?php if($results && $results['success'] && $availableCount > 0): ?>
                  <span class="text-success fw-medium"><?php echo e($availableCount); ?> tersedia</span>
                <?php endif; ?>
                <?php if($unknownCount > 0): ?>
                  <span style="color:#b45309"><?php echo e($unknownCount); ?> belum pasti</span>
                <?php endif; ?>
              </div>
            </div>

            <?php if(! $results['success']): ?>
              <div class="card-public p-4 text-center">
                <p class="mb-1" style="font-size:14px;color:#e11d48">
                  <i class="fa-solid fa-circle-exclamation"></i> <?php echo e($results['message']); ?>

                </p>
                <p class="text-muted mb-0" style="font-size:12px">Coba pilih lebih sedikit ekstensi, atau ulangi beberapa saat lagi.</p>
              </div>
            <?php else: ?>
              <div class="d-flex flex-column gap-2">
                <?php $__empty_1 = true; $__currentLoopData = $results['results']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $domainName => $available): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                  <?php
                    $ext = '.' . \Illuminate\Support\Str::after($domainName, '.');
                    $tld = $allTldPrices->get($ext);
                    $rowStyle = $available === true ? 'border-color:#a7f3d0!important;background:rgba(16,185,129,.04)'
                              : ($available === null ? 'border-color:#fde68a!important;background:rgba(245,158,11,.04)'
                              : 'background:#f8fafc');
                  ?>

                  <div class="rounded-3 border px-4 py-3 d-flex align-items-center justify-content-between gap-3 flex-wrap" style="<?php echo e($rowStyle); ?>">
                    <div class="min-w-0">
                      <p class="fw-semibold mb-0" style="<?php echo e($available === true ? 'color:#1e293b' : ($available === null ? 'color:#334155' : 'color:#94a3b8;text-decoration:line-through')); ?>">
                        <?php echo e($domainName); ?>

                        <?php if($tld?->is_demo): ?>
                          <span class="badge ms-1" style="font-size:10px;background:#fef3c7;color:#b45309;vertical-align:middle">DEMO</span>
                        <?php endif; ?>
                      </p>
                      <?php if($available === true && $tld): ?>
                        <p class="text-muted mb-0" style="font-size:12px">
                          Rp <?php echo e(number_format($tld->register_price, 0, ',', '.')); ?> <span class="text-muted">/tahun</span>
                        </p>
                      <?php elseif($available === null): ?>
                        <p class="mb-0" style="font-size:12px;color:#b45309">Status belum bisa dipastikan — akan dicek ulang saat pemesanan.</p>
                      <?php endif; ?>
                    </div>

                    <?php if($available === true): ?>
                      <form method="POST" action="<?php echo e(route('domain.add-to-cart')); ?>" data-add-domain class="d-flex align-items-center gap-2 flex-shrink-0">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="domain_name" value="<?php echo e($domainName); ?>">
                        <input type="hidden" name="tld_id" value="<?php echo e($tld->id ?? ''); ?>">
                        <select name="years" class="form-select form-select-sm" style="width:auto">
                          <?php for($y = 1; $y <= min($tld->max_years ?? 5, 5); $y++): ?>
                            <option value="<?php echo e($y); ?>"><?php echo e($y); ?> thn</option>
                          <?php endfor; ?>
                        </select>
                        <button type="submit" class="btn btn-theme py-1 px-3" style="font-size:12px" <?php echo e($tld ? '' : 'disabled'); ?>>
                          <i class="fa-solid fa-cart-plus" style="font-size:11px"></i> Tambah
                        </button>
                      </form>
                    <?php elseif($available === null): ?>
                      <span class="flex-shrink-0" style="font-size:12px;color:#b45309">Perlu Dicek Manual</span>
                    <?php else: ?>
                      <span class="text-muted flex-shrink-0" style="font-size:12px">Tidak Tersedia</span>
                    <?php endif; ?>
                  </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                  <div class="card-public p-4 text-center text-muted" style="font-size:14px">Tidak ada hasil untuk pencarian ini.</div>
                <?php endif; ?>
              </div>
            <?php endif; ?>
          </div>

          
          <div class="card-public p-4 d-flex align-items-center justify-content-between gap-3 flex-wrap">
            <a href="<?php echo e(route('home')); ?>" class="btn btn-outline-secondary">Kembali</a>

            <div class="d-flex align-items-center gap-2" style="font-size:12px">
              <?php $__currentLoopData = ['Domain', 'Hosting', 'Keranjang', 'Bayar']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="d-flex align-items-center gap-2">
                  <span class="rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width:24px;height:24px;font-size:11px;<?php echo e($i === 0 ? 'color:#fff;background:' . $themeColor : 'background:#e2e8f0;color:#64748b'); ?>"><?php echo e($i + 1); ?></span>
                  <span style="<?php echo e($i === 0 ? 'font-weight:600;color:#1e293b' : 'color:#94a3b8'); ?>"><?php echo e($step); ?></span>
                  <?php if($i < 3): ?>
                    <span style="width:24px;height:1px;background:#e2e8f0"></span>
                  <?php endif; ?>
                </div>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <a href="<?php echo e(route('catalog.index', ['dari_domain' => 1])); ?>" class="btn btn-theme">
              Lanjut <i class="fa-solid fa-arrow-right" style="font-size:12px"></i>
            </a>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  
  <div id="floatingCartBadge" class="position-fixed d-none" style="left:20px;bottom:20px;z-index:1050">
    <a href="<?php echo e(route('cart.index')); ?>" class="d-flex align-items-center gap-2 text-decoration-none rounded-pill shadow" style="background:#1e293b;color:#fff;padding:.75rem 1.25rem .75rem 1rem">
      <span class="position-relative">
        <i class="fa-solid fa-cart-shopping"></i>
        <span id="floatingCartCount" class="position-absolute rounded-circle d-flex align-items-center justify-content-center fw-bold text-white" style="top:-8px;right:-8px;width:16px;height:16px;font-size:9px;background:var(--lumora-theme)">0</span>
      </span>
      <span class="fw-medium" style="font-size:14px">Lihat Keranjang</span>
    </a>
  </div>

  <div id="toastBox" class="position-fixed d-none" style="left:20px;bottom:96px;z-index:1055;max-width:20rem">
    <div id="toastInner" class="d-flex align-items-center gap-2 rounded-3 px-3 py-2 shadow text-white" style="background:#059669;font-size:14px">
      <i class="fa-solid fa-circle-check flex-shrink-0"></i>
      <span id="toastMsg"></span>
    </div>
  </div>

  <script <?php echo 'nonce="' . e(app(\App\Support\CspNonce::class)->value()) . '"'; ?>>
    (function () {
      const boxes  = Array.from(document.querySelectorAll('[data-ext]'));
      const mirror = document.getElementById('selectedMirror');
      const count  = document.getElementById('selCount');

      // Checkbox berada di luar <form> pencarian (beda kolom layout), jadi
      // pilihannya disalin jadi hidden input tepat sebelum submit.
      function sync() {
        mirror.innerHTML = '';
        let n = 0;

        boxes.forEach(function (b) {
          if (!b.checked) return;
          n++;
          const hidden = document.createElement('input');
          hidden.type = 'hidden';
          hidden.name = 'extensions[]';
          hidden.value = b.value;
          mirror.appendChild(hidden);
        });

        count.textContent = n;
      }

      boxes.forEach(b => b.addEventListener('change', sync));

      document.getElementById('selAll')?.addEventListener('click', function () {
        boxes.forEach(b => b.checked = true); sync();
      });
      document.getElementById('selNone')?.addEventListener('click', function () {
        boxes.forEach(b => b.checked = false); sync();
      });

      // Klik kategori → centang hanya ekstensi dalam kelompok itu.
      document.querySelectorAll('[data-group]').forEach(function (btn) {
        btn.addEventListener('click', function () {
          const group = btn.dataset.group;
          boxes.forEach(function (b) {
            b.checked = b.closest('[data-ext-group]')?.dataset.extGroup === group;
          });
          sync();
        });
      });

      sync();
    })();

    (function () {
      const badge      = document.getElementById('floatingCartBadge');
      const badgeCount = document.getElementById('floatingCartCount');
      const topbarBadge = document.getElementById('cartBadge');
      const toast      = document.getElementById('toastBox');
      const toastInner = document.getElementById('toastInner');
      const toastMsg   = document.getElementById('toastMsg');
      let toastTimer = null;

      function updateCartCount(n) {
        badgeCount.textContent = n;
        badge.classList.toggle('d-none', n <= 0);

        if (topbarBadge) {
          topbarBadge.textContent = n;
          topbarBadge.classList.toggle('d-none', n <= 0);
        }
      }

      function showToast(message, isError) {
        toastMsg.textContent = message;
        toastInner.style.background = isError ? '#e11d48' : '#059669';
        toast.classList.remove('d-none');
        clearTimeout(toastTimer);
        toastTimer = setTimeout(() => toast.classList.add('d-none'), 3500);
      }

      // Mulai dari jumlah yang sudah dirender server di topbar, supaya
      // badge mengambang langsung akurat kalau keranjang sudah berisi
      // sesuatu dari kunjungan sebelumnya.
      updateCartCount(parseInt(topbarBadge?.textContent || '0', 10));

      document.querySelectorAll('form[data-add-domain]').forEach(function (form) {
        form.addEventListener('submit', async function (e) {
          e.preventDefault();

          const btn = form.querySelector('button[type="submit"]');
          const originalHtml = btn.innerHTML;
          btn.disabled = true;
          btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin" style="font-size:11px"></i>';

          try {
            const res = await fetch(form.action, {
              method: 'POST',
              headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                'Accept': 'application/json',
              },
              body: new FormData(form),
            });
            const data = await res.json();

            showToast(data.message, !data.success);

            if (data.success) {
              updateCartCount(data.cart_count);
              btn.innerHTML = '<i class="fa-solid fa-check" style="font-size:11px"></i> Ditambahkan';
              // Tombol dikembalikan normal setelah sebentar — klien tetap
              // bisa menambah domain lain kapan saja, tidak terkunci.
              setTimeout(function () {
                btn.innerHTML = originalHtml;
                btn.disabled = false;
              }, 1500);
            } else {
              btn.innerHTML = originalHtml;
              btn.disabled = false;
            }
          } catch (err) {
            showToast('Gagal menambah domain. Periksa koneksi Anda dan coba lagi.', true);
            btn.innerHTML = originalHtml;
            btn.disabled = false;
          }
        });
      });
    })();
  </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('public.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/themes/public-themes/default/public/catalog/domain-search.blade.php ENDPATH**/ ?>