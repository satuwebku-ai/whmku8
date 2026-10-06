<?php $__env->startSection('title', $order->exists ? 'Edit Order' : 'Buat Order'); ?>

<?php $__env->startSection('content'); ?>

  <?php
    $selectStyle = 'padding:.25rem .6rem;font-size:.875rem;border-radius:.375rem';
    $currentStatus = $order->status instanceof \App\Enums\OrderStatus ? $order->status->value : ($order->status ?: 'draft');
  ?>

  <div class="mb-4">
    <h1 class="h4 fw-bold text-dark mb-1"><?php echo e($order->exists ? 'Edit Order' : 'Buat Order Baru'); ?></h1>
    <?php if($order->exists): ?>
      <p class="small text-muted mb-0">Nomor order: <span class="fw-medium text-dark">#<?php echo e($order->order_number); ?></span></p>
    <?php else: ?>
      <p class="small text-muted mb-0">Nomor order akan dibuat otomatis.</p>
    <?php endif; ?>
  </div>

  <form method="POST" action="<?php echo e($order->exists ? route('admin.order.update', $order) : route('admin.order.add')); ?>" class="card border rounded-4 p-4" style="max-width:42rem">
    <?php echo csrf_field(); ?>

    <div class="row g-3 mb-3">
      <div class="col-sm-6">
        <label class="form-label small fw-medium text-dark">Klien</label>
        <select name="client_id" class="form-select" style="<?php echo e($selectStyle); ?>" required>
          <option value="">Pilih klien</option>
          <?php $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($client->id); ?>" <?php if(old('client_id', $order->client_id) == $client->id): echo 'selected'; endif; ?>><?php echo e($client->name); ?></option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <?php $__errorArgs = ['client_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      </div>
      <div class="col-sm-6">
        <label class="form-label small fw-medium text-dark">Hosting Account Terkait (opsional)</label>
        <select name="hosting_account_id" class="form-select" style="<?php echo e($selectStyle); ?>">
          <option value="">— Tidak terkait —</option>
          <?php $__currentLoopData = $hostingAccounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ha): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($ha->id); ?>" <?php if(old('hosting_account_id', $order->hosting_account_id) == $ha->id): echo 'selected'; endif; ?>><?php echo e($ha->domain); ?></option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
      </div>
    </div>

    <div class="row g-3 mb-3">
      <div class="col-sm-6">
        <label class="form-label small fw-medium text-dark">Nama Produk</label>
        <input type="text" name="product_name" value="<?php echo e(old('product_name', $order->product_name)); ?>" placeholder="Cloud Hosting - Pro" class="form-control form-control-sm" required>
        <?php $__errorArgs = ['product_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      </div>
      <div class="col-sm-6">
        <label class="form-label small fw-medium text-dark">Tipe Order</label>
        <select name="order_type" class="form-select" style="<?php echo e($selectStyle); ?>">
          <option value="hosting" <?php if(old('order_type', $order->order_type) === 'hosting'): echo 'selected'; endif; ?>>Hosting</option>
          <option value="domain" <?php if(old('order_type', $order->order_type) === 'domain'): echo 'selected'; endif; ?>>Domain</option>
          <option value="vps" <?php if(old('order_type', $order->order_type) === 'vps'): echo 'selected'; endif; ?>>VPS</option>
          <option value="addon" <?php if(old('order_type', $order->order_type) === 'addon'): echo 'selected'; endif; ?>>Lisensi / Addon</option>
          <option value="other" <?php if(old('order_type', $order->order_type) === 'other'): echo 'selected'; endif; ?>>Lainnya</option>
        </select>
      </div>
    </div>

    <div class="row g-3 mb-3">
      <div class="col-sm-6">
        <label class="form-label small fw-medium text-dark">Jumlah (Rp)</label>
        <input type="number" step="0.01" name="amount" value="<?php echo e(old('amount', $order->amount)); ?>" class="form-control form-control-sm" required>
        <?php $__errorArgs = ['amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      </div>
      <div class="col-sm-6">
        <label class="form-label small fw-medium text-dark">Status</label>
        <select name="status" class="form-select" style="<?php echo e($selectStyle); ?>">
          <?php $__currentLoopData = \App\Enums\OrderStatus::cases(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($status->value); ?>" <?php if(old('status', $currentStatus) === $status->value): echo 'selected'; endif; ?>><?php echo e(str_replace('_', ' ', ucfirst($status->value))); ?></option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
      </div>
    </div>

    <div class="d-flex align-items-center gap-2 pt-2">
      <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-check" style="font-size:11px"></i> Simpan</button>
      <a href="<?php echo e(route('admin.orders')); ?>" class="btn btn-outline-secondary btn-sm">Batal</a>
    </div>
  </form>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/admin/orders/form.blade.php ENDPATH**/ ?>