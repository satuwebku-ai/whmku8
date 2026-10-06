<?php $__env->startSection('title', 'Affiliate'); ?>

<?php $__env->startSection('content'); ?>

  <div class="mb-4">
    <h1 class="h4 fw-bold text-dark mb-1">Affiliate</h1>
    <p class="text-muted mb-0">Bagikan link referral. Jika referral melakukan pembayaran yang memenuhi aturan program, komisi masuk ke status pending dan dapat disetujui admin.</p>
  </div>

  <?php if($rate): ?>
    <?php [$rateType, $rateValue] = $rate; ?>
    <div class="rounded-3 p-3 mb-4" style="background:#eef2ff;color:#4338ca;font-size:13px;max-width:48rem">
      <i class="fa-solid fa-circle-info"></i>
      Program Anda: dapat komisi <b><?php echo e($rateType === 'percentage' ? $rateValue . '%' : 'Rp ' . number_format($rateValue, 0, ',', '.')); ?></b>
      dari <?php echo e($rateType === 'percentage' ? 'nilai' : 'setiap'); ?> transaksi orang yang Anda referensikan,
      <?php echo e(\App\Models\Setting::get('affiliate_commission_repeat', true) ? 'berulang setiap kali mereka membayar perpanjangan' : 'satu kali saja di pembelian pertama mereka'); ?>.
      Komisi masuk sebagai <b>pending</b> dulu, cair ke wallet setelah disetujui admin.
    </div>
  <?php endif; ?>

  <?php if(session('success')): ?>
    <div class="alert alert-success"><?php echo e(session('success')); ?></div>
  <?php endif; ?>
  <?php if(session('error')): ?>
    <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
  <?php endif; ?>

  <?php if(! $affiliate): ?>
    
    <div class="card border rounded-4 p-4 text-center">
      <p class="text-muted mb-3">Anda belum terdaftar sebagai affiliate. Daftar sekarang, gratis, tanpa syarat minimum.</p>
      <form method="POST" action="<?php echo e(route('client.affiliate.register')); ?>">
        <?php echo csrf_field(); ?>
        <button type="submit" class="btn btn-primary">Daftar Jadi Affiliate</button>
      </form>
    </div>

  <?php elseif($affiliate->status === 'pending'): ?>
    <div class="card border rounded-4 p-4 text-center">
      <p class="text-muted mb-0">Pendaftaran Anda sedang ditinjau admin. Kode referral Anda: <b style="font-family:monospace"><?php echo e($affiliate->code); ?></b></p>
    </div>

  <?php elseif($affiliate->status === 'rejected'): ?>
    <div class="card border rounded-4 p-4 text-center">
      <p class="text-danger mb-0">Pendaftaran affiliate Anda ditolak. Alasan: <?php echo e($affiliate->rejected_reason ?? '—'); ?></p>
    </div>

  <?php elseif($affiliate->status === 'suspended'): ?>
    <div class="card border rounded-4 p-4 text-center">
      <p class="text-danger mb-0">Akun affiliate Anda sedang disuspend. Hubungi support untuk info lebih lanjut.</p>
    </div>

  <?php else: ?>
    
    <div class="card border rounded-4 p-3 mb-4">
      <div class="small text-muted mb-1">Link Referral Utama Anda</div>
      <div class="input-group">
        <input type="text" class="form-control" readonly value="<?php echo e(url('/ref/' . $affiliate->code)); ?>" id="refLink">
        <button class="btn btn-outline-secondary" type="button" data-action="copy" data-target="refLink">Salin</button>
      </div>
    </div>

    <div class="row g-3 mb-4">
      <div class="col-6 col-md-2">
        <div class="card border rounded-4 p-3">
          <div class="small text-muted">Klik</div>
          <div class="h5 fw-bold mb-0"><?php echo e($summary['clicks']); ?></div>
        </div>
      </div>
      <div class="col-6 col-md-2">
        <div class="card border rounded-4 p-3">
          <div class="small text-muted">Referral</div>
          <div class="h5 fw-bold mb-0"><?php echo e($summary['referrals']); ?></div>
        </div>
      </div>
      <div class="col-6 col-md-2">
        <div class="card border rounded-4 p-3">
          <div class="small text-muted">Konversi</div>
          <div class="h5 fw-bold mb-0"><?php echo e($summary['conversions']); ?></div>
        </div>
      </div>
      <div class="col-6 col-md-2">
        <div class="card border rounded-4 p-3">
          <div class="small text-muted">Conversion Rate</div>
          <div class="h5 fw-bold mb-0"><?php echo e($summary['conversion_rate']); ?>%</div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="card border rounded-4 p-3">
          <div class="small text-muted">Komisi Pending</div>
          <div class="h6 fw-bold mb-0">Rp <?php echo e(number_format($summary['commission_pending'], 0, ',', '.')); ?></div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="card border rounded-4 p-3">
          <div class="small text-muted">Saldo Wallet</div>
          <div class="h6 fw-bold mb-0">Rp <?php echo e(number_format($summary['wallet_balance'], 0, ',', '.')); ?></div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="card border rounded-4 p-3">
          <div class="small text-muted">Total Withdrawn</div>
          <div class="h6 fw-bold mb-0">Rp <?php echo e(number_format($summary['total_withdrawn'], 0, ',', '.')); ?></div>
        </div>
      </div>
    </div>

    <div class="row g-3 mb-4">
      
      <div class="col-md-6">
        <div class="card border rounded-4 h-100">
          <div class="px-4 py-3 border-bottom d-flex align-items-center justify-content-between">
            <span class="fw-bold small text-uppercase text-muted">Link Campaign</span>
          </div>
          <div class="p-4">
            <form method="POST" action="<?php echo e(route('client.affiliate.campaign')); ?>" class="d-flex gap-2 mb-3">
              <?php echo csrf_field(); ?>
              <input type="text" name="name" required placeholder="Nama campaign, mis. Promo IG" class="form-control form-control-sm">
              <button type="submit" class="btn btn-primary btn-sm text-nowrap">Buat</button>
            </form>
            <?php $__empty_1 = true; $__currentLoopData = $campaigns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $camp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
              <div class="d-flex align-items-center justify-content-between border-top pt-2 mt-2">
                <div>
                  <div class="small fw-bold"><?php echo e($camp->name); ?></div>
                  <div class="small text-muted" style="font-family:monospace"><?php echo e(url('/ref/' . $affiliate->code . '/' . $camp->code)); ?></div>
                </div>
                <span class="small text-muted"><?php echo e($camp->clicks_count); ?> klik</span>
              </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
              <p class="small text-muted mb-0">Belum ada link campaign. Buat untuk melacak sumber promosi berbeda-beda.</p>
            <?php endif; ?>
          </div>
        </div>
      </div>

      
      <div class="col-md-6">
        <div class="card border rounded-4 h-100">
          <div class="px-4 py-3 border-bottom">
            <span class="fw-bold small text-uppercase text-muted">Rekening & Payout</span>
          </div>
          <div class="p-4">
            <form method="POST" action="<?php echo e(route('client.affiliate.bank')); ?>" class="mb-3">
              <?php echo csrf_field(); ?>
              <div class="row g-2 mb-2">
                <div class="col-6"><input type="text" name="bank_name" required value="<?php echo e(old('bank_name', $affiliate->bank_name)); ?>" placeholder="Nama Bank" class="form-control form-control-sm"></div>
                <div class="col-6"><input type="text" name="bank_account_number" required value="<?php echo e(old('bank_account_number', $affiliate->bank_account_number)); ?>" placeholder="No. Rekening" class="form-control form-control-sm"></div>
              </div>
              <input type="text" name="bank_account_name" required value="<?php echo e(old('bank_account_name', $affiliate->bank_account_name)); ?>" placeholder="Nama Pemilik Rekening" class="form-control form-control-sm mb-2">
              <button type="submit" class="btn btn-outline-secondary btn-sm">Simpan Rekening</button>
            </form>

            <form method="POST" action="<?php echo e(route('client.affiliate.payout')); ?>" class="d-flex gap-2 border-top pt-3">
              <?php echo csrf_field(); ?>
              <input type="number" name="amount" min="1" step="1" required placeholder="Nominal payout" class="form-control form-control-sm">
              <button type="submit" class="btn btn-primary btn-sm text-nowrap">Minta Payout</button>
            </form>
            <p class="small text-muted mt-2 mb-0">Minimal payout Rp <?php echo e(number_format((float) \App\Models\Setting::get('affiliate_min_payout', 50000), 0, ',', '.')); ?>. Saldo dipotong saat payout disetujui admin.</p>
            <div class="small text-muted mt-1">Alur: klik → daftar → bayar → komisi pending → admin approve → wallet → payout.</div>
          </div>
        </div>
      </div>
    </div>

    <div class="card border rounded-4">
      <div class="px-4 py-3 border-bottom fw-bold small text-uppercase text-muted">Riwayat Payout</div>
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead>
            <tr class="small text-uppercase text-muted" style="background:#f8fafc">
              <th class="px-4 py-3">Tanggal</th>
              <th class="py-3">Nominal</th>
              <th class="py-3">Status</th>
            </tr>
          </thead>
          <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $payouts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
              <tr>
                <td class="px-4 py-3 small text-muted"><?php echo e($p->created_at->format('d M Y')); ?></td>
                <td class="py-3">Rp <?php echo e(number_format($p->amount, 0, ',', '.')); ?></td>
                <td class="py-3">
                  <?php $pBadge = ['pending' => 'badge-soft-warning', 'approved' => 'badge-soft-info', 'paid' => 'badge-soft-success', 'rejected' => 'badge-soft-danger']; ?>
                  <span class="badge <?php echo e($pBadge[$p->status]); ?>"><?php echo e(ucfirst($p->status)); ?></span>
                </td>
              </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
              <tr><td colspan="3" class="text-center text-muted py-4">Belum pernah minta payout.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

    <div class="card border rounded-4 mt-4">
      <div class="px-4 py-3 border-bottom fw-bold small text-uppercase text-muted">Detail Referral</div>
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead>
            <tr class="small text-uppercase text-muted" style="background:#f8fafc">
              <th class="px-4 py-3">Client</th><th>Terdaftar</th><th>Invoice</th><th>Komisi</th><th>Status</th>
            </tr>
          </thead>
          <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $referrals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $referral): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
              <tr>
                <td class="px-4 py-3"><?php echo e($referral->client?->name ?? 'Client'); ?><br><span class="small text-muted"><?php echo e($referral->client?->email); ?></span></td>
                <td class="small text-muted"><?php echo e($referral->created_at->format('d M Y')); ?></td>
                <td><?php echo e($referral->conversion?->invoice?->invoice_number ?? 'Belum ada pembayaran'); ?></td>
                <td><?php echo e($referral->conversion?->commission?->amount ? 'Rp '.number_format((float) $referral->conversion->commission->amount, 0, ',', '.') : '—'); ?></td>
                <td><span class="badge badge-soft-<?php echo e($referral->conversion?->commission?->status === 'approved' ? 'success' : 'warning'); ?>"><?php echo e(ucfirst($referral->conversion?->commission?->status ?? $referral->status)); ?></span></td>
              </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
              <tr><td colspan="5" class="text-center text-muted py-4">Belum ada referral yang tercatat.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  <?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('client.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/themes/client-themes/default/client/affiliate/index.blade.php ENDPATH**/ ?>