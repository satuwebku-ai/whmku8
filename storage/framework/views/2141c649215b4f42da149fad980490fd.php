<?php $__env->startSection('title', 'Profil Klien — ' . $client->name); ?>

<?php $__env->startSection('content'); ?>

  <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <div>
      <a href="<?php echo e(route('admin.clients')); ?>" class="text-decoration-none text-muted" style="font-size:12px"><i class="fa-solid fa-arrow-left"></i> Kembali ke Klien</a>
      <h1 class="h4 fw-bold text-dark mt-1 mb-0"><?php echo e($client->name); ?></h1>
      <?php if($client->company): ?>
        <p class="small text-muted mb-0"><?php echo e($client->company); ?></p>
      <?php endif; ?>
    </div>
    <span class="badge <?php echo e($client->status === 'active' ? 'badge-soft-success' : 'badge-soft-secondary'); ?>" style="font-size:13px;padding:.4rem .8rem">
      <?php echo e($client->status === 'active' ? 'Aktif' : 'Nonaktif'); ?>

    </span>
  </div>

  <div class="row g-3 mb-4">
    <div class="col-4">
      <div class="card border rounded-4 p-3 text-center">
        <p class="h4 fw-bold text-dark mb-0"><?php echo e($client->hosting_accounts_count); ?></p>
        <p class="text-muted mb-0" style="font-size:12px">Hosting Account</p>
      </div>
    </div>
    <div class="col-4">
      <div class="card border rounded-4 p-3 text-center">
        <p class="h4 fw-bold text-dark mb-0"><?php echo e($client->orders_count); ?></p>
        <p class="text-muted mb-0" style="font-size:12px">Order</p>
      </div>
    </div>
    <div class="col-4">
      <div class="card border rounded-4 p-3 text-center">
        <p class="h4 fw-bold text-dark mb-0"><?php echo e($client->invoices_count); ?></p>
        <p class="text-muted mb-0" style="font-size:12px">Invoice</p>
      </div>
    </div>
  </div>

  <?php
    $statusBadge = fn ($s) => match ($s) {
        'active', 'paid' => 'badge-soft-success',
        'pending', 'unpaid' => 'badge-soft-warning',
        'suspended', 'overdue' => 'badge-soft-danger',
        default => 'badge-soft-secondary',
    };
  ?>

  <div class="row g-3">
    <div class="col-12 col-lg-8">

      <div class="card border rounded-4 p-4 mb-3">
        <h2 class="small fw-bold text-dark mb-3">Informasi Kontak</h2>
        <div class="row g-3 small">
          <div class="col-sm-6">
            <p class="text-muted mb-1" style="font-size:11px">EMAIL</p>
            <p class="fw-medium text-dark mb-0"><?php echo e($client->email); ?></p>
          </div>
          <div class="col-sm-6">
            <p class="text-muted mb-1" style="font-size:11px">TELEPON</p>
            <p class="fw-medium text-dark mb-0"><?php echo e($client->phone ?? '—'); ?></p>
          </div>
          <div class="col-12">
            <p class="text-muted mb-1" style="font-size:11px">ALAMAT</p>
            <p class="fw-medium text-dark mb-0"><?php echo e($client->address ?? '—'); ?>, <?php echo e($client->city); ?>, <?php echo e(\App\Support\Countries::name($client->country)); ?></p>
          </div>
        </div>
      </div>

      <?php if($client->orders->isNotEmpty()): ?>
        <div class="card border rounded-4 p-4 mb-3">
          <h2 class="small fw-bold text-dark mb-2">Order Terbaru</h2>
          <?php $__currentLoopData = $client->orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('admin.orders.details', $order)); ?>" class="d-flex align-items-center justify-content-between py-2 small text-decoration-none border-bottom text-dark">
              <span>#<?php echo e($order->order_number); ?> — <?php echo e($order->product_name); ?></span>
              <span class="badge <?php echo e($statusBadge($order->status->value)); ?>"><?php echo e(ucfirst(str_replace('_', ' ', $order->status->value))); ?></span>
            </a>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
      <?php endif; ?>

      <?php if($client->invoices->isNotEmpty()): ?>
        <div class="card border rounded-4 p-4 mb-3">
          <h2 class="small fw-bold text-dark mb-2">Invoice Terbaru</h2>
          <?php $__currentLoopData = $client->invoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('admin.invoices.details', $invoice)); ?>" class="d-flex align-items-center justify-content-between py-2 small text-decoration-none border-bottom text-dark">
              <span><?php echo e($invoice->invoice_number); ?></span>
              <span class="badge <?php echo e($statusBadge($invoice->status)); ?>"><?php echo e(ucfirst($invoice->status)); ?></span>
            </a>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
      <?php endif; ?>

      <?php if($client->hostingAccounts->isNotEmpty()): ?>
        <div class="card border rounded-4 p-4 mb-3">
          <h2 class="small fw-bold text-dark mb-2">Hosting Account</h2>
          <?php $__currentLoopData = $client->hostingAccounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('admin.hosting-accounts.details', $account)); ?>" class="d-flex align-items-center justify-content-between py-2 small text-decoration-none border-bottom text-dark">
              <span><?php echo e($account->domain); ?></span>
              <span class="badge <?php echo e($statusBadge($account->status)); ?>"><?php echo e(ucfirst($account->status)); ?></span>
            </a>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
      <?php endif; ?>

      <div class="card border rounded-4 p-4">
        <h2 class="small fw-bold text-dark mb-2">Catatan Internal</h2>
        <form method="POST" action="<?php echo e(route('admin.client.notes')); ?>">
          <?php echo csrf_field(); ?>
          <input type="hidden" name="client_id" value="<?php echo e($client->id); ?>">
          <textarea name="internal_notes" rows="4" class="form-control form-control-sm" placeholder="Catatan staf tentang klien ini..."><?php echo e(old('internal_notes', $client->internal_notes)); ?></textarea>
          <button type="submit" class="btn btn-outline-secondary btn-sm mt-2"><i class="fa-solid fa-floppy-disk" style="font-size:11px"></i> Simpan Catatan</button>
        </form>
      </div>
    </div>

    <div class="col-12 col-lg-4">
      <div class="card border rounded-4 p-4 mb-3">
        <h2 class="small fw-bold text-dark mb-1">Saldo Klien</h2>
        <p class="h4 fw-bold text-dark mb-3">Rp <?php echo e(number_format((float) $client->balance, 0, ',', '.')); ?></p>

        <form method="POST" action="<?php echo e(route('admin.client.balance.adjust', $client)); ?>" class="mb-3">
          <?php echo csrf_field(); ?>
          <input type="hidden" name="token" value="<?php echo e(\Illuminate\Support\Str::uuid()); ?>">
          <input type="number" name="amount" step="1000" placeholder="Nominal (- untuk kurangi)" required
                 class="form-control form-control-sm mb-2">
          <input type="text" name="description" placeholder="Alasan (mis. Refund invoice INV-2026-0003)" required
                 class="form-control form-control-sm mb-2">
          <?php $__errorArgs = ['amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mb-1" style="font-size:11px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mb-1" style="font-size:11px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          <?php $__errorArgs = ['token'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mb-1" style="font-size:11px">Formulir kedaluwarsa, muat ulang halaman.</p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          <button type="submit" class="btn btn-outline-secondary btn-sm w-100">
            <i class="fa-solid fa-sliders" style="font-size:11px"></i> Sesuaikan Saldo
          </button>
        </form>

        <?php if($client->balanceLogs->isNotEmpty()): ?>
          <div class="border-top pt-2" style="max-height:14rem;overflow-y:auto">
            <?php $__currentLoopData = $client->balanceLogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <div class="d-flex align-items-center justify-content-between mb-2" style="font-size:12px">
                <div class="min-w-0">
                  <p class="text-dark mb-0 text-truncate"><?php echo e($log->description); ?></p>
                  <p class="text-muted mb-0"><?php echo e($log->created_at->format('d M Y H:i')); ?></p>
                </div>
                <span class="fw-medium flex-shrink-0 ms-2 <?php echo e($log->amount >= 0 ? 'text-success' : 'text-danger'); ?>">
                  <?php echo e($log->amount >= 0 ? '+' : ''); ?><?php echo e(number_format($log->amount, 0, ',', '.')); ?>

                </span>
              </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </div>
        <?php else: ?>
          <p class="text-muted border-top pt-2 mb-0" style="font-size:12px">Belum ada riwayat saldo.</p>
        <?php endif; ?>
      </div>

      <div class="card border rounded-4 p-4">
        <h2 class="small fw-bold text-dark mb-2">Aksi</h2>
        <div class="d-flex flex-column gap-2">
          <form method="POST" action="<?php echo e(route('admin.client.status')); ?>">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="client_id" value="<?php echo e($client->id); ?>">
            <button type="submit" class="btn <?php echo e($client->status === 'active' ? 'btn-outline-danger' : 'btn-primary'); ?> btn-sm w-100 text-start">
              <i class="fa-solid <?php echo e($client->status === 'active' ? 'fa-user-slash' : 'fa-user-check'); ?>" style="font-size:11px"></i>
              <?php echo e($client->status === 'active' ? 'Nonaktifkan Klien' : 'Aktifkan Klien'); ?>

            </button>
          </form>
          <a href="<?php echo e(route('admin.client.edit.page', $client)); ?>" class="btn btn-outline-secondary btn-sm w-100 text-start">
            <i class="fa-regular fa-pen-to-square" style="font-size:11px"></i> Edit Data Klien
          </a>

          <?php if(auth('admin')->user()->hasModule('services')): ?>
            <form method="POST" action="<?php echo e(route('admin.client.impersonate', $client)); ?>"
                  data-confirm="Anda akan masuk ke akun <?php echo e($client->name); ?> (<?php echo e($client->email); ?>). Aktivitas ini tercatat di log Aktivitas. Lanjutkan?"
                  data-confirm-title="Login sebagai Klien" data-confirm-style="warn" data-confirm-label="Ya, Masuk">
              <?php echo csrf_field(); ?>
              <button type="submit" class="btn btn-outline-warning btn-sm w-100 text-start">
                <i class="fa-solid fa-user-shield" style="font-size:11px"></i> Login sebagai Klien Ini
              </button>
            </form>
            <p class="text-muted mb-0" style="font-size:11px">
              Berguna untuk troubleshooting. Tercatat di menu Aktivitas dan Percobaan Login.
            </p>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/admin/clients/details.blade.php ENDPATH**/ ?>