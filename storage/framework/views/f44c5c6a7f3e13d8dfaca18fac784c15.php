<?php $__env->startSection('title', 'Tiket Support'); ?>

<?php $__env->startSection('content'); ?>
  <?php
    $badgeMap = ['active' => 'badge-soft-success', 'pending' => 'badge-soft-warning', 'inactive' => 'badge-soft-secondary', 'suspended' => 'badge-soft-danger'];
  ?>

  <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
    <div>
      <h1 class="h4 fw-bold text-dark mb-1">Tiket Support</h1>
      <p class="text-muted mb-0">Riwayat percakapan Anda dengan tim kami.</p>
    </div>
    <a href="<?php echo e(route('client.tickets.create')); ?>" class="btn btn-theme">
      <i class="fa-solid fa-plus" style="font-size:11px"></i> Buat Tiket
    </a>
  </div>

  <div class="d-flex gap-2 mb-4">
    <?php $s = request('status'); ?>
    <a href="<?php echo e(route('client.tickets')); ?>" class="px-3 py-2 rounded-pill text-decoration-none" style="font-size:12px;font-weight:500;<?php echo e(!$s ? 'background:var(--lumora-theme);color:#fff' : 'background:#fff;border:1px solid #e2e8f0;color:#475569'); ?>">Semua</a>
    <a href="<?php echo e(route('client.tickets', ['status' => 'open'])); ?>" class="px-3 py-2 rounded-pill text-decoration-none" style="font-size:12px;font-weight:500;<?php echo e($s === 'open' ? 'background:var(--lumora-theme);color:#fff' : 'background:#fff;border:1px solid #e2e8f0;color:#475569'); ?>">Aktif</a>
    <a href="<?php echo e(route('client.tickets', ['status' => 'closed'])); ?>" class="px-3 py-2 rounded-pill text-decoration-none" style="font-size:12px;font-weight:500;<?php echo e($s === 'closed' ? 'background:var(--lumora-theme);color:#fff' : 'background:#fff;border:1px solid #e2e8f0;color:#475569'); ?>">Ditutup</a>
  </div>

  <div class="d-flex flex-column gap-3">
    <?php $__empty_1 = true; $__currentLoopData = $tickets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ticket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
      <a href="<?php echo e(route('client.tickets.show', $ticket)); ?>" class="dash-card dash-card-hover p-4 d-flex align-items-center justify-content-between gap-3 text-decoration-none">
        <div class="d-flex align-items-center gap-3 min-w-0">
          <span class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width:40px;height:40px;background:rgba(16,185,129,.1);color:#047857">
            <i class="fa-solid fa-comments" style="font-size:15px"></i>
          </span>
          <div class="min-w-0">
            <p class="fw-semibold text-dark text-truncate mb-0"><?php echo e($ticket->subject); ?></p>
            <p class="text-muted mt-1 mb-0" style="font-size:11px">
              <?php echo e($ticket->ticket_number); ?> · <?php echo e($ticket->public_replies_count); ?> pesan ·
              update <?php echo e($ticket->last_reply_at?->diffForHumans()); ?>

            </p>
          </div>
        </div>
        <div class="text-end flex-shrink-0">
          <span class="badge <?php echo e($badgeMap[$ticket->status_badge] ?? 'badge-soft-secondary'); ?>"><?php echo e($ticket->status_label); ?></span>
        </div>
      </a>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
      <div class="dash-card p-5 text-center">
        <span class="rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width:44px;height:44px;background:#f1f5f9;color:#94a3b8">
          <i class="fa-solid fa-comments"></i>
        </span>
        <p class="text-muted mb-3" style="font-size:14px">Belum ada tiket.</p>
        <a href="<?php echo e(route('client.tickets.create')); ?>" class="btn btn-theme">Buat Tiket Pertama</a>
      </div>
    <?php endif; ?>
  </div>

  <?php if($tickets->hasPages()): ?>
    <div class="mt-4"><?php echo e($tickets->links('pagination.bootstrap')); ?></div>
  <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('client.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/themes/client-themes/namahost/client/tickets/index.blade.php ENDPATH**/ ?>