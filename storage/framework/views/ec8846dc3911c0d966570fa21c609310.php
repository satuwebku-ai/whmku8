
<?php
  $current = $paginator->currentPage();
  $last = $paginator->lastPage();
  $from = $paginator->firstItem() ?? 0;
  $to = $paginator->lastItem() ?? 0;
  $window = collect(range(max(1, $current - 2), min($last, $current + 2)));
?>
<div class="d-flex flex-wrap align-items-center justify-content-between gap-2" style="font-size:13px">
  <span class="text-muted">
    Menampilkan <?php echo e(number_format($from, 0, ',', '.')); ?>–<?php echo e(number_format($to, 0, ',', '.')); ?>

    dari <?php echo e(number_format($paginator->total(), 0, ',', '.')); ?> domain
    · Halaman <?php echo e($current); ?> dari <?php echo e($last); ?>

  </span>

  <nav aria-label="Navigasi halaman">
    <ul class="pagination pagination-sm mb-0">
      <li class="page-item <?php echo e($current <= 1 ? 'disabled' : ''); ?>">
        <?php if($current > 1): ?>
          <a class="page-link" href="<?php echo e($paginator->url(1)); ?>" aria-label="Halaman pertama">&laquo;</a>
        <?php else: ?>
          <span class="page-link" aria-disabled="true">&laquo;</span>
        <?php endif; ?>
      </li>
      <li class="page-item <?php echo e($paginator->onFirstPage() ? 'disabled' : ''); ?>">
        <?php if(! $paginator->onFirstPage()): ?>
          <a class="page-link" href="<?php echo e($paginator->previousPageUrl()); ?>" rel="prev">&lsaquo; Sebelumnya</a>
        <?php else: ?>
          <span class="page-link" aria-disabled="true">&lsaquo; Sebelumnya</span>
        <?php endif; ?>
      </li>

      <?php if($window->first() > 1): ?>
        <li class="page-item"><a class="page-link" href="<?php echo e($paginator->url(1)); ?>">1</a></li>
        <?php if($window->first() > 2): ?><li class="page-item disabled"><span class="page-link">…</span></li><?php endif; ?>
      <?php endif; ?>

      <?php $__currentLoopData = $window; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <li class="page-item <?php echo e($page === $current ? 'active' : ''); ?>" <?php if($page === $current): ?> aria-current="page" <?php endif; ?>>
          <?php if($page === $current): ?>
            <span class="page-link"><?php echo e($page); ?></span>
          <?php else: ?>
            <a class="page-link" href="<?php echo e($paginator->url($page)); ?>"><?php echo e($page); ?></a>
          <?php endif; ?>
        </li>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

      <?php if($window->last() < $last): ?>
        <?php if($window->last() < $last - 1): ?><li class="page-item disabled"><span class="page-link">…</span></li><?php endif; ?>
        <li class="page-item"><a class="page-link" href="<?php echo e($paginator->url($last)); ?>"><?php echo e($last); ?></a></li>
      <?php endif; ?>

      <li class="page-item <?php echo e(! $paginator->hasMorePages() ? 'disabled' : ''); ?>">
        <?php if($paginator->hasMorePages()): ?>
          <a class="page-link" href="<?php echo e($paginator->nextPageUrl()); ?>" rel="next">Berikutnya &rsaquo;</a>
        <?php else: ?>
          <span class="page-link" aria-disabled="true">Berikutnya &rsaquo;</span>
        <?php endif; ?>
      </li>
      <li class="page-item <?php echo e($current >= $last ? 'disabled' : ''); ?>">
        <?php if($current < $last): ?>
          <a class="page-link" href="<?php echo e($paginator->url($last)); ?>" aria-label="Halaman terakhir">&raquo;</a>
        <?php else: ?>
          <span class="page-link" aria-disabled="true">&raquo;</span>
        <?php endif; ?>
      </li>
    </ul>
  </nav>
</div>
<?php /**PATH /home/runner/workspace/hosting-billing/resources/views/pagination/pager.blade.php ENDPATH**/ ?>