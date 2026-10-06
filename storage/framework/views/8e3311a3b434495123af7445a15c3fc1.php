<?php $__env->startSection('title', $ticket->ticket_number); ?>

<?php $__env->startSection('content'); ?>
  <?php
    $badgeMap = ['active' => 'badge-soft-success', 'pending' => 'badge-soft-warning', 'inactive' => 'badge-soft-secondary'];
  ?>

  <a href="<?php echo e(route('client.tickets')); ?>" class="text-decoration-none text-muted" style="font-size:12px">&larr; Kembali ke Tiket</a>

  <div class="d-flex align-items-center justify-content-between mt-2 mb-4 flex-wrap gap-3">
    <div>
      <h1 class="h4 fw-bold text-dark mb-0"><?php echo e($ticket->subject); ?></h1>
      <p class="text-muted mb-0"><?php echo e($ticket->ticket_number); ?> · dibuat <?php echo e($ticket->created_at->format('d M Y H:i')); ?></p>
    </div>
    <span class="badge <?php echo e($badgeMap[$ticket->status_badge] ?? 'badge-soft-secondary'); ?>"><?php echo e($ticket->status_label); ?></span>
  </div>

  
  <div class="d-flex flex-column gap-3 mb-4">
    <?php $__currentLoopData = $ticket->publicReplies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reply): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <div class="card-public p-4" style="<?php echo e($reply->isFromStaff() ? 'border-color:#c7d2fe!important;background:rgba(79,70,229,.04)' : ''); ?>">
        <div class="d-flex align-items-center gap-2 mb-2">
          <span class="rounded-circle d-flex align-items-center justify-content-center fw-bold flex-shrink-0"
                style="width:32px;height:32px;font-size:11px;<?php echo e($reply->isFromStaff() ? 'background:rgba(79,70,229,.12);color:#4f46e5' : 'background:#e2e8f0;color:#475569'); ?>">
            <?php echo e(strtoupper(substr($reply->author_name, 0, 2))); ?>

          </span>
          <div>
            <p class="fw-semibold text-dark mb-0" style="font-size:14px">
              <?php echo e($reply->isFromStaff() ? $reply->author_name . ' (Tim Support)' : 'Anda'); ?>

            </p>
            <p class="text-muted mb-0" style="font-size:11px"><?php echo e($reply->created_at->format('d M Y H:i')); ?></p>
          </div>
        </div>

        <div class="text-muted" style="font-size:14px;white-space:pre-line;line-height:1.7"><?php echo e($reply->message); ?></div>

        <?php if($reply->attachments->isNotEmpty()): ?>
          <div class="d-flex flex-wrap gap-2 mt-3">
            <?php $__currentLoopData = $reply->attachments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attachment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <a href="<?php echo e(route('client.ticket-attachments.file', $attachment)); ?>" target="_blank"
                 class="d-inline-flex align-items-center gap-2 text-decoration-none text-theme px-2 py-1 rounded-3"
                 style="font-size:12px;background:rgba(0,0,0,.04)">
                <i class="fa-solid fa-paperclip"></i> <?php echo e($attachment->original_name); ?>

                <?php if($attachment->size_label): ?><span class="text-muted">(<?php echo e($attachment->size_label); ?>)</span><?php endif; ?>
              </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </div>
        <?php endif; ?>
      </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </div>

  
  <?php if($ticket->isClosed()): ?>
    <div class="card-public p-4 text-center">
      <p class="text-muted mb-3" style="font-size:14px">
        Tiket ini sudah ditutup <?php echo e($ticket->closed_at?->diffForHumans()); ?>.
      </p>
      <a href="<?php echo e(route('client.tickets.create')); ?>" class="btn btn-theme">Buat Tiket Baru</a>
    </div>
  <?php else: ?>
    <div class="card-public p-4">
      <h2 class="small fw-bold text-dark mb-3">Balas</h2>
      <form method="POST" action="<?php echo e(route('client.tickets.reply', $ticket)); ?>" enctype="multipart/form-data" class="d-flex flex-column gap-3">
        <?php echo csrf_field(); ?>
        <textarea name="message" rows="5" required class="form-control" placeholder="Tulis balasan Anda..."><?php echo e(old('message')); ?></textarea>
        <?php $__errorArgs = ['message'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

        <div>
          <label class="form-label">Lampiran <span class="text-muted fw-normal">(opsional, maks 5 berkas @ 5MB)</span></label>
          <input type="file" name="attachments[]" multiple class="form-control form-control-sm">
          <?php $__errorArgs = ['attachments'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          <?php $__errorArgs = ['attachments.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="d-flex align-items-center gap-3">
          <button type="submit" class="btn btn-theme"><i class="fa-solid fa-paper-plane" style="font-size:11px"></i> Kirim Balasan</button>
        </div>
      </form>

      <form method="POST" action="<?php echo e(route('client.tickets.close', $ticket)); ?>" class="mt-4 pt-4 border-top"
            data-confirm="Tutup tiket ini? Anda tetap bisa membuat tiket baru nanti." data-confirm-title="Tutup Tiket" data-confirm-style="warn" data-confirm-label="Ya, Tutup">
        <?php echo csrf_field(); ?>
        <button type="submit" class="btn btn-link p-0 text-muted" style="font-size:12px;text-decoration:none">
          <i class="fa-solid fa-check-double"></i> Masalah sudah selesai — tutup tiket ini
        </button>
      </form>
    </div>
  <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('client.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/themes/client-themes/default/client/tickets/show.blade.php ENDPATH**/ ?>