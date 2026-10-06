<?php $__env->startSection('title', $addon->exists ? 'Edit Addon' : 'Tambah Addon'); ?>

<?php
  $cycleLabels = \App\Models\Addon::CYCLE_LABELS;
  $source = old('pricing_source', $addon->pricing_source ?: 'manual');

  // Bagian "Keterangan Produk Lengkap" dilipat kalau masih kosong supaya form
  // tidak terasa panjang; otomatis terbuka kalau sudah ada isi / ada error.
  $hasDetails = filled(old('long_description', $addon->long_description))
      || filled(old('features_text', $addon->features))
      || filled(old('specs_text', $addon->specs))
      || filled(old('faqs_text', $addon->faqs))
      || $errors->hasAny(['long_description', 'features_text', 'specs_text', 'faqs_text']);
?>

<?php $__env->startSection('content'); ?>

  <style>
    .addon-step { display:inline-flex; align-items:center; justify-content:center; width:22px; height:22px; border-radius:50%;
                  background:#eef2ff; color:#4f46e5; font-size:11px; font-weight:700; flex-shrink:0; }
    .src-opt { cursor:pointer; transition:border-color .15s, background .15s; }
    .src-opt.is-selected { border-color:#4f46e5 !important; background:#eef2ff; }
    .src-opt input { margin-top:3px; }
    .addon-details > summary { list-style:none; cursor:pointer; }
    .addon-details > summary::-webkit-details-marker { display:none; }
    .addon-details[open] .chev { transform:rotate(180deg); }
    .chev { transition:transform .15s; }
    .margin-hint { font-size:11px; min-height:16px; }
    @media (min-width: 992px) { .addon-side { position:sticky; top:84px; } }
  </style>

  <a href="<?php echo e(route('admin.addons.index')); ?>" class="text-decoration-none text-muted" style="font-size:12px"><i class="fa-solid fa-arrow-left"></i> Kembali ke Addons</a>
  <h1 class="h4 fw-bold text-dark mt-1 mb-4"><?php echo e($addon->exists ? 'Edit Addon' : 'Tambah Addon'); ?></h1>

  <?php if($errors->any()): ?>
    <div class="alert alert-danger small py-2 mb-3">
      <strong>Form belum bisa disimpan:</strong>
      <ul class="mb-0 ps-3">
        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($error); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </ul>
    </div>
  <?php endif; ?>

  <form id="addon-form" method="POST" action="<?php echo e($addon->exists ? route('admin.addons.update', $addon) : route('admin.addons.store')); ?>">
    <?php echo csrf_field(); ?>
    <?php if($addon->exists): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>

    <div class="row g-4 align-items-start">

      
      <div class="col-12 col-lg-8">

        
        <div class="card border rounded-4 p-4 mb-3">
          <div class="d-flex align-items-center gap-2 mb-3">
            <span class="addon-step">1</span>
            <span class="fw-semibold text-dark">Informasi Dasar</span>
          </div>
          <div class="mb-3">
            <label class="form-label small fw-medium text-dark">Nama Addon</label>
            <input type="text" name="name" id="nameInput" value="<?php echo e(old('name', $addon->name)); ?>" class="form-control form-control-sm" placeholder="mis. IP Dedicated" required>
            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>
          <div class="mb-3">
            <label class="form-label small fw-medium text-dark">Slug <span class="text-muted fw-normal">(opsional, otomatis dari nama kalau kosong)</span></label>
            <input type="text" name="slug" id="slugInput" value="<?php echo e(old('slug', $addon->slug)); ?>" class="form-control form-control-sm" placeholder="ip-dedicated">
            <?php $__errorArgs = ['slug'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>
          <div class="row g-3 mb-3">
            <div class="col-sm-6">
              <label class="form-label small fw-medium text-dark">Kategori</label>
              <select name="category" class="form-select form-select-sm">
                <?php $__currentLoopData = \App\Models\Addon::allCategories(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <option value="<?php echo e($key); ?>" <?php if(old('category', $addon->category ?: 'license') === $key): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </select>
              <?php $__errorArgs = ['category'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="col-sm-6">
              <label class="form-label small fw-medium text-dark">Merek</label>
              <input type="text" name="brand" value="<?php echo e(old('brand', $addon->brand)); ?>" class="form-control form-control-sm" placeholder="mis. Sectigo, GeoTrust, cPanel">
              <?php $__errorArgs = ['brand'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label small fw-medium text-dark">Ringkasan <span class="text-muted fw-normal">(satu kalimat, tampil di kartu katalog)</span></label>
            <input type="text" name="summary" value="<?php echo e(old('summary', $addon->summary)); ?>" class="form-control form-control-sm" maxlength="255">
            <?php $__errorArgs = ['summary'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>
          <div>
            <label class="form-label small fw-medium text-dark">Deskripsi</label>
            <textarea name="description" rows="3" class="form-control form-control-sm" placeholder="Dijelaskan singkat ke klien saat memilih addon ini."><?php echo e(old('description', $addon->description)); ?></textarea>
            <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>
        </div>

        
        <div class="card border rounded-4 p-4 mb-3">
          <div class="d-flex align-items-center gap-2 mb-1">
            <span class="addon-step">2</span>
            <span class="fw-semibold text-dark">Harga Jual per Siklus</span>
          </div>
          <p class="text-muted mb-3" style="font-size:11px">
            Yang dilihat klien. Kosongkan siklus yang tidak ditawarkan — addon baru tampil di katalog &amp; bisa dipesan
            kalau minimal satu siklus punya harga.
          </p>
          <div class="row g-3">
            <?php $__currentLoopData = $cycleLabels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cycle => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <div class="col-sm-6">
                <label class="text-muted mb-1 d-block" style="font-size:11px"><?php echo e($label); ?></label>
                <div class="input-group input-group-sm">
                  <span class="input-group-text">Rp</span>
                  <input type="number" step="1" min="0" name="price_<?php echo e($cycle); ?>" data-price="<?php echo e($cycle); ?>"
                         value="<?php echo e(old('price_'.$cycle, $addon->{'price_'.$cycle} !== null ? (int) $addon->{'price_'.$cycle} : null)); ?>" class="form-control">
                </div>
                <div class="margin-hint text-muted mt-1" data-margin="<?php echo e($cycle); ?>"></div>
                <?php $__errorArgs = ['price_'.$cycle];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
              </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </div>
          <p class="text-muted mt-3 mb-0" style="font-size:11px">
            Addon otomatis ikut ditagih di invoice perpanjangan layanan hosting yang memasangnya, mengikuti siklus
            tagihan layanan itu. Kalau siklus layanan tidak punya harga di sini, addon tidak bisa dipasang untuk layanan
            dengan siklus tersebut.
          </p>
        </div>

        
        <div class="card border rounded-4 p-4 mb-3">
          <div class="d-flex align-items-center gap-2 mb-1">
            <span class="addon-step">3</span>
            <span class="fw-semibold text-dark">Harga Modal Supplier</span>
          </div>
          <p class="text-muted mb-3" style="font-size:11px">Hanya untuk perhitungan margin internal, tidak ditampilkan ke klien. Pilih dulu dari mana harga modal didapat.</p>

          <div class="row g-2 mb-3">
            <div class="col-sm-6">
              <label class="src-opt border rounded-3 p-3 d-flex gap-2 h-100 mb-0" data-src-opt="manual">
                <input type="radio" name="pricing_source" value="manual" class="form-check-input flex-shrink-0" <?php if($source === 'manual'): echo 'checked'; endif; ?>>
                <span>
                  <span class="d-block small fw-semibold text-dark">Isi manual</span>
                  <span class="d-block text-muted" style="font-size:11px">Ketik harga modal sendiri per siklus.</span>
                </span>
              </label>
            </div>
            <div class="col-sm-6">
              <label class="src-opt border rounded-3 p-3 d-flex gap-2 h-100 mb-0" data-src-opt="api">
                <input type="radio" name="pricing_source" value="api" class="form-check-input flex-shrink-0" <?php if($source === 'api'): echo 'checked'; endif; ?>>
                <span>
                  <span class="d-block small fw-semibold text-dark">Ambil dari API supplier</span>
                  <span class="d-block text-muted" style="font-size:11px">Harga modal diperbarui otomatis lewat endpoint JSON supplier.</span>
                </span>
              </label>
            </div>
          </div>
          <?php $__errorArgs = ['pricing_source'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mb-2" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

          
          <div data-src-panel="manual">
            <div class="row g-3">
              <?php $__currentLoopData = $cycleLabels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cycle => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-sm-6">
                  <label class="text-muted mb-1 d-block" style="font-size:11px">Modal <?php echo e($label); ?></label>
                  <div class="input-group input-group-sm">
                    <span class="input-group-text">Rp</span>
                    <input type="number" step="1" min="0" name="cost_price_<?php echo e($cycle); ?>" data-cost="<?php echo e($cycle); ?>"
                           value="<?php echo e(old('cost_price_'.$cycle, $addon->{'cost_price_'.$cycle} !== null ? (int) $addon->{'cost_price_'.$cycle} : null)); ?>"
                           class="form-control" placeholder="Harga modal">
                  </div>
                  <?php $__errorArgs = ['cost_price_'.$cycle];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
          </div>

          
          <div data-src-panel="api">
            <div class="rounded-3 border p-3 mb-3" style="background:#f8fafc">
              <p class="fw-medium text-dark mb-1" style="font-size:12px">Konfigurasi API Supplier</p>
              <p class="text-muted mb-3" style="font-size:11px">Endpoint JSON generik. Isi path JSON untuk tiap siklus, misalnya <code>data.prices.monthly</code>. Token disimpan terenkripsi.</p>
              <div class="row g-3">
                <div class="col-12">
                  <label class="text-muted mb-1 d-block" style="font-size:11px">URL Endpoint</label>
                  <input type="url" name="supplier_api_url" value="<?php echo e(old('supplier_api_url', $addon->supplier_api_url)); ?>" class="form-control form-control-sm" placeholder="https://supplier.example/api/prices">
                  <?php $__errorArgs = ['supplier_api_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-sm-4">
                  <label class="text-muted mb-1 d-block" style="font-size:11px">Metode</label>
                  <select name="supplier_http_method" class="form-select form-select-sm">
                    <option value="GET" <?php if(old('supplier_http_method', $addon->supplier_http_method ?: 'GET') === 'GET'): echo 'selected'; endif; ?>>GET</option>
                    <option value="POST" <?php if(old('supplier_http_method', $addon->supplier_http_method) === 'POST'): echo 'selected'; endif; ?>>POST</option>
                  </select>
                </div>
                <div class="col-sm-8">
                  <label class="text-muted mb-1 d-block" style="font-size:11px">Bearer Token <?php if($addon->exists): ?><span class="fw-normal">(kosongkan untuk mempertahankan token lama)</span><?php endif; ?></label>
                  <input type="password" name="supplier_api_token" value="" class="form-control form-control-sm" autocomplete="new-password">
                </div>
                <?php $__currentLoopData = $cycleLabels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cycle => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <div class="col-sm-6">
                    <label class="text-muted mb-1 d-block" style="font-size:11px">Path harga <?php echo e(strtolower($label)); ?></label>
                    <input type="text" name="supplier_price_path_<?php echo e($cycle); ?>" value="<?php echo e(old('supplier_price_path_'.$cycle, $addon->{'supplier_price_path_'.$cycle})); ?>" class="form-control form-control-sm" placeholder="data.prices.<?php echo e($cycle); ?>">
                  </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </div>
            </div>

            <p class="fw-medium text-dark mb-2" style="font-size:12px">Harga modal hasil sinkronisasi</p>
            <div class="row g-2 mb-2">
              <?php $__currentLoopData = $cycleLabels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cycle => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php $cost = $addon->{'cost_price_'.$cycle}; ?>
                <div class="col-6 col-sm-3">
                  <div class="border rounded-3 px-3 py-2">
                    <div class="text-muted" style="font-size:11px"><?php echo e($label); ?></div>
                    <div class="small fw-semibold text-dark" data-cost-view="<?php echo e($cycle); ?>" data-value="<?php echo e($cost !== null ? (int) $cost : ''); ?>"><?php echo e($cost !== null ? 'Rp ' . number_format($cost, 0, ',', '.') : '—'); ?></div>
                  </div>
                </div>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <?php if($addon->supplier_last_synced_at): ?>
              <p class="text-muted mb-1" style="font-size:11px">Sync terakhir: <?php echo e($addon->supplier_last_synced_at->format('d M Y H:i')); ?></p>
            <?php endif; ?>
            <?php if($addon->supplier_last_error): ?>
              <p class="text-danger mb-1" style="font-size:11px"><?php echo e($addon->supplier_last_error); ?></p>
            <?php endif; ?>
            <p class="text-muted mb-0" style="font-size:11px">
              <?php if($addon->exists && $addon->isApiPricing()): ?>
                Simpan perubahan konfigurasi dulu, lalu klik <strong>Sync Harga Modal</strong> di panel kanan.
              <?php else: ?>
                Simpan addon ini dulu; tombol <strong>Sync Harga Modal</strong> muncul di halaman edit setelah sumber harga tersimpan sebagai API.
              <?php endif; ?>
            </p>
          </div>
        </div>

        
        <details class="addon-details card border rounded-4 mb-3" <?php if($hasDetails): ?> open <?php endif; ?>>
          <summary class="p-4 d-flex align-items-center justify-content-between gap-3">
            <span class="d-flex align-items-center gap-2">
              <span class="addon-step">4</span>
              <span>
                <span class="fw-semibold text-dark d-block">Keterangan Produk Lengkap <span class="badge badge-soft-secondary ms-1" style="font-size:10px">Opsional</span></span>
                <span class="text-muted d-block" style="font-size:11px">Tampil di halaman detail produk. Isi satu item per baris.</span>
              </span>
            </span>
            <i class="fa-solid fa-chevron-down chev text-muted" style="font-size:12px"></i>
          </summary>
          <div class="px-4 pb-4">
            <div class="mb-3">
              <label class="text-muted mb-1 d-block" style="font-size:11px">Penjelasan panjang</label>
              <textarea name="long_description" rows="4" class="form-control form-control-sm"><?php echo e(old('long_description', $addon->long_description)); ?></textarea>
              <?php $__errorArgs = ['long_description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="mb-3">
              <label class="text-muted mb-1 d-block" style="font-size:11px">Fitur / keunggulan (satu per baris)</label>
              <textarea name="features_text" rows="5" class="form-control form-control-sm" placeholder="Enkripsi 256-bit&#10;Terbit dalam hitungan menit"><?php echo e(old('features_text', implode("\n", $addon->features ?? []))); ?></textarea>
              <?php $__errorArgs = ['features_text'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="mb-3">
              <label class="text-muted mb-1 d-block" style="font-size:11px">Spesifikasi (format <code>Label: nilai</code>, satu per baris)</label>
              <textarea name="specs_text" rows="5" class="form-control form-control-sm" placeholder="Tipe validasi: Domain Validation (DV)&#10;Cakupan domain: 1 nama domain"><?php echo e(old('specs_text', collect($addon->specs ?? [])->map(fn ($v, $k) => "$k: $v")->implode("\n"))); ?></textarea>
              <?php $__errorArgs = ['specs_text'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div>
              <label class="text-muted mb-1 d-block" style="font-size:11px">FAQ (format <code>Pertanyaan | Jawaban</code>, satu per baris)</label>
              <textarea name="faqs_text" rows="5" class="form-control form-control-sm"><?php echo e(old('faqs_text', collect($addon->faqs ?? [])->map(fn ($x) => $x['q'].' | '.$x['a'])->implode("\n"))); ?></textarea>
              <?php $__errorArgs = ['faqs_text'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
          </div>
        </details>

      </div>

      
      <div class="col-12 col-lg-4">
        <div class="addon-side">
          <div class="card border rounded-4 p-4 mb-3">
            <p class="fw-semibold text-dark mb-3">Status &amp; Tampilan</p>

            <div class="form-check form-switch mb-1">
              <input type="checkbox" name="is_active" value="1" id="isActive" <?php if(old('is_active', $addon->is_active ?? true)): echo 'checked'; endif; ?> class="form-check-input">
              <label for="isActive" class="form-check-label small text-dark">Aktif</label>
            </div>
            <p class="text-muted mb-3" style="font-size:11px">Bisa dipesan &amp; dipasang klien.</p>

            <div class="form-check form-switch mb-1">
              <input type="checkbox" name="is_public" value="1" id="isPublic" <?php if(old('is_public', $addon->is_public ?? true)): echo 'checked'; endif; ?> class="form-check-input">
              <label for="isPublic" class="form-check-label small text-dark">Tampil di katalog publik</label>
            </div>
            <p class="text-muted mb-3" style="font-size:11px">Muncul di halaman Lisensi &amp; SSL.</p>

            <label class="form-label small fw-medium text-dark mb-1">Urutan Tampil</label>
            <input type="number" name="sort_order" value="<?php echo e(old('sort_order', $addon->sort_order ?? 0)); ?>" min="0" class="form-control form-control-sm">
            <?php $__errorArgs = ['sort_order'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            <p class="text-muted mt-1 mb-0" style="font-size:11px">Angka kecil tampil lebih dulu.</p>
          </div>

          <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
            <?php if($addon->exists && $addon->isApiPricing()): ?>
              <button type="submit" form="sync-addon-form" class="btn btn-outline-primary btn-sm">Sync Harga Modal</button>
            <?php endif; ?>
            <a href="<?php echo e(route('admin.addons.index')); ?>" class="btn btn-outline-secondary btn-sm">Batal</a>
          </div>
        </div>
      </div>

    </div>
  </form>

  <?php if($addon->exists && $addon->isApiPricing()): ?>
    <form id="sync-addon-form" method="POST" action="<?php echo e(route('admin.addons.sync', $addon)); ?>" class="d-none"><?php echo csrf_field(); ?></form>
  <?php endif; ?>

  <script <?php echo 'nonce="' . e(app(\App\Support\CspNonce::class)->value()) . '"'; ?>>
    (function () {
      // ---- Slug otomatis dari nama ----
      const name = document.getElementById('nameInput');
      const slug = document.getElementById('slugInput');
      const slugify = (s) => s.toLowerCase().trim()
        .replace(/[^a-z0-9\s-]/g, '')
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-');
      let slugTouched = slug.value.length > 0;
      slug.addEventListener('input', () => { slugTouched = true; });
      name.addEventListener('input', () => { if (!slugTouched) slug.value = slugify(name.value); });

      // ---- Sumber harga modal: hanya panel yang dipilih yang tampil & ikut terkirim ----
      const radios = document.querySelectorAll('input[name="pricing_source"]');
      const panels = document.querySelectorAll('[data-src-panel]');
      const opts = document.querySelectorAll('[data-src-opt]');

      function applySource() {
        const current = document.querySelector('input[name="pricing_source"]:checked')?.value || 'manual';
        panels.forEach((p) => {
          const active = p.dataset.srcPanel === current;
          p.classList.toggle('d-none', !active);
          // Field di panel yang tersembunyi dinonaktifkan supaya tidak terkirim
          // dan tidak menimpa data yang sudah tersimpan.
          p.querySelectorAll('input, select, textarea').forEach((el) => { el.disabled = !active; });
        });
        opts.forEach((o) => o.classList.toggle('is-selected', o.dataset.srcOpt === current));
        updateMargins();
      }

      // ---- Margin langsung: harga jual - harga modal ----
      const fmt = (n) => 'Rp ' + Math.round(n).toLocaleString('id-ID');
      function costFor(cycle) {
        const input = document.querySelector('[data-cost="' + cycle + '"]');
        if (input && !input.disabled) return input.value === '' ? null : parseFloat(input.value);
        const view = document.querySelector('[data-cost-view="' + cycle + '"]');
        return view && view.dataset.value !== '' ? parseFloat(view.dataset.value) : null;
      }
      function updateMargins() {
        document.querySelectorAll('[data-margin]').forEach((el) => {
          const cycle = el.dataset.margin;
          const priceInput = document.querySelector('[data-price="' + cycle + '"]');
          const price = priceInput && priceInput.value !== '' ? parseFloat(priceInput.value) : null;
          const cost = costFor(cycle);
          if (price === null || cost === null) { el.textContent = ''; el.className = 'margin-hint text-muted mt-1'; return; }
          const margin = price - cost;
          const pct = price > 0 ? (margin / price * 100).toFixed(1) : '0';
          el.textContent = 'Modal ' + fmt(cost) + ' · Margin ' + fmt(margin) + ' (' + pct + '%)';
          el.className = 'margin-hint mt-1 ' + (margin < 0 ? 'text-danger' : 'text-success');
        });
      }

      radios.forEach((r) => r.addEventListener('change', applySource));
      document.querySelectorAll('[data-price],[data-cost]').forEach((el) => el.addEventListener('input', updateMargins));
      applySource();
    })();
  </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/admin/addons/form.blade.php ENDPATH**/ ?>