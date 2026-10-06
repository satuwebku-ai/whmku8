<?php $__env->startSection('title', $product->exists ? 'Edit Produk' : 'Tambah Produk'); ?>

<?php $__env->startSection('content'); ?>
  <?php $selectStyle = 'padding:.25rem .6rem;font-size:.875rem;border-radius:.375rem'; ?>

  <div class="mb-4 d-flex align-items-start justify-content-between gap-3 flex-wrap">
    <div>
      <h1 class="h4 fw-bold text-dark mb-1"><?php echo e($product->exists ? 'Edit Produk' : 'Tambah Produk'); ?></h1>
      <?php if($product->exists && $product->category): ?>
        <p class="small text-muted mb-0">
          URL: <a href="<?php echo e($product->category->productUrl($product)); ?>" target="_blank" class="text-accent"><?php echo e($product->category->productUrl($product)); ?></a>
        </p>
      <?php endif; ?>
    </div>
    <?php if($product->exists): ?>
      <a href="<?php echo e(route('admin.products.options.index', $product)); ?>" class="btn btn-outline-secondary btn-sm flex-shrink-0">
        <i class="fa-solid fa-sliders"></i> Kelola Opsi Konfigurasi
      </a>
    <?php endif; ?>
  </div>

  <?php if($categories->isEmpty()): ?>
    <div class="card border rounded-4 p-4 text-center text-muted small" style="max-width:42rem">
      Belum ada kategori produk.
      <a href="<?php echo e(route('admin.product-categories.create')); ?>" class="text-accent">Buat kategori dulu</a>.
    </div>
  <?php else: ?>
    <form method="POST" action="<?php echo e($product->exists ? route('admin.products.update', $product) : route('admin.products.store')); ?>" class="row g-3" style="max-width:70rem">
      <?php echo csrf_field(); ?>
      <?php if($product->exists): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>

      <div class="col-12 col-lg-8">
        <div class="card border rounded-4 p-4 mb-3">
          <div class="row g-3 mb-3">
            <div class="col-sm-6">
              <label class="form-label small fw-medium text-dark">Kategori</label>
              <select name="product_category_id" id="categorySelect" class="form-select" style="<?php echo e($selectStyle); ?>" required>
                <option value="">Pilih kategori</option>
                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <option value="<?php echo e($cat->id); ?>" data-type="<?php echo e($cat->type ?? 'hosting'); ?>" <?php if(old('product_category_id', $product->product_category_id) == $cat->id): echo 'selected'; endif; ?>>
                    <?php echo e($cat->name); ?> — <?php echo e(($cat->type ?? 'hosting') === 'vps' ? 'VPS' : 'Hosting'); ?>

                  </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </select>
              <?php $__errorArgs = ['product_category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="col-sm-6">
              <label class="form-label small fw-medium text-dark">Nama Produk</label>
              <input type="text" name="name" id="nameInput" value="<?php echo e(old('name', $product->name)); ?>" class="form-control form-control-sm" required placeholder="Cloud Hosting - Pro">
              <?php $__errorArgs = ['name'];
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
            <label class="form-label small fw-medium text-dark">
              Slug URL
              <span id="slugAutoBadge" class="text-muted fw-normal" style="font-size:10.5px">(terisi otomatis saat mengetik Nama Produk)</span>
            </label>
            <input type="text" name="slug" id="slugInput" value="<?php echo e(old('slug', $product->slug)); ?>" class="form-control form-control-sm" placeholder="otomatis dari nama">
            <?php $__errorArgs = ['slug'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>

          <script <?php echo 'nonce="' . e(app(\App\Support\CspNonce::class)->value()) . '"'; ?>>
            (function () {
              const name = document.getElementById('nameInput');
              const slug = document.getElementById('slugInput');
              const badge = document.getElementById('slugAutoBadge');

              const slugify = (s) => s.toLowerCase().trim()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-');

              // slugTouched jadi true begitu klien MENGETIK sendiri di kolom
              // slug -- sejak itu auto-fill berhenti supaya tidak menimpa
              // slug yang sudah sengaja diedit manual.
              let slugTouched = slug.value.length > 0;

              function markTouched() {
                slugTouched = true;
                badge.classList.add('d-none');
              }

              function autofill() {
                if (! slugTouched) slug.value = slugify(name.value);
              }

              // 'input' menangkap ketik biasa; 'paste' + 'change' jadi jaring
              // pengaman untuk kasus isi lewat paste atau autofill browser
              // yang kadang tidak memicu event 'input' di semua browser.
              slug.addEventListener('input', markTouched);
              name.addEventListener('input', autofill);
              name.addEventListener('change', autofill);
              name.addEventListener('paste', () => setTimeout(autofill, 0));

              // Kalau field Nama sudah terisi duluan (mis. balik dari halaman
              // lain, atau autofill browser sebelum listener terpasang),
              // langsung sinkron sekali di awal -- bukan menunggu klien
              // mengetik ulang supaya slug baru muncul.
              autofill();
            })();
          </script>

          <div class="mb-3">
            <label class="form-label small fw-medium text-dark">Tagline <span class="text-muted fw-normal">(1 baris, tampil di kartu produk)</span></label>
            <input type="text" name="tagline" maxlength="255" value="<?php echo e(old('tagline', $product->tagline)); ?>" class="form-control form-control-sm" placeholder="Cocok untuk website bisnis & toko online">
          </div>

          <div class="mb-3">
            <label class="form-label small fw-medium text-dark">Deskripsi</label>
            <textarea name="description" rows="5" class="form-control form-control-sm"><?php echo e(old('description', $product->description)); ?></textarea>
          </div>

          <div>
            <label class="form-label small fw-medium text-dark">Daftar Fitur <span class="text-muted fw-normal">(satu per baris)</span></label>
            <textarea name="features_raw" rows="6" class="form-control form-control-sm" style="font-family:monospace;font-size:12px" placeholder="10 GB SSD Storage&#10;Unlimited Bandwidth&#10;Free SSL&#10;1 Domain"><?php echo e(old('features_raw', $product->features ? implode("\n", $product->features) : '')); ?></textarea>
          </div>
        </div>

        <div class="card border rounded-4 p-4 mb-3" id="pricingCyclesCard">
          <h2 class="small fw-bold text-dark mb-1">Harga per Siklus Tagihan</h2>
          <p class="text-muted mb-3" style="font-size:12px">Kosongkan siklus yang tidak dijual untuk produk ini. Minimal isi satu.</p>
          <?php $__errorArgs = ['price_monthly'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mb-2" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

          <div class="row g-3 mb-3">
            <?php $__currentLoopData = \App\Models\Product::CYCLES; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <div class="col-sm-6">
                <label class="form-label small fw-medium text-dark"><?php echo e($label); ?></label>
                <input type="number" step="0.01" name="price_<?php echo e($key); ?>" value="<?php echo e(old('price_' . $key, $product->{'price_' . $key})); ?>" class="form-control form-control-sm" placeholder="Kosongkan jika tidak dijual">
              </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </div>

          <?php if(auth('admin')->user()->isSuperadmin()): ?>
            <div class="mb-3" style="max-width:16rem">
              <label class="form-label small fw-medium text-dark">Jumlah Hari untuk Siklus "Custom" <span class="text-warning fw-normal" style="font-size:11px"><i class="fa-solid fa-lock"></i> Superadmin</span></label>
              <input type="number" min="1" name="custom_cycle_days" value="<?php echo e(old('custom_cycle_days', $product->custom_cycle_days)); ?>" class="form-control form-control-sm" placeholder="Contoh: 45">
              <?php $__errorArgs = ['custom_cycle_days'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
              <p class="text-muted mt-1 mb-0" style="font-size:11px">Cuma dipakai kalau kolom "Custom" di atas diisi harga.</p>
            </div>
          <?php endif; ?>

          <div>
            <label class="form-label small fw-medium text-dark">Biaya Setup <span class="text-muted fw-normal">(sekali bayar, opsional)</span></label>
            <input type="number" step="0.01" name="setup_fee" value="<?php echo e(old('setup_fee', $product->setup_fee ?? 0)); ?>" class="form-control form-control-sm">
          </div>
        </div>

        <div class="card border rounded-4 p-4">
          <h2 class="small fw-bold text-dark mb-1">Provisioning Otomatis</h2>
          <p class="text-muted mb-3" style="font-size:12px">
            Data ini menentukan ke server mana dan dengan paket apa akun cPanel dibuat otomatis
            saat order produk ini lunas. Boleh dikosongkan kalau provisioning-nya manual.
          </p>

          <div class="row g-3">
            <div class="col-sm-6">
              <label class="form-label small fw-medium text-dark">Server Tujuan</label>
              <select name="server_id" id="serverSelect" class="form-select" style="<?php echo e($selectStyle); ?>" data-server-edit-base="<?php echo e(url('/admin/servers')); ?>/__ID__/edit">
                <option value="">— Manual, tanpa auto-provisioning —</option>
                <?php $__currentLoopData = $servers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $srv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <option value="<?php echo e($srv->id); ?>" data-kind="<?php echo e($srv->isCloud() ? 'vps' : 'hosting'); ?>"
                          <?php if(old('server_id', $product->server_id) == $srv->id): echo 'selected'; endif; ?>>
                    <?php echo e($srv->name); ?><?php echo e($srv->isCloud() ? ' (Cloud/VPS · ' . $srv->vpsLabel() . ')' : ' (cPanel)'); ?>

                  </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </select>
              <p class="text-muted mt-1 mb-0" style="font-size:11px">Hanya server yang cocok dengan jenis kategori yang ditampilkan.</p>
            </div>
            <div class="col-sm-6 d-none" id="serverPackageField">
              <label class="form-label small fw-medium text-dark">Paket Server</label>
              <select name="server_package_id" id="serverPackageSelect" class="form-select form-select-sm">
                <option value="">— Tidak ditautkan ke inventaris paket —</option>
                <?php $__currentLoopData = $serverPackages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $package): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <option value="<?php echo e($package->id); ?>" data-server-id="<?php echo e($package->server_id); ?>"
                          <?php if(old('server_package_id', $product->server_package_id) == $package->id): echo 'selected'; endif; ?>>
                    <?php echo e($package->name); ?><?php echo e($package->status !== 'active' ? ' (nonaktif)' : ''); ?>

                    · disk <?php echo e($package->disk_limit ?? '∞'); ?> GB
                    · bandwidth <?php echo e($package->bandwidth_limit ?? '∞'); ?> GB
                  </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </select>
              <?php $__errorArgs = ['server_package_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
              <p class="text-muted mt-1 mb-0" style="font-size:11px">Paket terpilih disalin ke nama plan provider saat akun dibuat. Kelola inventaris dari halaman Server.</p>
            </div>
            <div class="col-sm-6" id="cpanelPackageField">
              <label class="form-label small fw-medium text-dark">Nama Package di WHM/cPanel <span class="text-muted fw-normal">(opsional)</span></label>
              <input type="text" name="panel_package" id="panelPackageInput" value="<?php echo e(old('panel_package', $product->panel_package)); ?>" class="form-control form-control-sm" placeholder="cloud_hosting_pro">
              <p class="text-muted mt-1 mb-0" style="font-size:11px">Isi manual jika belum memakai inventaris paket; nilainya harus sama persis dengan nama plan di panel.</p>
            </div>
          </div>

          
          <?php
            $vmSpec = json_decode((string) $product->panel_package, true);
            $vmSpec = is_array($vmSpec) && isset($vmSpec['vcpu']) ? $vmSpec : [];
          ?>
          <div id="vpsSpecFields" class="d-none mt-3 pt-3 border-top">
            <p class="fw-bold text-muted mb-2" style="font-size:11px;text-transform:uppercase;letter-spacing:.03em">
              <i class="fa-solid fa-microchip"></i> Spesifikasi VPS
            </p>
            
            <?php $__currentLoopData = config('vps_providers', []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $driver => $providerCfg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <?php if(! empty($providerCfg['product_fields'])): ?>
                <div class="row g-3 mb-3 d-none" data-provider-fields="<?php echo e($driver); ?>">
                  <?php $__currentLoopData = $providerCfg['product_fields']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fKey => $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-sm-6 col-lg-4">
                      <label class="form-label small fw-medium text-dark"><?php echo e($field['label']); ?> <span class="text-muted fw-normal">(<?php echo e($providerCfg['label']); ?>)</span></label>
                      <input type="text" name="vm_<?php echo e($fKey); ?>" value="<?php echo e(old('vm_' . $fKey, $vmSpec[$fKey] ?? ($field['default'] ?? ''))); ?>"
                             placeholder="<?php echo e($field['placeholder'] ?? ''); ?>" class="form-control form-control-sm" data-source="<?php echo e($field['source'] ?? ''); ?>" autocomplete="off" disabled>
                      <?php $__errorArgs = ['vm_' . $fKey];
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
              <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <datalist id="dlSizes"></datalist>
            <datalist id="dlRegions"></datalist>

            <div id="componentSpecFields">
              <p id="sizeModelNote" class="text-muted mb-2 d-none" style="font-size:11px">
                <i class="fa-solid fa-circle-info"></i> Spesifikasi (vCPU, RAM, disk) mengikuti <b>size</b> yang dipilih di atas — tidak perlu diisi manual.
              </p>
            <div class="row g-3">
              <div class="col-6 col-lg-3">
                <label class="form-label small fw-medium text-dark">vCPU (Core)</label>
                <input type="number" name="vm_vcpu" id="vmVcpu" min="1" max="16" value="<?php echo e(old('vm_vcpu', $vmSpec['vcpu'] ?? 1)); ?>" class="form-control form-control-sm">
              </div>
              <div class="col-6 col-lg-3">
                <label class="form-label small fw-medium text-dark">RAM (MB)</label>
                <input type="number" name="vm_ram" id="vmRam" min="512" step="512" value="<?php echo e(old('vm_ram', $vmSpec['ram'] ?? 1024)); ?>" class="form-control form-control-sm">
                <p class="text-muted mt-1 mb-0" style="font-size:10px">1024 = 1 GB</p>
              </div>
              <div class="col-6 col-lg-3">
                <label class="form-label small fw-medium text-dark">Disk (GB)</label>
                <input type="number" name="vm_disk" id="vmDisk" min="20" value="<?php echo e(old('vm_disk', $vmSpec['disk'] ?? 20)); ?>" class="form-control form-control-sm">
              </div>
              <div class="col-6 col-lg-3">
                <label class="form-label small fw-medium text-dark">Backup Otomatis</label>
                <select name="vm_backup" id="vmBackup" class="form-select form-select-sm">
                  <option value="0" <?php if(! ($vmSpec['backup_enabled'] ?? false)): echo 'selected'; endif; ?>>Tidak</option>
                  <option value="1" <?php if($vmSpec['backup_enabled'] ?? false): echo 'selected'; endif; ?>>Ya (biaya tambahan)</option>
                </select>
              </div>
            </div>
            </div>

            
            <div id="vpsCostBox" class="rounded-3 border px-3 py-2 mt-3 small d-none" style="background:#f8fafc"></div>

            <div class="mt-3 pt-3 border-top">
              <label class="form-label small fw-medium text-dark">Cara Menagih</label>
              <select name="billing_mode" id="vmBillingMode" class="form-select form-select-sm" style="max-width:22rem">
                <option value="invoice" <?php if(old('billing_mode', $product->billing_mode ?? 'invoice') === 'invoice'): echo 'selected'; endif; ?>>Invoice Berkala (bulanan, dst)</option>
                <option value="deposit" <?php if(old('billing_mode', $product->billing_mode) === 'deposit'): echo 'selected'; endif; ?>>Potong Saldo per Jam</option>
              </select>
              <p class="text-muted mt-1 mb-0" style="font-size:11px">
                <b>Invoice berkala</b>: ditagih seperti hosting biasa, pakai harga &amp; siklus di kartu "Harga per Siklus Tagihan" di atas.
                <br><b>Saldo per jam</b>: klien topup dulu, dipotong otomatis tiap jam sesuai pemakaian — kartu harga siklus disembunyikan, diganti kartu harga per-jam di bawah.
              </p>
            </div>

            
            <div id="hourlyPricingCard" class="d-none mt-3 pt-3 border-top">
              <p class="fw-bold text-muted mb-1" style="font-size:11px;text-transform:uppercase;letter-spacing:.03em">
                <i class="fa-solid fa-tags"></i> Kartu Harga (per jam) — khusus produk ini
              </p>
              <p class="text-muted mb-3" style="font-size:11px">
                Kosongkan semuanya kalau mau ikut kartu harga server tujuan (cadangan lama). Isi di sini kalau produk
                ini perlu harga jual sendiri, beda dari produk VPS lain yang kebetulan satu server.
              </p>

              <?php $__errorArgs = ['pricing_mode'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mb-2" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

              <div class="row g-2 mb-3">
                <?php $__currentLoopData = ['manual' => 'Isi Manual', 'markup' => 'Markup % dari Harga Modal Server']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mKey => $mLabel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <?php $activeMode = old('pricing_mode', $product->pricing_mode ?? 'manual') === $mKey; ?>
                  <div class="col-6">
                    <label class="d-flex align-items-center justify-content-center rounded-3 border px-2 py-2 text-center small fw-medium w-100"
                           style="cursor:pointer;<?php echo e($activeMode ? 'border-color:#4f46e5!important;background:rgba(79,70,229,.06);color:#4338ca' : ''); ?>">
                      <input type="radio" name="pricing_mode" value="<?php echo e($mKey); ?>" <?php if($activeMode): echo 'checked'; endif; ?> class="d-none" data-product-pricing-mode>
                      <?php echo e($mLabel); ?>

                    </label>
                  </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </div>

              <div id="productMarkupFields" class="<?php echo e(old('pricing_mode', $product->pricing_mode ?? 'manual') === 'markup' ? '' : 'd-none'); ?> rounded-3 border p-3 mb-3" style="background:#f8fafc">
                <div class="row g-3 align-items-end">
                  <div class="col-sm-5">
                    <label class="form-label small fw-medium text-dark">Markup (%)</label>
                    <input type="number" step="0.01" min="0" name="markup_percent" value="<?php echo e(old('markup_percent', $product->markup_percent ?? 50)); ?>" class="form-control form-control-sm">
                    <p class="text-muted mt-1 mb-0" style="font-size:10px">Mis. 50 = jual 1,5× harga modal server tujuan.</p>
                  </div>
                  <div class="col-sm-7">
                    <?php if($product->server_id && $product->server?->cost_cached_at): ?>
                      <p class="text-muted mb-1" style="font-size:11px">
                        Harga modal server tujuan (tersimpan <?php echo e($product->server->cost_cached_at->diffForHumans()); ?>):
                        vCPU <?php echo e(number_format((float) ($product->server->cost_cache['vcpu'] ?? 0), 3)); ?> ·
                        RAM <?php echo e(number_format((float) ($product->server->cost_cache['ram'] ?? 0), 3)); ?> ·
                        Disk <?php echo e(number_format((float) ($product->server->cost_cache['storage'] ?? 0), 3)); ?>

                      </p>
                      <a href="<?php echo e(route('admin.servers.edit', $product->server_id)); ?>" target="_blank" class="text-decoration-underline" style="font-size:11px">Refresh harga modal di halaman Server →</a>
                    <?php elseif($product->server_id): ?>
                      <p class="mb-0" style="font-size:11px;color:#b45309">
                        <i class="fa-solid fa-triangle-exclamation"></i> Server tujuan belum pernah ditarik harga modalnya —
                        <a href="<?php echo e(route('admin.servers.edit', $product->server_id)); ?>" target="_blank" style="color:inherit" class="text-decoration-underline">tarik dulu di halaman Server</a>, baru mode markup bisa menghitung.
                      </p>
                    <?php else: ?>
                      <p class="text-muted mb-0" style="font-size:11px">Pilih &amp; simpan "Server Tujuan" dulu, baru harga modalnya bisa dibaca di sini.</p>
                    <?php endif; ?>
                  </div>
                </div>
              </div>

              <div id="productManualRateFields" class="<?php echo e(old('pricing_mode', $product->pricing_mode ?? 'manual') === 'markup' ? 'd-none' : ''); ?>">
                <div class="row g-3">
                  <div class="col-sm-6 col-lg-4">
                    <label class="form-label small fw-medium text-dark">Harga per vCPU</label>
                    <input type="number" step="0.000001" min="0" name="price_per_vcpu_hour" value="<?php echo e(old('price_per_vcpu_hour', $product->price_per_vcpu_hour)); ?>" class="form-control form-control-sm">
                  </div>
                  <div class="col-sm-6 col-lg-4">
                    <label class="form-label small fw-medium text-dark">Harga per GB RAM</label>
                    <input type="number" step="0.000001" min="0" name="price_per_ram_gb_hour" value="<?php echo e(old('price_per_ram_gb_hour', $product->price_per_ram_gb_hour)); ?>" class="form-control form-control-sm">
                  </div>
                  <div class="col-sm-6 col-lg-4">
                    <label class="form-label small fw-medium text-dark">Harga per GB Storage</label>
                    <input type="number" step="0.000001" min="0" name="price_per_storage_gb_hour" value="<?php echo e(old('price_per_storage_gb_hour', $product->price_per_storage_gb_hour)); ?>" class="form-control form-control-sm">
                  </div>
                  <div class="col-sm-6 col-lg-4">
                    <label class="form-label small fw-medium text-dark">Harga per GB Backup</label>
                    <input type="number" step="0.000001" min="0" name="price_per_backup_gb_hour" value="<?php echo e(old('price_per_backup_gb_hour', $product->price_per_backup_gb_hour)); ?>" class="form-control form-control-sm">
                  </div>
                  <div class="col-sm-6 col-lg-4">
                    <label class="form-label small fw-medium text-dark">Harga per GB Snapshot</label>
                    <input type="number" step="0.000001" min="0" name="price_per_snapshot_gb_hour" value="<?php echo e(old('price_per_snapshot_gb_hour', $product->price_per_snapshot_gb_hour)); ?>" class="form-control form-control-sm">
                  </div>
                  <div class="col-sm-6 col-lg-4">
                    <label class="form-label small fw-medium text-dark">Lisensi Windows per vCPU</label>
                    <input type="number" step="0.000001" min="0" name="price_windows_license_per_vcpu_hour" value="<?php echo e(old('price_windows_license_per_vcpu_hour', $product->price_windows_license_per_vcpu_hour)); ?>" class="form-control form-control-sm">
                  </div>
                </div>
              </div>
            </div>

            <p class="text-muted mt-3 mb-0" style="font-size:11px">
              <i class="fa-solid fa-circle-info"></i>
              OS &amp; aplikasi dipilih klien sendiri saat memesan — jadi satu paket ini berlaku untuk semua OS.
              Estimasi biaya modal bisa dilihat di halaman Diagnosa server.
            </p>
          </div>
        </div>
      </div>

      <div class="col-12 col-lg-4">
        <div class="card border rounded-4 p-4 mb-3">
          <h2 class="small fw-bold text-dark mb-2">Domain</h2>
          <select name="domain_option" class="form-select" style="<?php echo e($selectStyle); ?>">
            <option value="none" <?php if(old('domain_option', $product->domain_option ?? 'optional') === 'none'): echo 'selected'; endif; ?>>Tidak terkait domain</option>
            <option value="optional" <?php if(old('domain_option', $product->domain_option) === 'optional'): echo 'selected'; endif; ?>>Opsional (boleh pakai domain sendiri)</option>
            <option value="required" <?php if(old('domain_option', $product->domain_option) === 'required'): echo 'selected'; endif; ?>>Wajib disertai domain</option>
          </select>
        </div>

        <div class="card border rounded-4 p-4">
          <h2 class="small fw-bold text-dark mb-2">Publikasi</h2>

          <label class="d-flex align-items-center gap-2 small text-dark mb-2">
            <input type="checkbox" name="is_active" value="1" <?php if(old('is_active', $product->is_active ?? true)): echo 'checked'; endif; ?> class="form-check-input" style="margin-top:0">
            Aktif (tampil di katalog)
          </label>
          <label class="d-flex align-items-center gap-2 small text-dark mb-3">
            <input type="checkbox" name="is_featured" value="1" <?php if(old('is_featured', $product->is_featured)): echo 'checked'; endif; ?> class="form-check-input" style="margin-top:0">
            Tandai sebagai Unggulan
          </label>

          <div class="mb-3">
            <label class="form-label small fw-medium text-dark">Stok <span class="text-muted fw-normal">(opsional)</span></label>
            <input type="number" name="stock" value="<?php echo e(old('stock', $product->stock)); ?>" class="form-control form-control-sm" placeholder="Kosongkan = tidak dibatasi">
          </div>

          <div class="mb-3">
            <label class="form-label small fw-medium text-dark">Urutan Tampil</label>
            <input type="number" name="sort_order" value="<?php echo e(old('sort_order', $product->sort_order ?? 0)); ?>" class="form-control form-control-sm">
          </div>

          <div class="d-flex flex-column gap-2 pt-2 border-top">
            <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-check" style="font-size:11px"></i> Simpan Produk</button>
            <a href="<?php echo e(route('admin.products.index')); ?>" class="btn btn-outline-secondary btn-sm">Batal</a>
          </div>
        </div>
      </div>
    </form>
  <?php endif; ?>

  <script <?php echo 'nonce="' . e(app(\App\Support\CspNonce::class)->value()) . '"'; ?>>
    (function () {
      const catSelect = document.getElementById('categorySelect');
      const serverSelect = document.getElementById('serverSelect');
      if (! catSelect || ! serverSelect) return;

      const vpsFields = document.getElementById('vpsSpecFields');
      const cpanelField = document.getElementById('cpanelPackageField');
      const packageField = document.getElementById('serverPackageField');
      const packageSelect = document.getElementById('serverPackageSelect');
      const billingModeSelect = document.getElementById('vmBillingMode');
      const pricingCard = document.getElementById('pricingCyclesCard');
      const hourlyCard = document.getElementById('hourlyPricingCard');
      const serverMeta = <?php echo json_encode($vpsServerMeta, 15, 512) ?>;
      const componentFields = document.getElementById('componentSpecFields');
      const sizeNote = document.getElementById('sizeModelNote');
      const costBox = document.getElementById('vpsCostBox');
      const form = catSelect.form;

      // Simpan semua opsi server aslinya, supaya bisa disaring
      // bolak-balik tanpa kehilangan pilihan.
      const allServerOptions = Array.from(serverSelect.options).map(o => ({
        value: o.value, text: o.text, kind: o.dataset.kind || '',
      }));
      const allPackageOptions = Array.from(packageSelect.options).map(o => ({
        value: o.value, text: o.text, serverId: o.dataset.serverId || '',
      }));

      function currentType() {
        return catSelect.selectedOptions[0]?.dataset.type || 'hosting';
      }

      // Kartu "Harga per Siklus Tagihan" & kartu "Harga per Jam" cuma
      // relevan salah satu, tergantung Cara Menagih -- ditampilkan
      // gantian, bukan dua-duanya sekaligus dari awal seperti sebelumnya.
      function syncBillingMode() {
        const isVps = currentType() === 'vps';
        const isDeposit = isVps && billingModeSelect.value === 'deposit';

        pricingCard.classList.toggle('d-none', isDeposit);
        hourlyCard.classList.toggle('d-none', ! isDeposit);
      }

      function sync() {
        const isVps = currentType() === 'vps';
        const keep = serverSelect.value;

        // Saring pilihan server: kategori VPS hanya boleh server cloud,
        // kategori hosting hanya boleh server cPanel.
        serverSelect.innerHTML = '';
        allServerOptions
          .filter(o => o.value === '' || o.kind === (isVps ? 'vps' : 'hosting'))
          .forEach(function (o) {
            const opt = document.createElement('option');
            opt.value = o.value;
            opt.textContent = o.text;
            opt.dataset.kind = o.kind;
            if (o.value === keep) opt.selected = true;
            serverSelect.appendChild(opt);
          });

        vpsFields.classList.toggle('d-none', ! isVps);
        cpanelField.classList.toggle('d-none', isVps);
        const selectedServer = serverSelect.value;
        const selectedPackage = packageSelect.value;
        packageSelect.innerHTML = '';
        allPackageOptions
          .filter(o => o.value === '' || o.serverId === selectedServer)
          .forEach(function (o) {
            const opt = document.createElement('option');
            opt.value = o.value;
            opt.textContent = o.text;
            opt.dataset.serverId = o.serverId;
            if (o.value === selectedPackage) opt.selected = true;
            packageSelect.appendChild(opt);
          });
        const canUsePackageInventory = ! isVps && !! selectedServer;
        packageField.classList.toggle('d-none', ! canUsePackageInventory);
        packageSelect.disabled = ! canUsePackageInventory;

        // Field "Cara Menagih" cuma berlaku untuk produk VPS -- dinonaktifkan
        // (bukan cuma disembunyikan) untuk kategori hosting/domain supaya
        // TIDAK ikut ter-submit sama sekali, dan billing_mode produk hosting
        // selalu jatuh ke default "invoice" di server, bukan diam-diam
        // kebawa nilai "deposit" dari select yang kebetulan tersembunyi.
        billingModeSelect.disabled = ! isVps;

        syncBillingMode();
        syncProvider();
      }

      // Isian khusus provider server yang dipilih (mis. size/image/region
      // DigitalOcean) + mode spek: provider berbasis size menentukan
      // vCPU/RAM/disk dari size-nya, jadi isian komponen disembunyikan.
      function syncProvider() {
        const meta = currentType() === 'vps' ? serverMeta[serverSelect.value] : null;

        document.querySelectorAll('[data-provider-fields]').forEach(function (box) {
          const on = !! meta && box.dataset.providerFields === meta.driver;
          box.classList.toggle('d-none', ! on);
          box.querySelectorAll('input').forEach(function (i) { i.disabled = ! on; });
        });

        const sizeModel = !! meta && meta.model === 'size';
        componentFields.classList.toggle('d-none', sizeModel);
        componentFields.querySelectorAll('input,select').forEach(function (i) { i.disabled = sizeModel; });
        sizeNote.classList.toggle('d-none', ! sizeModel);

        fillDatalist('dlSizes', meta ? meta.sizes : {});
        fillDatalist('dlRegions', meta ? meta.regions : {});
        document.querySelectorAll('[data-provider-fields] input[data-source]').forEach(function (i) {
          const list = { sizes: 'dlSizes', regions: 'dlRegions' }[i.dataset.source];
          if (list) i.setAttribute('list', list); else i.removeAttribute('list');
        });

        estimate();
      }

      function fillDatalist(id, items) {
        const dl = document.getElementById(id);
        dl.innerHTML = '';
        Object.entries(items).forEach(function ([value, label]) {
          const opt = document.createElement('option');
          opt.value = value;
          opt.label = label;
          dl.appendChild(opt);
        });
      }

      // Estimasi harga modal provider (+ tarif jual per jam untuk mode
      // deposit) dari server -- rumusnya sama dengan yang dipakai saat
      // menyimpan & menagih, jadi tidak ada hitungan ganda di JavaScript.
      let estimateTimer = null;
      function estimate() {
        clearTimeout(estimateTimer);
        estimateTimer = setTimeout(runEstimate, 350);
      }

      function runEstimate() {
        if (currentType() !== 'vps' || ! serverMeta[serverSelect.value]) {
          costBox.classList.add('d-none');
          return;
        }

        const body = new FormData(form);
        body.delete('_method'); // form edit memakai PUT; endpoint estimasi cuma menerima POST

        fetch(<?php echo json_encode(route('admin.products.vps-estimate'), 15, 512) ?>, {
          method: 'POST',
          headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
          body: body,
        })
          .then(function (r) { return r.json(); })
          .then(renderEstimate)
          .catch(function () { costBox.classList.add('d-none'); });
      }

      const rp = function (n, d) { return 'Rp ' + Number(n).toLocaleString('id-ID', { minimumFractionDigits: d || 0, maximumFractionDigits: d || 0 }); };

      function renderEstimate(res) {
        costBox.classList.remove('d-none');

        if (! res.ok) {
          costBox.style.color = '#b45309';
          costBox.textContent = res.message;
          return;
        }

        if (! res.ready) {
          costBox.style.color = '#b45309';
          costBox.textContent = ! res.synced
            ? 'Harga modal ' + res.provider + ' belum ditarik — tarik dulu di halaman Server.'
            : (res.fx_missing ? 'Kurs ' + res.currency + ' ke Rupiah belum diisi di halaman Server.' : 'Harga modal untuk spek ini tidak ditemukan — cek size/spek.');
          return;
        }

        costBox.style.color = '#334155';
        let html = '<b>Harga modal ' + res.provider + '</b>: ' + rp(res.modal_hourly, 2) + ' / jam · ± ' + rp(res.modal_monthly) + ' / bulan (730 jam)';

        if (res.sell_hourly !== null) {
          const below = res.sell_hourly > 0 && res.sell_hourly < res.modal_hourly;
          html += '<br><b>Tarif jual</b>: ' + rp(res.sell_hourly, 2) + ' / jam'
            + (res.sell_hourly <= 0 ? ' <span style="color:#b91c1c">— masih 0, produk tidak akan ditagih</span>' : '')
            + (below ? ' <span style="color:#b91c1c">— di bawah modal (rugi)</span>' : '');
        }

        costBox.innerHTML = html;
      }

      catSelect.addEventListener('change', sync);
      billingModeSelect.addEventListener('change', function () { syncBillingMode(); estimate(); });
      serverSelect.addEventListener('change', function () { syncBillingMode(); syncProvider(); });
      // Semua isian yang memengaruhi spek/tarif memicu estimasi ulang.
      form.addEventListener('input', estimate);
      form.addEventListener('change', estimate);
      sync();
    })();
  </script>

  <script <?php echo 'nonce="' . e(app(\App\Support\CspNonce::class)->value()) . '"'; ?>>
    (function () {
      const radios = document.querySelectorAll('[data-product-pricing-mode]');
      const markupBox = document.getElementById('productMarkupFields');
      const manualBox = document.getElementById('productManualRateFields');
      if (! radios.length) return;

      function sync() {
        const mode = document.querySelector('[data-product-pricing-mode]:checked')?.value;
        markupBox.classList.toggle('d-none', mode !== 'markup');
        manualBox.classList.toggle('d-none', mode === 'markup');

        radios.forEach(function (r) {
          const label = r.closest('label');
          const on = r.checked;
          label.style.borderColor = on ? '#4f46e5' : '';
          label.style.background = on ? 'rgba(79,70,229,.06)' : '';
          label.style.color = on ? '#4338ca' : '';
        });
      }

      radios.forEach(r => r.addEventListener('change', sync));
      sync();
    })();
  </script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/admin/products/form.blade.php ENDPATH**/ ?>