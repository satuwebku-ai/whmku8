<?php $__env->startSection('title', 'Detail Pembayaran ' . $payment->reference); ?>

<?php $__env->startSection('content'); ?>

  <?php
    $badgeMap = ['paid' => 'badge-soft-success', 'pending' => 'badge-soft-warning', 'inactive' => 'badge-soft-secondary', 'suspended' => 'badge-soft-danger'];
  ?>

  <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <div>
      <a href="<?php echo e(route('admin.payments')); ?>" class="text-decoration-none text-muted" style="font-size:12px"><i class="fa-solid fa-arrow-left"></i> Kembali ke Pembayaran</a>
      <h1 class="h4 fw-bold text-dark mt-1 mb-0"><?php echo e($payment->reference); ?></h1>
    </div>
    <span class="badge <?php echo e($badgeMap[$payment->status_badge] ?? 'badge-soft-secondary'); ?>" style="font-size:13px;padding:.4rem .8rem"><?php echo e(ucfirst($payment->status)); ?></span>
  </div>

  <div class="row g-3">
    <div class="col-12 col-lg-8">

      <?php
        $premiumDomains = $payment->invoice
          ? \App\Models\Domain::where('is_premium', true)->whereIn('order_id', $payment->invoice->items()->pluck('order_id')->filter())->get()
          : collect();
      ?>
      <?php $__currentLoopData = $premiumDomains; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pd): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="alert alert-warning small mb-3">
          <i class="fa-solid fa-crown"></i>
          Invoice ini berisi domain premium <strong><?php echo e($pd->domain_name); ?></strong>. Menyetujui pembayaran hanya menandai invoice lunas —
          registrasi domainnya diselesaikan manual di
          <a href="<?php echo e(route('admin.domains.details', $pd)); ?>" class="alert-link">halaman detail domain</a>.
        </div>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

      <div class="card border rounded-4 p-4 mb-3">
        <h2 class="small fw-bold text-dark mb-3">Informasi Pembayaran</h2>
        <div class="row g-3 small">
          <div class="col-sm-6">
            <p class="text-muted mb-1" style="font-size:11px">KLIEN</p>
            <p class="fw-medium text-dark mb-0"><?php echo e($payment->client->name ?? '—'); ?></p>
          </div>
          <div class="col-sm-6">
            <p class="text-muted mb-1" style="font-size:11px">INVOICE</p>
            <p class="fw-medium text-dark mb-0">
              <?php if($payment->invoice): ?>
                <a href="<?php echo e(route('admin.invoices.details', $payment->invoice)); ?>" class="text-decoration-none text-accent"><?php echo e($payment->invoice->invoice_number); ?></a>
              <?php else: ?>
                —
              <?php endif; ?>
            </p>
          </div>
          <div class="col-sm-6">
            <p class="text-muted mb-1" style="font-size:11px">GATEWAY</p>
            <p class="fw-medium text-dark mb-0"><?php echo e($payment->gateway->name ?? '—'); ?> <span class="text-muted" style="font-size:11px">(<?php echo e($payment->gateway->driver_label ?? ''); ?>)</span></p>
          </div>
          <div class="col-sm-6">
            <p class="text-muted mb-1" style="font-size:11px">METODE</p>
            <p class="fw-medium text-dark mb-0"><?php echo e($payment->payment_method ?? '—'); ?></p>
          </div>
          <div class="col-sm-6">
            <p class="text-muted mb-1" style="font-size:11px">ID TRANSAKSI GATEWAY</p>
            <p class="fw-medium text-dark mb-0" style="word-break:break-all"><?php echo e($payment->external_id ?? '—'); ?></p>
          </div>
          <div class="col-sm-6">
            <p class="text-muted mb-1" style="font-size:11px">DIBAYAR PADA</p>
            <p class="fw-medium text-dark mb-0"><?php echo e($payment->paid_at?->format('d M Y H:i') ?? '—'); ?></p>
          </div>
        </div>

        <div class="border-top mt-3 pt-3 small">
          <div class="d-flex justify-content-between mb-1"><span class="text-muted">Nominal Invoice</span><span class="text-dark">Rp <?php echo e(number_format($payment->amount, 0, ',', '.')); ?></span></div>
          <div class="d-flex justify-content-between mb-2"><span class="text-muted">Biaya Gateway</span><span class="text-dark">Rp <?php echo e(number_format($payment->fee, 0, ',', '.')); ?></span></div>
          <div class="d-flex justify-content-between fw-bold text-dark border-top pt-2" style="font-size:15px"><span>Total Ditagih</span><span>Rp <?php echo e(number_format($payment->total, 0, ',', '.')); ?></span></div>
        </div>

        <?php if($payment->payment_url): ?>
          <div class="mt-3 pt-3 border-top">
            <p class="text-muted mb-2" style="font-size:11px">Link Pembayaran (kirim ke klien)</p>
            <div class="d-flex align-items-center gap-2">
              <input type="text" readonly value="<?php echo e($payment->payment_url); ?>" class="form-control form-control-sm" style="font-size:11px" id="payUrl">
              <button type="button" data-action="copy" data-target="payUrl" class="btn btn-outline-secondary btn-sm flex-shrink-0"><i class="fa-regular fa-copy" style="font-size:11px"></i></button>
            </div>
          </div>
        <?php endif; ?>

        <?php if($payment->gateway?->isManual() && $payment->gateway->instructions): ?>
          <div class="mt-3 pt-3 border-top">
            <p class="text-muted mb-2" style="font-size:11px">Instruksi Transfer</p>
            <div class="small text-dark rounded-3 p-3" style="white-space:pre-line;background:#f8fafc"><?php echo e($payment->gateway->instructions); ?></div>
          </div>
        <?php endif; ?>
      </div>

      <?php if($payment->gateway_response): ?>
        <div class="card border rounded-4 p-4">
          <h2 class="small fw-bold text-dark mb-2">Respons Gateway (audit)</h2>
          <pre class="rounded-3 p-3 mb-0" style="font-size:11px;background:#0f172a;color:#e2e8f0;overflow-x:auto"><?php echo e(json_encode($payment->gateway_response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)); ?></pre>
        </div>
      <?php endif; ?>
    </div>

    <div class="col-12 col-lg-4">
      <?php if($payment->proof_path): ?>
        <div class="card border rounded-4 p-4 mb-3" style="border-color:#c7d2fe!important;background:rgba(79,70,229,.04)">
          <h2 class="small fw-bold text-dark mb-3">
            <i class="fa-solid fa-receipt text-accent"></i> Bukti Transfer dari Klien
          </h2>

          <?php $isImage = ! str_ends_with(strtolower($payment->proof_path), '.pdf'); ?>

          
          <?php if($isImage): ?>
            <a href="<?php echo e(route('admin.payments.proof', $payment)); ?>" target="_blank" rel="noopener">
              <img src="<?php echo e(route('admin.payments.proof', $payment)); ?>" alt="Bukti transfer"
                   class="w-100 rounded-3 border" style="max-height:20rem;object-fit:contain;background:#fff">
            </a>
          <?php else: ?>
            <a href="<?php echo e(route('admin.payments.proof', $payment)); ?>" target="_blank" rel="noopener"
               class="d-flex align-items-center gap-2 small text-decoration-none text-accent rounded-3 border px-3 py-2" style="background:#fff">
              <i class="fa-solid fa-file-pdf"></i> Buka Berkas PDF
            </a>
          <?php endif; ?>

          <a href="<?php echo e(route('admin.payments.proof', $payment)); ?>" target="_blank" rel="noopener"
             class="d-block text-center text-decoration-none text-muted mt-2" style="font-size:11px">
            Buka di tab baru / unduh
          </a>
        </div>
      <?php endif; ?>

      <div class="card border rounded-4 p-4 mb-3">
        <h2 class="small fw-bold text-dark mb-3">Verifikasi</h2>

        <?php if($payment->status !== 'paid'): ?>
          <form method="POST" action="<?php echo e(route('admin.payment.approve')); ?>" data-confirm="Setujui pembayaran ini? Invoice terkait akan ditandai lunas." data-confirm-title="Setujui Pembayaran" data-confirm-style="info" data-confirm-label="Ya, Setujui">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="payment_id" value="<?php echo e($payment->id); ?>">
            <textarea name="admin_note" rows="2" class="form-control form-control-sm mb-2" placeholder="Catatan admin (opsional)"><?php echo e($payment->admin_note); ?></textarea>
            <button type="submit" class="btn btn-primary btn-sm w-100 text-start"><i class="fa-solid fa-check" style="font-size:11px"></i> Setujui &amp; Lunasi</button>
          </form>

          <form method="POST" action="<?php echo e(route('admin.payment.reject')); ?>" class="mt-2" data-confirm="Tolak pembayaran ini?" data-confirm-title="Konfirmasi" data-confirm-style="warn" data-confirm-label="Lanjutkan">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="payment_id" value="<?php echo e($payment->id); ?>">
            <button type="submit" class="btn btn-outline-danger btn-sm w-100 text-start"><i class="fa-solid fa-xmark" style="font-size:11px"></i> Tolak Pembayaran</button>
          </form>
        <?php else: ?>
          <p class="text-success mb-3" style="font-size:13px"><i class="fa-solid fa-circle-check"></i> Pembayaran sudah terverifikasi lunas.</p>

          <form method="POST" action="<?php echo e(route('admin.payment.refund', $payment)); ?>" data-confirm="Catat refund pembayaran ini? Saldo, invoice, dan komisi terkait akan disesuaikan. Pengembalian dana ke klien tetap dilakukan terpisah." data-confirm-title="Refund Pembayaran" data-confirm-style="warn" data-confirm-label="Ya, Catat Refund">
            <?php echo csrf_field(); ?>
            <textarea name="reason" rows="2" minlength="5" maxlength="500" required class="form-control form-control-sm mb-2" placeholder="Alasan refund (wajib)"><?php echo e(old('reason')); ?></textarea>
            <button type="submit" class="btn btn-outline-danger btn-sm w-100 text-start"><i class="fa-solid fa-rotate-left" style="font-size:11px"></i> Refund Pembayaran</button>
          </form>
        <?php endif; ?>

        <?php if($payment->gateway && ! $payment->gateway->isManual()): ?>
          <form method="POST" action="<?php echo e(route('admin.payment.check.status', $payment)); ?>" class="mt-2">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn btn-outline-secondary btn-sm w-100 text-start"><i class="fa-solid fa-rotate" style="font-size:11px"></i> Cek Status ke Gateway</button>
          </form>
        <?php endif; ?>
      </div>

      <?php if($payment->admin_note): ?>
        <div class="card border rounded-4 p-4">
          <h2 class="small fw-bold text-dark mb-2">Catatan Admin</h2>
          <p class="small text-dark mb-0" style="white-space:pre-line"><?php echo e($payment->admin_note); ?></p>
        </div>
      <?php endif; ?>
    </div>
  </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/admin/payments/details.blade.php ENDPATH**/ ?>