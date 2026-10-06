<?php $__env->startSection('title', 'Bayar dengan QRIS'); ?>

<?php $__env->startSection('content'); ?>

  <a href="<?php echo e(route('client.invoices.show', $invoice)); ?>" class="text-decoration-none text-muted" style="font-size:12px">
    &larr; Kembali ke Invoice
  </a>

  <div class="mx-auto mt-3" style="max-width:26rem">
    <div class="card-public p-4 text-center">
      <h1 class="fw-bold text-dark mb-1" style="font-size:1.2rem">Bayar dengan QRIS</h1>
      <p class="text-muted mb-4" style="font-size:14px">
        Invoice <?php echo e($invoice->invoice_number); ?> — Rp <?php echo e(number_format((float) $payment->total, 0, ',', '.')); ?>

      </p>

      
      <div id="qrHolder" class="d-flex align-items-center justify-content-center bg-white p-3 rounded-4 border mx-auto" style="width:264px;height:264px"></div>
      <p class="text-muted mt-2 mb-0" style="font-size:11px">
        Berlaku sampai <?php echo e($payment->expires_at?->format('H:i')); ?> ·
        <span id="countdown"></span>
      </p>

      <div id="statusBox" class="mt-4">
        <p class="text-muted mb-0" style="font-size:14px">
          <i class="fa-solid fa-circle-notch fa-spin"></i> Menunggu pembayaran…
        </p>
      </div>

      <div class="mt-4 pt-4 border-top text-start">
        <p class="fw-bold text-muted mb-2" style="font-size:12px">Cara membayar</p>
        <ol class="text-muted mb-0 ps-3" style="font-size:12px">
          <li class="mb-1">Buka aplikasi e-wallet atau mobile banking yang mendukung QRIS</li>
          <li class="mb-1">Pilih menu Scan / Bayar dengan QR</li>
          <li class="mb-1">Arahkan kamera ke kode QR di atas</li>
          <li>Periksa nominal, lalu selesaikan pembayaran</li>
        </ol>
      </div>

      <a href="<?php echo e(route('client.invoices.show', $invoice)); ?>" class="d-block text-decoration-none text-theme mt-4" style="font-size:12px">
        Pilih metode pembayaran lain
      </a>
    </div>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
  <script <?php echo 'nonce="' . e(app(\App\Support\CspNonce::class)->value()) . '"'; ?>>
    (function () {
      new QRCode(document.getElementById('qrHolder'), {
        text: <?php echo json_encode($qrString, 15, 512) ?>,
        width: 240,
        height: 240,
        correctLevel: QRCode.CorrectLevel.M,
      });

      const statusBox = document.getElementById('statusBox');
      const countdownEl = document.getElementById('countdown');
      const expiresAt = <?php echo json_encode($payment->expires_at?->toIso8601String(), 15, 512) ?>;
      const statusUrl = <?php echo json_encode(route('client.invoices.qris-status', $payment), 512) ?>;
      const invoiceUrl = <?php echo json_encode(route('client.invoices.show', $invoice), 512) ?>;

      function tickCountdown() {
        if (!expiresAt) return;

        const diff = Math.max(0, new Date(expiresAt) - new Date());
        const minutes = Math.floor(diff / 60000);
        const seconds = Math.floor((diff % 60000) / 1000);

        countdownEl.textContent = diff > 0
          ? `sisa ${minutes}:${String(seconds).padStart(2, '0')}`
          : 'kedaluwarsa';
      }

      async function poll() {
        try {
          const res = await fetch(statusUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
          const data = await res.json();

          if (data.status === 'paid') {
            statusBox.innerHTML = '<p class="text-success fw-medium mb-0" style="font-size:14px">'
              + '<i class="fa-solid fa-circle-check"></i> Pembayaran berhasil! Mengalihkan…</p>';
            clearInterval(timer);
            setTimeout(() => window.location.href = invoiceUrl, 1500);
            return;
          }

          if (data.expired) {
            statusBox.innerHTML = '<p class="text-danger mb-0" style="font-size:14px">'
              + '<i class="fa-solid fa-circle-exclamation"></i> Kode QR sudah kedaluwarsa. '
              + '<a href="' + invoiceUrl + '" style="text-decoration:underline;color:inherit">Buat kode baru</a></p>';
            clearInterval(timer);
          }
        } catch (e) {
          // Diam saja saat gagal sesekali — percobaan berikutnya akan jalan normal.
        }
      }

      tickCountdown();
      poll();

      const timer = setInterval(function () { tickCountdown(); poll(); }, 5000);
    })();
  </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('client.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/themes/client-themes/default/client/invoices/qris.blade.php ENDPATH**/ ?>