<?php $__env->startSection('title', $invoice->exists ? 'Edit Invoice' : 'Buat Invoice'); ?>

<?php $__env->startSection('content'); ?>

  <div class="mb-4">
    <h1 class="h4 fw-bold text-dark mb-1"><?php echo e($invoice->exists ? 'Edit Invoice' : 'Buat Invoice Manual'); ?></h1>
    <?php if($invoice->exists): ?>
      <p class="small text-muted mb-0">No. Invoice: <span class="fw-medium text-dark"><?php echo e($invoice->invoice_number); ?></span></p>
    <?php else: ?>
      <p class="small text-muted mb-0">Nomor invoice akan dibuat otomatis (format INV-<?php echo e(date('Y')); ?>-xxxx).</p>
    <?php endif; ?>
  </div>

  <form method="POST" action="<?php echo e($invoice->exists ? route('admin.invoice.update', $invoice) : route('admin.invoice.add')); ?>" class="card border rounded-4 p-4" style="max-width:42rem">
    <?php echo csrf_field(); ?>

    <?php $selectStyle = 'padding:.25rem .6rem;font-size:.875rem;border-radius:.375rem'; ?>

    <div class="row g-3 mb-3">
      <div class="col-sm-6">
        <label class="form-label small fw-medium text-dark">Klien</label>
        <select name="client_id" class="form-select" style="<?php echo e($selectStyle); ?>" required>
          <option value="">Pilih klien</option>
          <?php $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($client->id); ?>" <?php if(old('client_id', $invoice->client_id) == $client->id): echo 'selected'; endif; ?>><?php echo e($client->name); ?></option>
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
        <label class="form-label small fw-medium text-dark">Order Terkait (opsional)</label>
        <?php
          // Invoice dari checkout menautkan order lewat item invoice
          // (invoice_items.order_id), sedangkan kolom invoices.order_id
          // sering kosong. Pakai keduanya supaya dropdown terisi otomatis.
          $linkedOrders = $invoice->exists
            ? $invoice->items->pluck('order')->filter()->unique('id')->values()
            : collect();
          $defaultOrderId = $invoice->order_id
            ?? ($linkedOrders->count() === 1 ? $linkedOrders->first()->id : null);
        ?>
        <select name="order_id" class="form-select" style="<?php echo e($selectStyle); ?>">
          <option value="">— Tidak terkait —</option>
          <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($order->id); ?>" <?php if((string) old('order_id', $defaultOrderId) === (string) $order->id): echo 'selected'; endif; ?>>#<?php echo e($order->order_number); ?> — <?php echo e($order->product_name); ?></option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <?php if($linkedOrders->isNotEmpty()): ?>
          <p class="text-muted mt-1 mb-0" style="font-size:11px">
            Order pada item invoice: <?php echo e($linkedOrders->map(fn ($o) => '#' . $o->order_number)->implode(', ')); ?>

          </p>
        <?php endif; ?>
        <?php $__errorArgs = ['order_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      </div>
    </div>

    <div class="row g-3 mb-3">
      <div class="col-sm-6">
        <label class="form-label small fw-medium text-dark">Jumlah / Subtotal (Rp)</label>
        <input type="number" step="0.01" name="amount" value="<?php echo e(old('amount', $invoice->amount)); ?>" class="form-control form-control-sm" required>
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
        <label class="form-label small fw-medium text-dark">Pajak (Rp, opsional)</label>
        <input type="number" step="0.01" name="tax" value="<?php echo e(old('tax', $invoice->tax ?? 0)); ?>" class="form-control form-control-sm">
      </div>
    </div>

    <div class="row g-3 mb-3">
      <div class="col-sm-6">
        <label class="form-label small fw-medium text-dark">Tanggal Terbit</label>
        <input type="date" name="issue_date" value="<?php echo e(old('issue_date', optional($invoice->issue_date)->format('Y-m-d') ?? now()->format('Y-m-d'))); ?>" class="form-control form-control-sm" required>
        <?php $__errorArgs = ['issue_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      </div>
      <div class="col-sm-6">
        <label class="form-label small fw-medium text-dark">Jatuh Tempo</label>
        <input type="date" name="due_date" value="<?php echo e(old('due_date', optional($invoice->due_date)->format('Y-m-d') ?? now()->addDays(7)->format('Y-m-d'))); ?>" class="form-control form-control-sm" required>
        <?php $__errorArgs = ['due_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      </div>
    </div>

    <div class="row g-3 mb-3">
      <div class="col-sm-6">
        <label class="form-label small fw-medium text-dark">Status</label>
        <select name="status" class="form-select" style="<?php echo e($selectStyle); ?>">
          <option value="unpaid" <?php if(old('status', $invoice->status) === 'unpaid'): echo 'selected'; endif; ?>>Unpaid</option>
          <option value="paid" <?php if(old('status', $invoice->status) === 'paid'): echo 'selected'; endif; ?>>Paid</option>
          <option value="overdue" <?php if(old('status', $invoice->status) === 'overdue'): echo 'selected'; endif; ?>>Overdue</option>
          <option value="cancelled" <?php if(old('status', $invoice->status) === 'cancelled'): echo 'selected'; endif; ?>>Cancelled</option>
        </select>
      </div>
      <div class="col-sm-6">
        <label class="form-label small fw-medium text-dark">Metode Pembayaran (opsional)</label>
        <input type="text" name="payment_method" value="<?php echo e(old('payment_method', $invoice->payment_method)); ?>" placeholder="Transfer Bank / Midtrans / dll" class="form-control form-control-sm">
      </div>
    </div>

    <div class="mb-3">
      <label class="form-label small fw-medium text-dark">Catatan (opsional)</label>
      <textarea name="notes" rows="2" class="form-control form-control-sm"><?php echo e(old('notes', $invoice->notes)); ?></textarea>
    </div>

    <div class="d-flex align-items-center gap-2 pt-2">
      <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-check" style="font-size:11px"></i> Simpan</button>
      <a href="<?php echo e(route('admin.invoices')); ?>" class="btn btn-outline-secondary btn-sm">Batal</a>
    </div>
  </form>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/admin/invoices/form.blade.php ENDPATH**/ ?>