<?php $__env->startSection('title', 'Support Ticket'); ?>

<?php $__env->startSection('content'); ?>

  <div class="d-flex align-items-start justify-content-between gap-3 mb-4 flex-wrap">
    <div>
      <div class="d-flex align-items-center gap-2 mb-1">
        <span class="rounded-3 d-flex align-items-center justify-content-center" style="width:34px;height:34px;background:#ecfdf5;color:#059669"><i class="fa-solid fa-ticket"></i></span>
        <h1 class="h4 fw-bold text-dark mb-0">Support Ticket</h1>
      </div>
      <p class="small text-muted mb-0">Kelola pertanyaan klien dengan SLA, prioritas, penanggung jawab, dan riwayat email yang jelas.</p>
    </div>
    <a href="<?php echo e(route('admin.ticket.add.page')); ?>" class="btn btn-primary">
      <i class="fa-solid fa-plus" style="font-size:12px"></i> Buat Tiket
    </a>
  </div>

  <div class="row g-2 mb-3">
    <?php $__currentLoopData = [
      ['label' => 'Total tiket', 'value' => $stats['all'], 'icon' => 'fa-layer-group', 'tone' => 'primary'],
      ['label' => 'Perlu ditangani', 'value' => $stats['open'] + $stats['customer_reply'], 'icon' => 'fa-inbox', 'tone' => 'warning'],
      ['label' => 'Prioritas tinggi', 'value' => $stats['urgent'], 'icon' => 'fa-bolt', 'tone' => 'danger'],
      ['label' => 'Selesai', 'value' => $stats['closed'], 'icon' => 'fa-circle-check', 'tone' => 'success'],
    ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <div class="col-6 col-xl-3">
        <div class="card border rounded-4 px-3 py-3 h-100">
          <div class="d-flex align-items-center justify-content-between">
            <div><p class="text-muted mb-1" style="font-size:10px;text-transform:uppercase;letter-spacing:.06em"><?php echo e($stat['label']); ?></p><p class="h5 fw-bold text-dark mb-0"><?php echo e($stat['value']); ?></p></div>
            <span class="rounded-3 d-flex align-items-center justify-content-center text-<?php echo e($stat['tone']); ?>" style="width:34px;height:34px;background:rgba(var(--bs-<?php echo e($stat['tone']); ?>-rgb),.1)"><i class="fa-solid <?php echo e($stat['icon']); ?>" style="font-size:13px"></i></span>
          </div>
        </div>
      </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </div>

  
  <div class="d-flex align-items-center gap-1 mb-3 border-bottom flex-wrap">
    <?php
      $tabs = [
        ['label' => 'Semua', 'route' => 'admin.tickets'],
        ['label' => 'Baru', 'route' => 'admin.tickets.open'],
        ['label' => 'Balasan Klien', 'route' => 'admin.tickets.customer-reply'],
        ['label' => 'Dijawab', 'route' => 'admin.tickets.answered'],
        ['label' => 'Ditutup', 'route' => 'admin.tickets.closed'],
      ];
    ?>
    <?php $__currentLoopData = $tabs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tab): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <a href="<?php echo e(route($tab['route'])); ?>"
         class="px-3 py-2 small fw-medium text-decoration-none border-bottom border-2 <?php echo e(request()->routeIs(str_replace('.bootstrap-preview', '', $tab['route'])) ? 'border-primary text-accent' : 'border-transparent text-muted'); ?>">
        <?php echo e($tab['label']); ?>

      </a>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </div>

  <div class="card border rounded-4 overflow-hidden">
    <form method="GET" class="px-4 py-3 border-bottom d-flex flex-wrap align-items-center gap-2">
      <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari nomor tiket / subjek..." class="form-control form-control-sm" style="max-width:16rem;flex:1 1 180px">
      <select name="priority" class="form-select" style="padding:.25rem .6rem;font-size:.875rem;border-radius:.375rem;max-width:10rem">
        <option value="">Semua Prioritas</option>
        <option value="urgent" <?php if(request('priority') === 'urgent'): echo 'selected'; endif; ?>>Urgent</option>
        <option value="high" <?php if(request('priority') === 'high'): echo 'selected'; endif; ?>>High</option>
        <option value="medium" <?php if(request('priority') === 'medium'): echo 'selected'; endif; ?>>Medium</option>
        <option value="low" <?php if(request('priority') === 'low'): echo 'selected'; endif; ?>>Low</option>
      </select>
      <button type="submit" class="btn btn-outline-secondary btn-sm" style="width:fit-content">Filter</button>
      <?php if(request('search') || request('priority')): ?>
        <a href="<?php echo e(url()->current()); ?>" class="btn btn-outline-secondary btn-sm" style="width:fit-content">Reset</a>
      <?php endif; ?>
    </form>

    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead>
          <tr class="small text-uppercase text-muted" style="background:#f8fafc">
            <th class="px-4 py-3">Tiket</th>
            <th class="py-3">Klien</th>
            <th class="py-3">Departemen</th>
            <th class="py-3">Prioritas</th>
            <th class="py-3">Ditugaskan</th>
            <th class="py-3">Status</th>
            <th class="py-3">Update</th>
            <th class="text-end px-4 py-3">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php
            $badgeMap = ['active' => 'badge-soft-success', 'pending' => 'badge-soft-warning', 'overdue' => 'badge-soft-danger', 'suspended' => 'badge-soft-danger', 'inactive' => 'badge-soft-secondary'];
          ?>
          <?php $__empty_1 = true; $__currentLoopData = $tickets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ticket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
             <tr class="<?php echo e($ticket->needsAttention() ? 'table-warning-subtle' : ''); ?>" style="<?php echo e($ticket->needsAttention() ? 'background:#fffbeb' : ''); ?>">
              <td class="px-4 py-3">
                <a href="<?php echo e(route('admin.tickets.details', $ticket)); ?>" class="text-decoration-none fw-medium text-dark">
                  <?php echo e($ticket->subject); ?>

                </a>
                 <p class="text-muted mb-0" style="font-size:11px"><span class="font-monospace"><?php echo e($ticket->ticket_number); ?></span> · <?php echo e($ticket->replies_count); ?> balasan</p>
              </td>
              <td class="text-muted py-3"><?php echo e($ticket->client->name ?? '—'); ?></td>
              <td class="text-muted text-capitalize py-3"><?php echo e($ticket->department); ?></td>
              <td class="py-3"><span class="badge <?php echo e($badgeMap[$ticket->priority_badge] ?? 'badge-soft-secondary'); ?>"><?php echo e(ucfirst($ticket->priority)); ?></span></td>
              <td class="text-muted py-3"><?php echo e($ticket->assignee->name ?? '—'); ?></td>
              <td class="py-3"><span class="badge <?php echo e($badgeMap[$ticket->status_badge] ?? 'badge-soft-secondary'); ?>"><?php echo e($ticket->status_label); ?></span></td>
              <td class="text-muted py-3" style="font-size:12px"><?php echo e($ticket->last_reply_at?->diffForHumans() ?? '—'); ?></td>
              <td class="text-end px-4 py-3">
                <div class="d-flex align-items-center justify-content-end gap-2">
                  <a href="<?php echo e(route('admin.tickets.details', $ticket)); ?>" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center justify-content-center" style="width:32px;height:32px;padding:0" title="Buka">
                    <i class="fa-regular fa-comments" style="font-size:12px"></i>
                  </a>
                  <form method="POST" action="<?php echo e(route('admin.ticket.delete', $ticket)); ?>" data-confirm="Hapus tiket ini beserta semua balasannya?" data-confirm-title="Hapus Data" data-confirm-style="danger" data-confirm-label="Ya, Hapus">
                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-outline-danger btn-sm d-inline-flex align-items-center justify-content-center" style="width:32px;height:32px;padding:0" title="Hapus">
                      <i class="fa-regular fa-trash-can" style="font-size:12px"></i>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr><td colspan="8" class="text-center text-muted py-5">Tidak ada tiket di kategori ini.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <?php if($tickets->hasPages()): ?>
      <div class="px-4 py-3 border-top"><?php echo e($tickets->links('pagination.bootstrap')); ?></div>
    <?php endif; ?>
  </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/admin/tickets/index.blade.php ENDPATH**/ ?>