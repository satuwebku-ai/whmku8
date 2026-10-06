

<div style="background:#f8fafc;border-top:1px solid #e2e8f0;border-bottom:1px solid #e2e8f0;padding:5rem 0">
  <div style="max-width:72rem;margin:0 auto;padding:0 1.5rem">
    <div style="text-align:center;margin-bottom:3rem">
      <h2 style="font-weight:700;color:#1e293b;font-size:1.75rem;letter-spacing:-.02em;margin:0 0 .5rem 0">VPS &amp; Cloud Server</h2>
      <p style="color:#64748b;font-size:15px;margin:0">Kontrol penuh dengan akses root, aktif otomatis dalam hitungan menit.</p>
    </div>

    <div style="display:flex;flex-wrap:wrap;gap:1.5rem">
      <?php $__currentLoopData = $vpsProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div style="flex:1 1 300px;min-width:280px;max-width:400px">
          <?php echo $__env->make('public.catalog._product-card', ['product' => $product], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <div style="text-align:center;margin-top:2.5rem">
      <a href="<?php echo e(route('catalog.index')); ?>" class="btn btn-outline-secondary">
        Lihat Semua Paket <i class="fa-solid fa-arrow-right" style="font-size:11px"></i>
      </a>
    </div>
  </div>
</div>
<?php /**PATH /home/runner/workspace/hosting-billing/resources/views/themes/public-themes/default/public/home/_vps.blade.php ENDPATH**/ ?>