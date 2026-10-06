<?php $__env->startSection('title', 'Tiket ' . $ticket->ticket_number); ?>

<?php $__env->startSection('content'); ?>

  <?php
    $badgeMap = ['active' => 'badge-soft-success', 'pending' => 'badge-soft-warning', 'overdue' => 'badge-soft-danger', 'suspended' => 'badge-soft-danger', 'inactive' => 'badge-soft-secondary'];
  ?>

  <div class="ticket-hero d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2 rounded-4 px-4 py-3">
    <div>
      <a href="<?php echo e(route('admin.tickets')); ?>" class="text-decoration-none text-muted" style="font-size:12px"><i class="fa-solid fa-arrow-left"></i> Kembali ke Tiket</a>
       <h1 class="h4 fw-bold text-dark mt-1 mb-0"><?php echo e($ticket->subject); ?></h1>
       <p class="small text-muted mb-0"><span class="font-monospace"><?php echo e($ticket->ticket_number); ?></span> · dibuat <?php echo e($ticket->created_at->format('d M Y H:i')); ?></p>
    </div>
    <div class="d-flex align-items-center gap-2">
      <span class="badge <?php echo e($badgeMap[$ticket->priority_badge] ?? 'badge-soft-secondary'); ?>" style="font-size:13px;padding:.4rem .8rem"><?php echo e(ucfirst($ticket->priority)); ?></span>
      <span class="badge <?php echo e($badgeMap[$ticket->status_badge] ?? 'badge-soft-secondary'); ?>" style="font-size:13px;padding:.4rem .8rem"><?php echo e($ticket->status_label); ?></span>
    </div>
  </div>

  <div class="row g-3">
    <div class="col-12 col-lg-8">

      
      <div class="mb-3">
        <?php $__currentLoopData = $ticket->replies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reply): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <?php
            $isNote = $reply->is_internal_note;
            $isStaff = $reply->isFromStaff() && ! $isNote;
            $cardStyle = $isNote ? 'border-color:#fde68a!important;background:#fffbeb'
                       : ($isStaff ? 'border-color:#c7d2fe!important;background:rgba(79,70,229,.04)' : '');
          ?>
          <div class="card border rounded-4 p-4 mb-2" style="<?php echo e($cardStyle); ?>">
            <div class="d-flex align-items-center justify-content-between mb-2">
              <div class="d-flex align-items-center gap-2">
                <span class="rounded-circle d-flex align-items-center justify-content-center fw-bold flex-shrink-0"
                      style="width:32px;height:32px;font-size:11px;<?php echo e($reply->isFromStaff() ? 'background:rgba(79,70,229,.12);color:#4338ca' : 'background:#e2e8f0;color:#475569'); ?>">
                  <?php echo e(strtoupper(substr($reply->author_name, 0, 2))); ?>

                </span>
                <div>
                  <p class="small fw-bold text-dark mb-0">
                    <?php echo e($reply->author_name); ?>

                    <span class="text-muted fw-normal" style="font-size:11px"><?php echo e($reply->isFromStaff() ? '(Staf)' : '(Klien)'); ?></span>
                  </p>
                  <p class="text-muted mb-0" style="font-size:11px"><?php echo e($reply->created_at->format('d M Y H:i')); ?></p>
                </div>
              </div>
              <?php if($isNote): ?>
                <span class="badge badge-soft-warning"><i class="fa-solid fa-lock" style="font-size:9px"></i> Catatan Internal</span>
              <?php endif; ?>
            </div>

            <div class="small text-dark" style="white-space:pre-line;line-height:1.6"><?php echo e($reply->message); ?></div>

            <?php if($reply->attachments->isNotEmpty()): ?>
              <div class="d-flex flex-wrap gap-2 mt-2">
                <?php $__currentLoopData = $reply->attachments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attachment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <a href="<?php echo e(route('admin.ticket-attachments.file', $attachment)); ?>" target="_blank"
                     class="d-inline-flex align-items-center gap-2 text-decoration-none text-accent px-2 py-1 rounded-3"
                     style="font-size:12px;background:#f1f5f9">
                    <i class="fa-solid fa-paperclip"></i> <?php echo e($attachment->original_name); ?>

                    <?php if($attachment->size_label): ?><span class="text-muted">(<?php echo e($attachment->size_label); ?>)</span><?php endif; ?>
                  </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </div>
            <?php endif; ?>
          </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </div>

      
      <?php if(! $ticket->isClosed()): ?>
        <div class="card border rounded-4 p-4">
          <h2 class="small fw-bold text-dark mb-3">Balas Tiket</h2>
          <form method="POST" action="<?php echo e(route('admin.ticket.reply')); ?>" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="ticket_id" value="<?php echo e($ticket->id); ?>">

            <textarea name="message" rows="5" class="form-control form-control-sm mb-2" placeholder="Tulis balasan untuk klien..." required><?php echo e(old('message')); ?></textarea>
            <?php $__errorArgs = ['message'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mb-2" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

            <div class="mb-2">
              <label class="form-label small fw-medium text-dark">Lampiran (opsional, maks 5 berkas @ 5MB)</label>
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

            <label class="d-flex align-items-center gap-2 small text-dark mb-3">
              <input type="checkbox" name="is_internal_note" value="1" class="form-check-input" style="margin-top:0">
              Simpan sebagai catatan internal (tidak terlihat klien, status tiket tidak berubah)
            </label>

            <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-paper-plane" style="font-size:11px"></i> Kirim Balasan</button>
          </form>
        </div>
      <?php else: ?>
        <div class="card border rounded-4 p-4 text-center text-muted small">
          Tiket ini sudah ditutup <?php echo e($ticket->closed_at?->diffForHumans()); ?>. Buka kembali untuk membalas.
        </div>
      <?php endif; ?>
    </div>

    
    <div class="col-12 col-lg-4">
      <?php if($ticket->domain && str_starts_with($ticket->subject, 'Permintaan Kode Transfer')): ?>
        <div class="card border rounded-4 p-4 mb-3" style="background:#fffbeb;border-color:#fde68a!important">
          <h2 class="small fw-bold mb-1" style="color:#92400e">
            <i class="fa-solid fa-key"></i> Permintaan Kode Transfer
          </h2>
          <p class="mb-3" style="font-size:12px;color:#b45309">
            Domain: <a href="<?php echo e(route('admin.domains.details', $ticket->domain)); ?>" class="text-decoration-underline" style="color:inherit"><?php echo e($ticket->domain->domain_name); ?></a>
          </p>

          <?php if(session('preview_transfer_code')): ?>
            <div class="rounded-3 px-3 py-2 small mb-3" style="background:#1e293b;color:#fff">
              <p class="mb-1" style="font-size:11px;color:#cbd5e1">Kode transfer (pratinjau — belum terkirim ke klien):</p>
              <p class="fw-bold mb-0" style="font-family:monospace;letter-spacing:.05em"><?php echo e(session('preview_transfer_code')); ?></p>
            </div>
            <form method="POST" action="<?php echo e(route('admin.tickets.approve-transfer-code', $ticket)); ?>"
                  data-confirm="Kirim kode ini ke email klien sekarang?" data-confirm-title="Setujui & Kirim" data-confirm-style="warn" data-confirm-label="Ya, Kirim ke Klien">
              <?php echo csrf_field(); ?>
              <input type="hidden" name="code" value="<?php echo e(session('preview_transfer_code')); ?>">
              <button type="submit" class="btn btn-primary btn-sm w-100">
                <i class="fa-solid fa-paper-plane" style="font-size:11px"></i> Setujui &amp; Kirim ke Email Klien
              </button>
            </form>
          <?php else: ?>
            <form method="POST" action="<?php echo e(route('admin.tickets.preview-transfer-code', $ticket)); ?>">
              <?php echo csrf_field(); ?>
              <button type="submit" class="btn btn-outline-secondary btn-sm w-100" style="border-color:#fcd34d;color:#b45309">
                <i class="fa-solid fa-eye" style="font-size:11px"></i> Lihat Kode Dulu
              </button>
            </form>
          <?php endif; ?>
        </div>
      <?php endif; ?>

      <div class="card border rounded-4 p-4 mb-3">
        <h2 class="small fw-bold text-dark mb-3">Informasi</h2>
        <div class="mb-2">
          <p class="text-muted mb-1" style="font-size:11px">KLIEN</p>
          <p class="fw-medium text-dark mb-0">
            <?php if($ticket->client): ?>
              <a href="<?php echo e(route('admin.clients.details', $ticket->client)); ?>" class="text-decoration-none text-accent"><?php echo e($ticket->client->name); ?></a>
            <?php else: ?>
              —
            <?php endif; ?>
          </p>
        </div>
        <div class="mb-2">
          <p class="text-muted mb-1" style="font-size:11px">DEPARTEMEN</p>
          <p class="fw-medium text-dark text-capitalize mb-0"><?php echo e($ticket->department); ?></p>
        </div>
        <div>
          <p class="text-muted mb-1" style="font-size:11px">BALASAN TERAKHIR</p>
          <p class="fw-medium text-dark mb-0"><?php echo e($ticket->last_reply_at?->diffForHumans() ?? '—'); ?></p>
        </div>
      </div>

      <div class="card border rounded-4 p-4 mb-3" style="background:linear-gradient(135deg,#eef2ff,#fff)">
        <div class="d-flex align-items-center gap-2 mb-2">
          <span class="rounded-2 d-flex align-items-center justify-content-center" style="width:28px;height:28px;background:#fff;color:#4f46e5"><i class="fa-regular fa-envelope"></i></span>
          <h2 class="small fw-bold text-dark mb-0">Email Klien</h2>
        </div>
        <p class="text-muted mb-2" style="font-size:11px">Setiap balasan publik dikirim otomatis ke alamat ini. Catatan internal tidak pernah dikirim.</p>
        <div class="rounded-3 px-2 py-2 d-flex align-items-center gap-2" style="background:rgba(255,255,255,.75);font-size:11px">
          <i class="fa-solid fa-circle-check text-success"></i>
          <span class="text-dark text-truncate"><?php echo e($ticket->client?->email ?: 'Email klien belum tersedia'); ?></span>
        </div>
      </div>

      <div class="card border rounded-4 p-4">
        <h2 class="small fw-bold text-dark mb-3">Kelola</h2>

        <form method="POST" action="<?php echo e(route('admin.ticket.assign')); ?>" class="mb-3">
          <?php echo csrf_field(); ?>
          <input type="hidden" name="ticket_id" value="<?php echo e($ticket->id); ?>">
          <label class="form-label small fw-medium text-dark">Tugaskan ke</label>
          <div class="d-flex gap-2">
            <select name="assigned_to" class="form-select" style="padding:.25rem .6rem;font-size:.875rem;border-radius:.375rem">
              <option value="">— Belum ditugaskan —</option>
              <?php $__currentLoopData = $admins; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $admin): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($admin->id); ?>" <?php if($ticket->assigned_to == $admin->id): echo 'selected'; endif; ?>><?php echo e($admin->name); ?></option>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <button type="submit" class="btn btn-outline-secondary btn-sm flex-shrink-0"><i class="fa-solid fa-check" style="font-size:11px"></i></button>
          </div>
        </form>

        <form method="POST" action="<?php echo e(route('admin.ticket.priority')); ?>" class="mb-3">
          <?php echo csrf_field(); ?>
          <input type="hidden" name="ticket_id" value="<?php echo e($ticket->id); ?>">
          <label class="form-label small fw-medium text-dark">Prioritas</label>
          <div class="d-flex gap-2">
            <select name="priority" class="form-select" style="padding:.25rem .6rem;font-size:.875rem;border-radius:.375rem">
              <option value="low" <?php if($ticket->priority === 'low'): echo 'selected'; endif; ?>>Low</option>
              <option value="medium" <?php if($ticket->priority === 'medium'): echo 'selected'; endif; ?>>Medium</option>
              <option value="high" <?php if($ticket->priority === 'high'): echo 'selected'; endif; ?>>High</option>
              <option value="urgent" <?php if($ticket->priority === 'urgent'): echo 'selected'; endif; ?>>Urgent</option>
            </select>
            <button type="submit" class="btn btn-outline-secondary btn-sm flex-shrink-0"><i class="fa-solid fa-check" style="font-size:11px"></i></button>
          </div>
        </form>

        <div class="pt-2 border-top">
          <?php if($ticket->isClosed()): ?>
            <form method="POST" action="<?php echo e(route('admin.ticket.reopen')); ?>">
              <?php echo csrf_field(); ?>
              <input type="hidden" name="ticket_id" value="<?php echo e($ticket->id); ?>">
              <button type="submit" class="btn btn-primary btn-sm w-100 text-start"><i class="fa-solid fa-rotate-left" style="font-size:11px"></i> Buka Kembali</button>
            </form>
          <?php else: ?>
            <form method="POST" action="<?php echo e(route('admin.ticket.close')); ?>" data-confirm="Tutup tiket ini?" data-confirm-title="Tutup Tiket" data-confirm-style="warn" data-confirm-label="Ya, Tutup">
              <?php echo csrf_field(); ?>
              <input type="hidden" name="ticket_id" value="<?php echo e($ticket->id); ?>">
              <button type="submit" class="btn btn-outline-danger btn-sm w-100 text-start"><i class="fa-solid fa-check-double" style="font-size:11px"></i> Tutup Tiket</button>
            </form>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

  <style>
    .ticket-hero{ background:linear-gradient(135deg,rgba(255,255,255,.88),rgba(238,242,255,.72)); border:1px solid rgba(99,102,241,.12); box-shadow:0 8px 24px rgba(15,23,42,.04); }
    .ticket-hero .badge{ box-shadow:0 3px 10px rgba(15,23,42,.06); }
  </style>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/admin/tickets/details.blade.php ENDPATH**/ ?>