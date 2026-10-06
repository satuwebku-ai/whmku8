<?php
  $actTabs = [
    ['label' => 'Aktivitas', 'route' => 'admin.activities'],
    ['label' => 'Kirim Promo', 'route' => 'admin.promo'],
  ];
?>

<div class="d-flex align-items-center gap-1 mb-4 border-bottom flex-wrap">
  <?php $__currentLoopData = $actTabs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tab): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <a href="<?php echo e(route($tab['route'])); ?>"
       class="px-3 py-2 small fw-medium text-decoration-none border-bottom border-2 <?php echo e(request()->routeIs(str_replace('.bootstrap-preview', '', $tab['route'])) ? 'border-primary text-accent' : 'border-transparent text-muted'); ?>">
      <?php echo e($tab['label']); ?>

    </a>
  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php /**PATH /home/runner/workspace/hosting-billing/resources/views/admin/activities/_nav.blade.php ENDPATH**/ ?>