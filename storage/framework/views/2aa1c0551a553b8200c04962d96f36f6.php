<?php $__env->startSection('title', 'Program Affiliate'); ?>

<?php $__env->startSection('content'); ?>

  <?php use App\Models\Setting; ?>

  <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <div>
      <h1 class="h4 fw-bold text-dark mb-1">Program Affiliate</h1>
      <p class="small text-muted mb-0">Daftar affiliate, klik, konversi, dan komisi.</p>
    </div>
    <div class="d-flex gap-2">
      <a href="<?php echo e(route('admin.affiliate.commissions.index')); ?>" class="btn btn-outline-secondary btn-sm">Komisi</a>
      <a href="<?php echo e(route('admin.affiliate.payouts.index')); ?>" class="btn btn-outline-secondary btn-sm">Payout</a>
      <a href="<?php echo e(route('admin.affiliate.rules.index')); ?>" class="btn btn-outline-secondary btn-sm">Rules</a>
      <a href="<?php echo e(route('admin.affiliate.fraud.index')); ?>" class="btn btn-outline-secondary btn-sm">Fraud Review</a>
      <a href="<?php echo e(route('admin.settings.affiliate')); ?>" class="btn btn-outline-secondary btn-sm"><i class="fa-solid fa-gear" style="font-size:11px"></i> Atur Komisi</a>
    </div>
  </div>

  <div class="rounded-3 p-3 mb-4" style="background:#eef2ff;color:#4338ca;font-size:13px;max-width:48rem">
    <i class="fa-solid fa-circle-info"></i>
    Komisi default saat ini: affiliate dapat
    <b><?php echo e(Setting::get('affiliate_commission_type', 'percentage') === 'percentage' ? Setting::get('affiliate_commission_value', 10) . '%' : 'Rp ' . number_format((float) Setting::get('affiliate_commission_value', 10), 0, ',', '.')); ?></b>
    dari <?php echo e(Setting::get('affiliate_commission_type', 'percentage') === 'percentage' ? 'nilai' : 'setiap'); ?> transaksi klien referral,
    <?php echo e(Setting::get('affiliate_commission_repeat', true) ? 'berulang setiap perpanjangan' : 'satu kali di pembelian pertama'); ?>.
    Affiliate baru berstatus <b>pending</b> sampai disetujui di sini. <a href="<?php echo e(route('admin.settings.affiliate')); ?>" style="color:inherit;text-decoration:underline">Ubah pengaturan ini</a>.
  </div>

  <div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
      <div class="card border rounded-4 p-3">
        <div class="small text-muted">Menunggu Approval</div>
        <div class="h5 fw-bold mb-0"><?php echo e($summary['affiliates_pending']); ?></div>
      </div>
      <div class="col-6 col-md-3">
        <div class="card border rounded-4 p-3">
          <div class="small text-muted">Fraud Review</div>
          <div class="h5 fw-bold mb-0"><?php echo e($summary['fraud_review']); ?></div>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="card border rounded-4 p-3">
        <div class="small text-muted">Affiliate Aktif</div>
        <div class="h5 fw-bold mb-0"><?php echo e($summary['affiliates_approved']); ?></div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="card border rounded-4 p-3">
        <div class="small text-muted">Total Komisi Disetujui</div>
        <div class="h5 fw-bold mb-0">Rp <?php echo e(number_format($summary['commission_approved_total'], 0, ',', '.')); ?></div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="card border rounded-4 p-3">
        <div class="small text-muted">Payout Menunggu</div>
        <div class="h5 fw-bold mb-0"><?php echo e($summary['payouts_pending']); ?> <span class="small text-muted">(Rp <?php echo e(number_format($summary['payouts_pending_amount'], 0, ',', '.')); ?>)</span></div>
      </div>
    </div>
  </div>

  <div class="card border rounded-4 overflow-hidden">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead>
          <tr class="small text-uppercase text-muted" style="background:#f8fafc">
            <th class="px-4 py-3">Kode</th>
            <th class="py-3">Client</th>
            <th class="py-3">Status</th>
            <th class="py-3">Rekening</th>
            <th class="py-3">Daftar</th>
            <th class="text-end px-4 py-3">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php $__empty_1 = true; $__currentLoopData = $affiliates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $affiliate): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
              <td class="px-4 py-3 fw-bold text-dark" style="font-family:monospace">
                <a href="<?php echo e(route('admin.affiliate.show', $affiliate)); ?>"><?php echo e($affiliate->code); ?></a>
              </td>
              <td class="py-3"><?php echo e($affiliate->client?->name); ?><br><span class="small text-muted"><?php echo e($affiliate->client?->email); ?></span></td>
              <td class="py-3">
                <?php $statusBadge = ['pending' => 'badge-soft-warning', 'approved' => 'badge-soft-success', 'rejected' => 'badge-soft-danger', 'suspended' => 'badge-soft-secondary']; ?>
                <span class="badge <?php echo e($statusBadge[$affiliate->status]); ?>"><?php echo e(ucfirst($affiliate->status)); ?></span>
              </td>
              <td class="py-3 small text-muted"><?php echo e($affiliate->bank_account_number ?? '—'); ?></td>
              <td class="py-3 small text-muted"><?php echo e($affiliate->created_at->format('d M Y')); ?></td>
              <td class="text-end px-4 py-3">
                <div class="d-flex align-items-center justify-content-end gap-2">
                  <?php if($affiliate->status === 'pending'): ?>
                    <form method="POST" action="<?php echo e(route('admin.affiliate.approve', $affiliate)); ?>">
                      <?php echo csrf_field(); ?>
                      <button type="submit" class="btn btn-success btn-sm">Setujui</button>
                    </form>
                  <?php endif; ?>
                  <a href="<?php echo e(route('admin.affiliate.show', $affiliate)); ?>" class="btn btn-outline-secondary btn-sm">Detail</a>
                </div>
              </td>
            </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr><td colspan="6" class="text-center text-muted py-5">Belum ada affiliate yang mendaftar.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <?php if($affiliates->hasPages()): ?>
      <div class="px-4 py-3 border-top"><?php echo e($affiliates->links('pagination.bootstrap')); ?></div>
    <?php endif; ?>
  </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/admin/affiliate/index.blade.php ENDPATH**/ ?>