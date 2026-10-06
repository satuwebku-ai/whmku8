<?php $__env->startSection('title', $invoice->invoice_number); ?>

<?php $__env->startSection('content'); ?>
  <?php
    $badgeMap = ['unpaid' => 'badge-soft-warning', 'paid' => 'badge-soft-success', 'overdue' => 'badge-soft-danger', 'cancelled' => 'badge-soft-secondary'];
  ?>

  <a href="<?php echo e(route('client.invoices')); ?>" class="text-decoration-none text-muted" style="font-size:12px">&larr; Kembali ke Invoice</a>

  <div class="d-flex align-items-center justify-content-between mt-2 mb-4 flex-wrap gap-3">
    <h1 class="h4 fw-bold text-dark mb-0"><?php echo e($invoice->invoice_number); ?></h1>
    <div class="d-flex align-items-center gap-2">
      <span class="badge <?php echo e($badgeMap[$invoice->is_overdue ? 'overdue' : $invoice->status] ?? 'badge-soft-secondary'); ?>">
        <?php echo e($invoice->is_overdue ? 'Terlambat' : ucfirst($invoice->status)); ?>

      </span>
      <a href="<?php echo e(route('client.invoices.pdf', $invoice)); ?>" class="btn btn-outline-secondary btn-sm">
        <i class="fa-solid fa-file-arrow-down" style="font-size:11px"></i> Unduh PDF
      </a>
    </div>
  </div>

  <div class="row g-4">
    <div class="col-12 col-lg-8">
      <div class="card-public p-4">
        <div class="d-flex justify-content-between align-items-start mb-4 pb-4 border-bottom">
          <div>
            <p class="text-muted mb-0" style="font-size:11px">Ditagihkan kepada</p>
            <p class="fw-semibold text-dark mt-1 mb-0"><?php echo e($invoice->client->name); ?></p>
            <p class="text-muted mb-0" style="font-size:14px"><?php echo e($invoice->client->email); ?></p>
          </div>
          <div class="text-end">
            <p class="text-muted mb-0" style="font-size:11px">Tanggal Terbit</p>
            <p class="fw-medium text-dark mb-0" style="font-size:14px"><?php echo e($invoice->issue_date->format('d M Y')); ?></p>
            <p class="text-muted mt-2 mb-0" style="font-size:11px">Jatuh Tempo</p>
            <p class="fw-medium text-dark mb-0" style="font-size:14px"><?php echo e($invoice->due_date->format('d M Y')); ?></p>
          </div>
        </div>

        <div class="table-responsive mb-4">
          <table class="table mb-0">
            <thead>
              <tr class="small text-uppercase text-muted border-bottom">
                <th class="pb-2">Deskripsi</th>
                <th class="pb-2 text-end">Jumlah</th>
              </tr>
            </thead>
            <tbody>
              <?php $__empty_1 = true; $__currentLoopData = $invoice->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lineItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                  <td class="py-3 text-dark" style="font-size:14px"><?php echo e($lineItem->description); ?></td>
                  <td class="py-3 text-end text-dark" style="font-size:14px">Rp <?php echo e(number_format($lineItem->amount, 0, ',', '.')); ?></td>
                </tr>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                  <td class="py-3 text-dark" style="font-size:14px">
                    <?php echo e($invoice->order->product_name ?? 'Tagihan layanan'); ?>

                    <?php if($invoice->order): ?>
                      <span class="d-block text-muted" style="font-size:11px">Order #<?php echo e($invoice->order->order_number); ?></span>
                    <?php endif; ?>
                  </td>
                  <td class="py-3 text-end text-dark" style="font-size:14px">Rp <?php echo e(number_format($invoice->amount, 0, ',', '.')); ?></td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

        <div class="d-flex flex-column gap-2 pt-3 border-top" style="font-size:14px">
          <div class="d-flex justify-content-between"><span class="text-muted">Subtotal</span><span class="text-dark">Rp <?php echo e(number_format($invoice->amount, 0, ',', '.')); ?></span></div>
          <div class="d-flex justify-content-between"><span class="text-muted">Pajak</span><span class="text-dark">Rp <?php echo e(number_format($invoice->tax, 0, ',', '.')); ?></span></div>
          <?php if($invoice->discount > 0): ?>
            <div class="d-flex justify-content-between text-success">
              <span>Kupon<?php echo e($invoice->coupon ? ' ' . $invoice->coupon->code : ''); ?></span>
              <span>- Rp <?php echo e(number_format($invoice->discount, 0, ',', '.')); ?></span>
            </div>
          <?php endif; ?>
          <div class="d-flex justify-content-between fw-bold text-dark pt-2 border-top" style="font-size:1.1rem">
            <span>Total</span><span>Rp <?php echo e(number_format($invoice->total, 0, ',', '.')); ?></span>
          </div>
        </div>

        <?php if($invoice->notes): ?>
          <div class="mt-4 pt-4 border-top">
            <p class="text-muted mb-1" style="font-size:11px">Catatan</p>
            <p class="text-muted mb-0" style="font-size:14px;white-space:pre-line"><?php echo e($invoice->notes); ?></p>
          </div>
        <?php endif; ?>
      </div>
    </div>

    
    <div class="col-12 col-lg-4 d-flex flex-column gap-4">
      <?php if($invoice->status === 'paid'): ?>
        <div class="card-public p-4 text-center" style="border-color:#a7f3d0!important;background:#f0fdf4">
          <i class="fa-solid fa-circle-check text-success mb-2" style="font-size:1.75rem"></i>
          <p class="fw-semibold mb-1" style="color:#065f46">Invoice Lunas</p>
          <p class="mb-0" style="font-size:11px;color:#047857">
            Dibayar <?php echo e($invoice->paid_at?->format('d M Y')); ?>

            <?php if($invoice->payment_method): ?> via <?php echo e($invoice->payment_method); ?> <?php endif; ?>
          </p>
        </div>

      <?php elseif($invoice->status === 'cancelled'): ?>
        <div class="card-public p-4 text-center text-muted">
          <p class="mb-0" style="font-size:14px">Invoice ini sudah dibatalkan.</p>
        </div>

      <?php else: ?>
        
        <?php if($pendingPayment && $pendingPayment->payment_url): ?>
          <div class="card-public p-4" style="border-color:#fde68a!important;background:#fffbeb">
            <p class="fw-semibold mb-1" style="font-size:14px;color:#92400e">Pembayaran Sedang Diproses</p>
            <p class="mb-3" style="font-size:11px;color:#b45309">
              Anda sudah memulai pembayaran (<?php echo e($pendingPayment->reference); ?>). Lanjutkan di link berikut,
              atau pilih metode lain di bawah.
            </p>
            <a href="<?php echo e($pendingPayment->payment_url); ?>" class="btn btn-theme w-100">
              <i class="fa-solid fa-arrow-up-right-from-square" style="font-size:11px"></i> Lanjutkan Pembayaran
            </a>
          </div>
        <?php endif; ?>

        <?php $clientBalance = (float) (auth('client')->user()->balance ?? 0); ?>
        <?php if(! $invoice->is_topup && $clientBalance >= (float) $invoice->total): ?>
          <div class="card-public p-4" style="border-color:#a7f3d0!important;background:#f0fdf4">
            <p class="mb-2" style="font-size:14px;color:#065f46">
              <i class="fa-solid fa-wallet"></i>
              Saldo Anda cukup — <b>Rp <?php echo e(number_format($clientBalance, 0, ',', '.')); ?></b>
            </p>
            <form method="POST" action="<?php echo e(route('client.balance.pay', $invoice)); ?>">
              <?php echo csrf_field(); ?>
              <button type="submit" class="btn btn-success w-100 btn-sm">
                Bayar dengan Saldo
              </button>
            </form>
          </div>
        <?php endif; ?>

        <div class="card-public p-4">
          <h2 class="small fw-bold text-dark mb-1">Bayar Invoice</h2>
          <p class="text-muted mb-3" style="font-size:12px">Pilih metode pembayaran yang Anda inginkan.</p>

          <?php if($gateways->isEmpty()): ?>
            <p class="text-muted mb-0" style="font-size:14px">Belum ada metode pembayaran tersedia. Silakan hubungi support.</p>
          <?php else: ?>
            
            <?php $qrisGateway = $gateways->first(fn ($g) => $g->supportsEmbeddedQris()); ?>
            <?php if($qrisGateway): ?>
              <form method="POST" action="<?php echo e(route('client.invoices.qris', [$invoice, $qrisGateway])); ?>"
                    class="mb-3">
                <?php echo csrf_field(); ?>
                <button type="submit"
                        class="w-100 d-flex align-items-center gap-3 p-3 rounded-3 text-start border-0 text-decoration-none" style="border:2px solid rgba(79,70,229,.25)!important;background:rgba(79,70,229,.04)">
                <span class="rounded-3 bg-white d-flex align-items-center justify-content-center flex-shrink-0" style="width:40px;height:40px;border:1px solid rgba(79,70,229,.2)">
                  <i class="fa-solid fa-qrcode text-theme"></i>
                </span>
                <span class="flex-grow-1">
                  <span class="d-block fw-semibold text-dark" style="font-size:14px">Bayar dengan QRIS</span>
                  <span class="d-block text-muted" style="font-size:11px">Scan langsung dari halaman ini — tanpa pindah situs</span>
                </span>
                <i class="fa-solid fa-arrow-right text-theme" style="font-size:11px"></i>
                </button>
              </form>

              <div class="d-flex align-items-center gap-3 mb-3">
                <span class="flex-grow-1 border-top"></span>
                <span class="text-muted" style="font-size:11px">atau metode lain</span>
                <span class="flex-grow-1 border-top"></span>
              </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('client.invoices.pay', $invoice)); ?>" class="d-flex flex-column gap-3">
              <?php echo csrf_field(); ?>

              <div class="d-flex flex-column gap-2">
                <?php $__currentLoopData = $gateways; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gw): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <?php $fee = $gw->calculateFee((float) $invoice->total); ?>
                  <label class="d-flex align-items-start gap-3 p-3 rounded-3 border" style="cursor:pointer">
                    <input type="radio" name="payment_gateway_id" value="<?php echo e($gw->id); ?>" required style="margin-top:2px">
                    <span class="flex-grow-1 min-w-0">
                      <span class="d-block fw-medium text-dark" style="font-size:14px"><?php echo e($gw->name); ?></span>
                      <?php if($fee > 0): ?>
                        <span class="d-block text-muted" style="font-size:11px">
                          + biaya Rp <?php echo e(number_format($fee, 0, ',', '.')); ?>

                          — total Rp <?php echo e(number_format($invoice->total + $fee, 0, ',', '.')); ?>

                        </span>
                      <?php else: ?>
                        <span class="d-block text-success" style="font-size:11px">Tanpa biaya tambahan</span>
                      <?php endif; ?>
                    </span>
                  </label>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </div>

              <button type="submit" class="btn btn-theme w-100">
                <i class="fa-solid fa-credit-card" style="font-size:11px"></i> Lanjutkan Pembayaran
              </button>
            </form>
          <?php endif; ?>
        </div>

        
        <?php $__currentLoopData = $gateways->where('driver', 'manual'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $manual): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <?php if($manual->instructions): ?>
            <div class="card-public p-4">
              <h2 class="small fw-bold text-dark mb-2"><?php echo e($manual->name); ?></h2>
              <div class="text-muted rounded-3 p-3" style="font-size:14px;white-space:pre-line;background:#f8fafc"><?php echo e($manual->instructions); ?></div>

              <?php if($pendingPayment && $pendingPayment->payment_gateway_id === $manual->id): ?>
                <div class="mt-3 pt-3 border-top">
                  <?php if($pendingPayment->proof_path): ?>
                    <p class="mb-0 rounded-3 px-3 py-2" style="font-size:12px;color:#047857;background:#f0fdf4;border:1px solid #a7f3d0">
                      <i class="fa-solid fa-circle-check"></i> Bukti transfer sudah dikirim, menunggu diperiksa tim kami.
                    </p>
                  <?php else: ?>
                    <p class="text-muted mb-2" style="font-size:12px">
                      Sudah transfer? Unggah buktinya di sini supaya kami tahu untuk memeriksanya —
                      tidak perlu menghubungi kami secara terpisah.
                    </p>
                    <form method="POST" action="<?php echo e(route('client.payment.confirm', $pendingPayment)); ?>"
                          enctype="multipart/form-data" class="d-flex flex-column gap-2">
                      <?php echo csrf_field(); ?>
                      <input type="file" name="proof" accept="image/*,application/pdf" required class="form-control form-control-sm">
                      <?php $__errorArgs = ['proof'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                      <textarea name="note" rows="2" placeholder="Catatan tambahan (opsional)" class="form-control form-control-sm"></textarea>
                      <button type="submit" class="btn btn-theme w-100 btn-sm">
                        <i class="fa-solid fa-upload" style="font-size:11px"></i> Kirim Bukti Transfer
                      </button>
                    </form>
                  <?php endif; ?>
                </div>
              <?php else: ?>
                <p class="text-muted mt-2 mb-0" style="font-size:11px">
                  Cantumkan nomor invoice <b><?php echo e($invoice->invoice_number); ?></b> saat konfirmasi transfer.
                </p>
              <?php endif; ?>
            </div>
          <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      <?php endif; ?>
    </div>
  </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('client.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/themes/client-themes/default/client/invoices/show.blade.php ENDPATH**/ ?>