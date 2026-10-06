<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>

  <?php
    $badgeMap = [
      'active' => 'badge-soft-success', 'paid' => 'badge-soft-success',
      'pending' => 'badge-soft-warning', 'unpaid' => 'badge-soft-warning', 'answered' => 'badge-soft-warning',
      'suspended' => 'badge-soft-danger', 'overdue' => 'badge-soft-danger', 'expired' => 'badge-soft-danger',
      'inactive' => 'badge-soft-secondary', 'closed' => 'badge-soft-secondary', 'cancelled' => 'badge-soft-secondary', 'terminated' => 'badge-soft-secondary',
    ];
  ?>

  
  <div class="welcome d-flex flex-wrap justify-content-between align-items-center p-4 mb-4 gap-2">
    <div>
      <h1 class="h3 mb-0">Halo, <?php echo e($client->name); ?> <span class="wave">&#128075;</span></h1>
      <small class="text-white-50">Berikut ringkasan layanan Anda hari ini.</small>
    </div>
    <div class="d-flex flex-wrap gap-2">
      <a href="<?php echo e(route('client.tickets.create')); ?>" class="btn btn-outline-light">Buka tiket</a>
      <a href="<?php echo e(route('catalog.index')); ?>" class="btn btn-accent">Tambah layanan</a>
    </div>
  </div>

  
  <?php if($stats['unpaidInvoices'] > 0): ?>
    <div class="alert alert-warning d-flex flex-wrap align-items-center justify-content-between gap-2" role="alert">
      <div class="d-flex align-items-center">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        <div>
          Anda punya <b><?php echo e($stats['unpaidInvoices']); ?></b> invoice belum dibayar dengan total
          <b>Rp <?php echo e(number_format($unpaidTotal, 0, ',', '.')); ?></b>.
        </div>
      </div>
      <a href="<?php echo e(route('client.invoices', ['status' => 'unpaid'])); ?>" class="btn btn-sm btn-primary">Bayar sekarang</a>
    </div>
  <?php endif; ?>

  
  <?php
    $cards = [
      ['label' => 'Layanan aktif',       'value' => $stats['services'],        'icon' => 'bi-server',    'tile' => 't-teal',   'color' => '#0e7c86', 'route' => 'client.services'],
      ['label' => 'Domain aktif',        'value' => $stats['domains'],         'icon' => 'bi-globe2',    'tile' => 't-indigo', 'color' => '#5b5bd6', 'route' => 'client.domains'],
      ['label' => 'Invoice belum bayar', 'value' => $stats['unpaidInvoices'],  'icon' => 'bi-receipt',   'tile' => 't-coral',  'color' => '#ff6f59', 'route' => 'client.invoices'],
      ['label' => 'Tiket terbuka',       'value' => $stats['openTickets'],     'icon' => 'bi-life-preserver', 'tile' => 't-amber', 'color' => '#f5a524', 'route' => 'client.tickets'],
    ];
  ?>
  <div class="row g-3 mb-4">
    <?php $__currentLoopData = $cards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $card): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <div class="col-6 col-xl-3">
        <a href="<?php echo e(route($card['route'])); ?>" class="dash-card dash-card-hover stat-card d-block h-100 text-decoration-none" style="--stat-color:<?php echo e($card['color']); ?>">
          <div class="p-3 d-flex align-items-center gap-3">
            <span class="tile <?php echo e($card['tile']); ?>"><i class="bi <?php echo e($card['icon']); ?>"></i></span>
            <div>
              <small class="text-body-secondary"><?php echo e($card['label']); ?></small>
              <div class="fs-3 fw-bold text-body" style="line-height:1.1"><?php echo e($card['value']); ?></div>
            </div>
          </div>
        </a>
      </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </div>

  <div class="row g-4">

    <div class="col-xl-8 d-flex flex-column gap-4">

      
      <div class="card">
        <div class="card-header d-flex justify-content-between"><b>Invoice terbaru</b><a href="<?php echo e(route('client.invoices')); ?>" class="text-decoration-none">Lihat semua</a></div>

        <?php if($recentInvoices->isEmpty()): ?>
          <div class="text-center py-5">
            <i class="bi bi-receipt fs-2 text-body-secondary"></i>
            <p class="text-body-secondary mb-0 mt-2">Belum ada invoice.</p>
          </div>
        <?php else: ?>
          <div class="table-responsive">
            <table class="table align-middle mb-0">
              <thead><tr><th>No. invoice</th><th>Jatuh tempo</th><th>Total</th><th>Status</th><th></th></tr></thead>
              <tbody>
                <?php $__currentLoopData = $recentInvoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <tr>
                    <td class="fw-semibold"><?php echo e($invoice->invoice_number); ?></td>
                    <td><?php echo e($invoice->due_date->format('d M Y')); ?></td>
                    <td>Rp <?php echo e(number_format($invoice->total, 0, ',', '.')); ?></td>
                    <td>
                      <span class="badge <?php echo e($badgeMap[$invoice->is_overdue ? 'overdue' : $invoice->status] ?? 'badge-soft-secondary'); ?>">
                        <?php echo e($invoice->is_overdue ? 'Terlambat' : ucfirst($invoice->status)); ?>

                      </span>
                    </td>
                    <td class="text-end">
                      <a href="<?php echo e(route('client.invoices.show', $invoice)); ?>" class="btn btn-sm <?php echo e($invoice->status === 'unpaid' ? 'btn-primary' : 'btn-outline-secondary'); ?>">
                        <?php echo e($invoice->status === 'unpaid' ? 'Bayar' : 'Lihat'); ?>

                      </a>
                    </td>
                  </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>
      </div>

      
      <?php if($expiringSoon->isNotEmpty()): ?>
        <div class="card">
          <div class="card-header d-flex align-items-center gap-2">
            <i class="bi bi-exclamation-triangle-fill text-warning"></i>
            <b>Domain akan segera kedaluwarsa</b>
          </div>
          <ul class="list-group list-group-flush">
            <?php $__currentLoopData = $expiringSoon; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $domain): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <li class="list-group-item list-row d-flex flex-wrap justify-content-between align-items-center gap-2">
                <span class="fw-semibold"><i class="bi bi-globe2 me-2 text-primary"></i><?php echo e($domain->domain_name); ?></span>
                <span class="d-flex align-items-center gap-3">
                  <small class="text-warning-emphasis">
                    <?php echo e($domain->expiry_date->format('d M Y')); ?>

                    (<?php echo e((int) now()->diffInDays($domain->expiry_date)); ?> hari lagi)
                  </small>
                  <a href="<?php echo e(route('client.domains.show', $domain)); ?>" class="btn btn-sm btn-accent">Perpanjang</a>
                </span>
              </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </ul>
        </div>
      <?php endif; ?>
    </div>

    <div class="col-xl-4 d-flex flex-column gap-4">

      
      <div class="card">
        <div class="card-header fw-bold">Pengumuman</div>
        <?php if($announcements->isEmpty()): ?>
          <div class="card-body text-body-secondary">Belum ada pengumuman.</div>
        <?php else: ?>
          <ul class="list-group list-group-flush">
            <?php $__currentLoopData = $announcements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <li class="list-group-item">
                <a href="<?php echo e(route('announcements.show', $item->slug)); ?>" target="_blank" class="text-decoration-none d-block">
                  <div class="d-flex justify-content-between gap-2">
                    <span class="text-body fw-semibold"><?php echo e($item->title); ?></span>
                    <span class="badge <?php echo e($badgeMap[$item->category] ?? 'badge-soft-secondary'); ?> text-capitalize align-self-start"><?php echo e($item->category); ?></span>
                  </div>
                  <small class="text-body-secondary"><?php echo e($item->published_at?->diffForHumans()); ?></small>
                </a>
              </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </ul>
        <?php endif; ?>
      </div>

      
      <div class="card">
        <div class="card-body">
          <span class="tile t-amber mb-3"><i class="bi bi-headset"></i></span>
          <h2 class="h6 fw-bold mb-2">Butuh bantuan?</h2>
          <p class="text-body-secondary mb-3">Tim support kami siap membantu masalah teknis maupun tagihan.</p>
          <a href="<?php echo e(route('client.tickets.create')); ?>" class="btn btn-primary w-100"><i class="bi bi-plus-lg"></i> Buat tiket support</a>
        </div>
      </div>
    </div>
  </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('client.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/themes/client-themes/namahost/client/dashboard/index.blade.php ENDPATH**/ ?>