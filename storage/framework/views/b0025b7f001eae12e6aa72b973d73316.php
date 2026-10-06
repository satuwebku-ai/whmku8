<?php
  $seoTitle = 'Keranjang Belanja';
?>

<?php $__env->startSection('content'); ?>

  <h1 class="fw-bold text-dark mb-4" style="font-size:1.6rem">Keranjang Belanja</h1>

  <div class="row g-4">
    
    <div class="col-12 col-lg-4 d-flex flex-column gap-3">
      <?php if($categories->isNotEmpty()): ?>
        <div class="card-public overflow-hidden">
          <div class="px-3 py-3 fw-semibold text-white" style="background:#1e293b;font-size:14px">Kategori Layanan</div>
          <div>
            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <a href="<?php echo e($category->publicUrl()); ?>" class="d-flex align-items-center justify-content-between px-3 py-2 text-decoration-none text-muted border-bottom" style="font-size:14px">
                <?php echo e($category->name); ?>

                <span class="text-muted" style="font-size:12px"><?php echo e($category->products_count); ?></span>
              </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </div>
        </div>
      <?php endif; ?>

      <div class="card-public overflow-hidden">
        <div class="px-3 py-3 fw-semibold text-white" style="background:#1e293b;font-size:14px">Aksi</div>
        <div>
          <a href="<?php echo e(route('domain.search')); ?>" class="d-flex align-items-center gap-2 px-3 py-2 text-decoration-none text-muted border-bottom" style="font-size:14px">
            <i class="fa-solid fa-globe text-center" style="width:16px"></i> Daftarkan Domain Baru
          </a>
          <a href="<?php echo e(route('catalog.index')); ?>" class="d-flex align-items-center gap-2 px-3 py-2 text-decoration-none text-muted border-bottom" style="font-size:14px">
            <i class="fa-solid fa-server text-center" style="width:16px"></i> Lihat Paket Hosting
          </a>
          <span class="d-flex align-items-center gap-2 px-3 py-2 fw-medium text-theme" style="font-size:14px;background:rgba(79,70,229,.05)">
            <i class="fa-solid fa-cart-shopping text-center" style="width:16px"></i> Keranjang (<?php echo e(count($items)); ?>)
          </span>
        </div>
      </div>
    </div>

    <div class="col-12 col-lg-8">
      <?php if(empty($items)): ?>
        <div class="card-public p-5 text-center">
          <i class="fa-solid fa-cart-shopping text-muted mb-3" style="font-size:2rem"></i>
          <p class="text-muted mb-4">Keranjang Anda masih kosong.</p>
          <a href="<?php echo e(route('catalog.index')); ?>" class="btn btn-theme">Lihat Paket Hosting</a>
        </div>
      <?php else: ?>
        <div class="row g-4">
          <div class="col-12 col-lg-8 d-flex flex-column gap-3">
            <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <div class="card-public p-4 d-flex align-items-start justify-content-between gap-3 flex-wrap">
                <div class="d-flex align-items-start gap-3 min-w-0">
                  <span class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width:40px;height:40px;<?php echo e(in_array($item['type'], ['domain', 'domain_premium'], true) ? 'background:rgba(6,182,212,.14);color:#0891b2' : 'background:rgba(79,70,229,.12);color:#4f46e5'); ?>">
                    <i class="fa-solid <?php echo e(in_array($item['type'], ['domain', 'domain_premium'], true) ? 'fa-globe' : 'fa-server'); ?>"></i>
                  </span>
                  <div class="min-w-0">
                    <?php if($item['type'] === 'domain_premium'): ?>
                      <p class="fw-semibold text-dark mb-0">
                        <?php echo e($item['domain_name']); ?>

                        <span class="badge ms-1" style="background:#fef3c7;color:#92400e;font-weight:600;font-size:10px">Premium</span>
                      </p>
                      <p class="text-muted mb-0 mt-1" style="font-size:11px">Registrasi domain premium — 1 tahun (harga tetap, tidak bisa diubah)</p>
                    <?php elseif($item['type'] === 'product'): ?>
                      <p class="fw-semibold text-dark mb-0"><?php echo e($item['name']); ?></p>

                      <form method="POST" action="<?php echo e(route('cart.update-cycle')); ?>" class="mt-2">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="key" value="<?php echo e($item['key']); ?>">
                        <select name="billing_cycle" data-auto-submit class="form-select form-select-sm" style="width:auto">
                          <?php $__currentLoopData = \App\Models\Product::CYCLES; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ck => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($ck); ?>" <?php if($item['billing_cycle'] === $ck): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
                          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                      </form>

                      <?php if(!empty($item['domain_name'])): ?>
                        <p class="text-muted mt-2 mb-0" style="font-size:11px">
                          <i class="fa-solid fa-globe" style="font-size:10px"></i>
                          <?php echo e($item['domain_mode'] === 'register' ? 'Daftar baru' : 'Domain sendiri'); ?>: <?php echo e($item['domain_name']); ?>

                        </p>
                      <?php endif; ?>

                      <?php if(!empty($item['selected_options'])): ?>
                        <ul class="list-unstyled mt-2 mb-0 d-flex flex-column gap-1">
                          <?php $__currentLoopData = $item['selected_options']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="text-muted d-flex align-items-center gap-1" style="font-size:11px">
                              <i class="fa-solid fa-plus" style="font-size:9px"></i>
                              <?php echo e($opt['name']); ?>

                              <span class="text-dark fw-medium">— Rp <?php echo e(number_format($opt['price'], 0, ',', '.')); ?></span>
                            </li>
                          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                      <?php endif; ?>
                    <?php elseif($item['type'] === 'addon'): ?>
                      <p class="fw-semibold text-dark mb-0"><?php echo e($item['name']); ?></p>
                      <p class="text-muted mt-1 mb-0" style="font-size:11px">Lisensi digital<?php echo e(! empty($item['license_ip']) ? ' · IP ' . $item['license_ip'] : ''); ?></p>
                      <form method="POST" action="<?php echo e(route('cart.update-cycle')); ?>" class="mt-2">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="key" value="<?php echo e($item['key']); ?>">
                        <select name="billing_cycle" data-auto-submit class="form-select form-select-sm" style="width:auto">
                          <?php $__currentLoopData = ['monthly' => 'Bulanan', 'quarterly' => '3 Bulan', 'semi_annually' => '6 Bulan', 'annually' => 'Tahunan']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ck => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($ck); ?>" <?php if($item['billing_cycle'] === $ck): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
                          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                      </form>
                    <?php else: ?>
                      <p class="fw-semibold text-dark mb-0"><?php echo e($item['domain_name']); ?></p>
                      <form method="POST" action="<?php echo e(route('cart.update-years')); ?>" class="mt-2">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="key" value="<?php echo e($item['key']); ?>">
                        <select name="years" data-auto-submit class="form-select form-select-sm" style="width:auto">
                          <?php for($y = 1; $y <= 10; $y++): ?>
                            <option value="<?php echo e($y); ?>" <?php if($item['years'] == $y): echo 'selected'; endif; ?>><?php echo e($y); ?> Tahun</option>
                          <?php endfor; ?>
                        </select>
                      </form>

                      
                      <div class="mt-3 rounded-3 border overflow-hidden">
                        
                        <div class="d-flex align-items-center gap-2 px-3 py-2 border-bottom" style="background:rgba(16,185,129,.05)">
                          <span class="rounded-2 d-flex align-items-center justify-content-center flex-shrink-0" style="width:28px;height:28px;background:rgba(16,185,129,.14);color:#047857">
                            <i class="fa-solid fa-server" style="font-size:11px"></i>
                          </span>
                          <div class="flex-grow-1 min-w-0">
                            <p class="fw-medium text-dark mb-0" style="font-size:12px">DNS Management</p>
                            <p class="text-muted mb-0" style="font-size:11px">Kelola DNS lewat panel setelah domain aktif</p>
                          </div>
                          <span class="badge badge-soft-success flex-shrink-0" style="font-size:10px">Termasuk</span>
                        </div>

                        
                        <?php if($item['whois_privacy_eligible'] ?? true): ?>
                          <form method="POST" action="<?php echo e(route('cart.toggle-privacy')); ?>">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="key" value="<?php echo e($item['key']); ?>">
                            <label class="d-flex align-items-center gap-2 px-3 py-2" style="cursor:pointer">
                              <span class="rounded-2 d-flex align-items-center justify-content-center flex-shrink-0" style="width:28px;height:28px;<?php echo e(($item['whois_privacy'] ?? false) ? 'background:rgba(79,70,229,.12);color:#4f46e5' : 'background:#f1f5f9;color:#94a3b8'); ?>">
                                <i class="fa-solid fa-user-shield" style="font-size:11px"></i>
                              </span>
                              <div class="flex-grow-1 min-w-0">
                                <p class="fw-medium text-dark mb-0" style="font-size:12px">ID Protection</p>
                                <p class="text-muted mb-0" style="font-size:11px">Sembunyikan data pribadi dari WHOIS publik</p>
                              </div>
                              <span class="fw-medium flex-shrink-0" style="font-size:11px;<?php echo e(($item['whois_privacy_price'] ?? 0) > 0 ? 'color:#64748b' : 'color:#047857'); ?>">
                                <?php echo e(($item['whois_privacy_price'] ?? 0) > 0 ? '+Rp ' . number_format($item['whois_privacy_price'], 0, ',', '.') . '/thn' : 'Gratis'); ?>

                              </span>
                              <input type="checkbox" data-auto-submit <?php if($item['whois_privacy'] ?? false): echo 'checked'; endif; ?>
                                     class="form-check-input flex-shrink-0" style="margin:0">
                            </label>
                          </form>
                        <?php else: ?>
                          <div class="d-flex align-items-center gap-2 px-3 py-2">
                            <span class="rounded-2 d-flex align-items-center justify-content-center flex-shrink-0" style="width:28px;height:28px;background:#f1f5f9;color:#94a3b8">
                              <i class="fa-solid fa-circle-info" style="font-size:11px"></i>
                            </span>
                            <div class="flex-grow-1 min-w-0">
                              <p class="fw-medium text-dark mb-0" style="font-size:12px">ID Protection tidak tersedia</p>
                              <p class="text-muted mb-0" style="font-size:11px">Domain .id dan turunannya wajib menampilkan data pendaftar sesuai aturan PANDI.</p>
                            </div>
                          </div>
                        <?php endif; ?>
                      </div>
                    <?php endif; ?>
                  </div>
                </div>

                <div class="text-end flex-shrink-0">
                  <p class="fw-semibold text-dark mb-0">Rp <?php echo e(number_format($item['price'], 0, ',', '.')); ?></p>
                  <?php if(!empty($item['setup_fee'])): ?>
                    <p class="text-muted mb-0" style="font-size:11px">+ setup Rp <?php echo e(number_format($item['setup_fee'], 0, ',', '.')); ?></p>
                  <?php endif; ?>
                  <form method="POST" action="<?php echo e(route('cart.remove')); ?>" class="mt-2">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="key" value="<?php echo e($item['key']); ?>">
                    <button type="submit" class="btn btn-link p-0 text-danger" style="font-size:12px;text-decoration:none">
                      <i class="fa-regular fa-trash-can"></i> Hapus
                    </button>
                  </form>
                </div>
              </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <form method="POST" action="<?php echo e(route('cart.clear')); ?>"
                  data-confirm="Kosongkan seluruh keranjang?" data-confirm-title="Kosongkan Keranjang"
                  data-confirm-style="danger" data-confirm-label="Ya, Kosongkan">
              <?php echo csrf_field(); ?>
              <button type="submit" class="btn btn-link p-0 text-muted" style="font-size:12px;text-decoration:none">
                <i class="fa-regular fa-trash-can"></i> Kosongkan keranjang
              </button>
            </form>
          </div>

          <div class="col-12 col-lg-4">
            <div class="card-public p-4" style="position:sticky;top:6rem">
              <h2 class="small fw-bold text-dark mb-3">Ringkasan</h2>
              <div class="d-flex justify-content-between mb-2" style="font-size:14px">
                <span class="text-muted">Subtotal</span>
                <span class="fw-medium text-dark">Rp <?php echo e(number_format($subtotal, 0, ',', '.')); ?></span>
              </div>
              <p class="text-muted mb-3" style="font-size:11px">Biaya setup (jika ada) & pajak dihitung saat checkout.</p>

              <a href="<?php echo e(route('client.checkout')); ?>" class="btn btn-theme w-100">
                <i class="fa-solid fa-lock" style="font-size:12px"></i> Lanjut ke Checkout
              </a>
              <a href="<?php echo e(route('catalog.index')); ?>" class="btn btn-outline-secondary w-100 mt-2">Lanjut Belanja</a>
            </div>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('public.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/themes/public-themes/namahost/public/cart/index.blade.php ENDPATH**/ ?>