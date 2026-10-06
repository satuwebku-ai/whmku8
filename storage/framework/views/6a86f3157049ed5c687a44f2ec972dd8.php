
    <div style="max-width:72rem;margin:0 auto;padding:5rem 1.5rem">
    <div style="text-align:center;margin-bottom:3rem">
      <h2 style="font-weight:700;color:#1e293b;font-size:1.75rem;letter-spacing:-.02em;margin:0 0 .5rem 0">Paket Hosting Pilihan</h2>
      <p style="color:#64748b;font-size:15px;margin:0">Mulai kecil, naik kelas kapan saja tanpa pindah server.</p>
    </div>

    <?php if($featured->isEmpty()): ?>
      <div class="card-public" style="padding:3rem;text-align:center">
        <p style="color:#64748b;font-size:14px;margin:0 0 .25rem 0">Katalog sedang disiapkan.</p>
        <p style="color:#94a3b8;font-size:12px;margin:0">
          Belum ada produk yang bisa ditampilkan — tambahkan lewat menu Produk di admin panel.
        </p>
      </div>
    <?php else: ?>
      <div style="display:flex;flex-wrap:wrap;gap:1.5rem">
        <?php $__currentLoopData = $featured; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <div style="flex:1 1 300px;min-width:280px;max-width:400px">
            <?php echo $__env->make('public.catalog._product-card', ['product' => $product], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
          </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </div>

      <div style="text-align:center;margin-top:3rem">
        <a href="<?php echo e(route('catalog.index')); ?>" class="btn btn-outline-secondary" style="padding:.6rem 1.5rem">
          Lihat Semua Paket <i class="fa-solid fa-arrow-right" style="font-size:12px"></i>
        </a>
      </div>
    <?php endif; ?>
  </div>
<?php /**PATH /home/runner/workspace/hosting-billing/resources/views/themes/public-themes/default/public/home/_hosting.blade.php ENDPATH**/ ?>