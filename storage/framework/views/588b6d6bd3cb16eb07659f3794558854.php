<?php $__env->startSection('title', 'Aktivitas'); ?>

<?php $__env->startSection('content'); ?>

  <?php echo $__env->make('admin.activities._nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

  <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
    <div>
      <h1 class="h4 fw-bold text-dark mb-1">Aktivitas Aplikasi</h1>
      <p class="small text-muted mb-0">Catatan kejadian penting: pesanan, pembayaran, tiket, dan pendaftaran klien.</p>
    </div>
    <div class="d-flex align-items-center gap-2">
      <?php if($counts['unread'] > 0): ?>
        <form method="POST" action="<?php echo e(route('admin.activities.read-all')); ?>">
          <?php echo csrf_field(); ?>
          <button type="submit" class="btn btn-outline-secondary btn-sm"><i class="fa-solid fa-check-double" style="font-size:11px"></i> Tandai Semua Dibaca</button>
        </form>
      <?php endif; ?>
      <form method="POST" action="<?php echo e(route('admin.activities.clear-old')); ?>"
            data-confirm="Hapus catatan yang sudah dibaca dan berumur lebih dari 30 hari?"
            data-confirm-title="Bersihkan Catatan Lama" data-confirm-style="warn" data-confirm-label="Ya, Bersihkan">
        <?php echo csrf_field(); ?>
        <button type="submit" class="btn btn-outline-secondary btn-sm"><i class="fa-regular fa-trash-can" style="font-size:11px"></i> Bersihkan Lama</button>
      </form>
    </div>
  </div>

  <div class="d-flex align-items-center gap-2 mb-4 flex-wrap">
    <a href="<?php echo e(route('admin.activities')); ?>"
       class="px-3 py-2 small fw-medium text-decoration-none rounded-pill <?php echo e(! request('type') && ! request('unread') ? 'text-white' : 'text-muted'); ?>"
       style="<?php echo e(! request('type') && ! request('unread') ? 'background:#4f46e5' : 'background:#f1f5f9'); ?>">
      Semua (<?php echo e($counts['all']); ?>)
    </a>
    <a href="<?php echo e(route('admin.activities', ['unread' => 1])); ?>"
       class="px-3 py-2 small fw-medium text-decoration-none rounded-pill <?php echo e(request('unread') ? 'text-white' : 'text-muted'); ?>"
       style="<?php echo e(request('unread') ? 'background:#4f46e5' : 'background:#f1f5f9'); ?>">
      Belum Dibaca (<?php echo e($counts['unread']); ?>)
    </a>
    <?php $__currentLoopData = ['order' => 'Order', 'payment' => 'Pembayaran', 'ticket' => 'Tiket', 'client' => 'Klien', 'invoice' => 'Invoice']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <a href="<?php echo e(route('admin.activities', ['type' => $t])); ?>"
         class="px-3 py-2 small fw-medium text-decoration-none rounded-pill <?php echo e(request('type') === $t ? 'text-white' : 'text-muted'); ?>"
         style="<?php echo e(request('type') === $t ? 'background:#4f46e5' : 'background:#f1f5f9'); ?>">
        <?php echo e($label); ?>

      </a>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </div>

  <div class="card border rounded-4 overflow-hidden">
    <div>
      <?php
        $levelStyle = fn ($level) => match ($level) {
            'success' => ['bg' => 'rgba(16,185,129,.14)', 'fg' => '#047857'],
            'warning' => ['bg' => 'rgba(245,158,11,.16)', 'fg' => '#b45309'],
            'danger' => ['bg' => 'rgba(244,63,94,.14)', 'fg' => '#e11d48'],
            default => ['bg' => 'rgba(79,70,229,.12)', 'fg' => '#4338ca'],
        };
      ?>
      <?php $__empty_1 = true; $__currentLoopData = $activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <?php $style = $levelStyle($activity->level); ?>
        <div class="d-flex align-items-start gap-3 px-4 py-3 border-bottom" style="<?php echo e($activity->read_at ? '' : 'background:rgba(79,70,229,.04)'); ?>">
          <span class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width:36px;height:36px;background:<?php echo e($style['bg']); ?>;color:<?php echo e($style['fg']); ?>">
            <i class="fa-solid <?php echo e($activity->icon); ?>" style="font-size:14px"></i>
          </span>

          <div class="flex-grow-1 min-w-0">
            <p class="small fw-medium text-dark mb-0">
              <?php echo e($activity->title); ?>

              <?php if (! ($activity->read_at)): ?>
                <span class="rounded-circle bg-primary d-inline-block ms-1" style="width:8px;height:8px;vertical-align:middle"></span>
              <?php endif; ?>
            </p>
            <?php if($activity->description): ?>
              <p class="text-muted mb-0 mt-1" style="font-size:12px"><?php echo e($activity->description); ?></p>
            <?php endif; ?>
            <p class="text-muted mb-0 mt-1" style="font-size:11px"><?php echo e($activity->created_at->diffForHumans()); ?></p>
          </div>

          <div class="d-flex align-items-center gap-2 flex-shrink-0">
            <?php if($activity->link): ?>
              <a href="<?php echo e(route('admin.activities.open', $activity)); ?>" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center justify-content-center" style="width:32px;height:32px;padding:0" title="Buka">
                <i class="fa-solid fa-arrow-right" style="font-size:12px"></i>
              </a>
            <?php endif; ?>
            <?php if (! ($activity->read_at)): ?>
              <form method="POST" action="<?php echo e(route('admin.activities.read', $activity)); ?>">
                <?php echo csrf_field(); ?>
                <button type="submit" class="btn btn-outline-success btn-sm d-inline-flex align-items-center justify-content-center" style="width:32px;height:32px;padding:0" title="Tandai sudah dibaca" aria-label="Tandai sudah dibaca">
                  <i class="fa-solid fa-check" style="font-size:12px"></i>
                </button>
              </form>
            <?php endif; ?>
            <form method="POST" action="<?php echo e(route('admin.activity.delete', $activity)); ?>">
              <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
              <button type="submit" class="btn btn-outline-danger btn-sm d-inline-flex align-items-center justify-content-center" style="width:32px;height:32px;padding:0" title="Hapus">
                <i class="fa-regular fa-trash-can" style="font-size:12px"></i>
              </button>
            </form>
          </div>
        </div>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="text-center py-5">
          <p class="small text-dark mb-1">Belum ada aktivitas tercatat.</p>
          <p class="text-muted mb-0" style="font-size:12px">Kejadian akan muncul di sini saat ada pesanan, pembayaran, atau tiket masuk.</p>
        </div>
      <?php endif; ?>
    </div>

    <?php if($activities->hasPages()): ?>
      <div class="px-4 py-3 border-top"><?php echo e($activities->links('pagination.bootstrap')); ?></div>
    <?php endif; ?>
  </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/admin/activities/index.blade.php ENDPATH**/ ?>