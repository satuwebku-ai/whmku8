<?php $__env->startSection('title', 'Chat — ' . $chat->display_name); ?>

<?php $__env->startSection('content'); ?>
  <?php echo $__env->make('admin.chats._workspace', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/admin/chats/show.blade.php ENDPATH**/ ?>