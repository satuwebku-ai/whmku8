<?php $__env->startSection('title', 'Berkas Persyaratan'); ?>
<?php $__env->startSection('content'); ?>

  <div class="mb-4">
    <a href="<?php echo e(route('client.domains')); ?>" class="text-decoration-none text-muted" style="font-size:12px">
      <i class="fa-solid fa-arrow-left"></i> Kembali ke Domain
    </a>
    <h1 class="h4 fw-bold text-dark mt-1 mb-1">Berkas Persyaratan — <?php echo e($domain->domain_name); ?></h1>
    <p class="small text-muted mb-0">
      Domain <b>.<?php echo e($tldExt); ?></b> mewajibkan berkas berikut sebelum bisa didaftarkan.
      Unggah satu per satu, lalu tunggu tim kami memverifikasi.
    </p>
  </div>

  <?php if($requirements->isEmpty()): ?>
    <div class="card border rounded-4 p-5 text-center">
      <i class="fa-solid fa-circle-check text-success mb-3" style="font-size:1.75rem"></i>
      <p class="fw-medium text-dark mb-1">Domain ini tidak butuh berkas apa pun</p>
      <p class="text-muted mb-0" style="font-size:13px">Pendaftarannya bisa langsung diproses.</p>
    </div>
  <?php else: ?>
    
    <div class="card border rounded-4 p-4 mb-3"
         style="<?php echo e($progress['complete'] ? 'border-color:#a7f3d0!important;background:rgba(16,185,129,.04)' : ($progress['rejected'] > 0 ? 'border-color:#fecaca!important;background:rgba(239,68,68,.04)' : '')); ?>">
      <?php if($progress['complete']): ?>
        <p class="fw-bold text-dark mb-1" style="font-size:14px">
          <i class="fa-solid fa-circle-check text-success"></i> Semua berkas sudah disetujui
        </p>
        <p class="text-muted mb-2" style="font-size:12px">
          Domain akan didaftarkan setelah pembayaran diterima.
        </p>

        <?php if($invoice): ?>
          <a href="<?php echo e(route('client.invoices.show', $invoice)); ?>" class="btn btn-theme btn-sm">
            <i class="fa-solid fa-credit-card" style="font-size:11px"></i>
            Lanjut Bayar — <?php echo e($invoice->invoice_number); ?> (Rp <?php echo e(number_format($invoice->total, 0, ',', '.')); ?>)
          </a>
        <?php else: ?>
          
          <a href="<?php echo e(route('client.invoices')); ?>" class="btn btn-outline-secondary btn-sm">
            Lihat Invoice Saya
          </a>
        <?php endif; ?>
      <?php elseif($progress['rejected'] > 0): ?>
        <p class="fw-bold mb-1" style="font-size:14px;color:#991b1b">
          <i class="fa-solid fa-triangle-exclamation"></i> Ada berkas yang perlu diunggah ulang
        </p>
        <p class="text-muted mb-0" style="font-size:12px">
          <?php echo e($progress['rejected']); ?> berkas ditolak. Baca alasannya di bawah, perbaiki, lalu unggah ulang.
        </p>
      <?php else: ?>
        <p class="fw-bold text-dark mb-1" style="font-size:14px">
          Menunggu kelengkapan berkas — <?php echo e($progress['approved']); ?>/<?php echo e($progress['required']); ?> berkas wajib disetujui
        </p>
        <p class="text-muted mb-0" style="font-size:12px">
          
          <?php if($progress['blocking_missing'] > 0): ?>
            <?php echo e($progress['blocking_missing']); ?> berkas wajib belum diunggah.
          <?php endif; ?>
          <?php
            // Syarat opsional yang belum disentuh sama sekali -- TIDAK
            // menghalangi pembayaran (memang boleh dilewati), tapi
            // tetap disebut di sini supaya kalimatnya konsisten dengan
            // tabel di bawah yang menampilkan SEMUA syarat (termasuk
            // opsional), bukan cuma yang wajib.
            $opsionalBelum = $progress['items']->filter(fn ($i) => ! $i['requirement']->is_required && $i['status'] === 'missing')->count();
          ?>
          <?php if($opsionalBelum > 0): ?>
            <?php echo e($opsionalBelum); ?> berkas opsional belum diunggah (boleh dilewati).
          <?php endif; ?>
          <?php if($progress['blocking_pending'] > 0): ?>
            <?php echo e($progress['blocking_pending']); ?> berkas sedang ditinjau tim kami<?php echo e($progress['pending'] < $progress['blocking_pending'] ? ' (termasuk berkas opsional yang sudah diunggah)' : ''); ?>.
          <?php endif; ?>
          <?php if($progress['blocking_rejected'] > 0): ?>
            <?php echo e($progress['blocking_rejected']); ?> berkas ditolak dan perlu diunggah ulang.
          <?php endif; ?>
          Pembayaran baru bisa dilanjutkan setelah semuanya disetujui.
        </p>
      <?php endif; ?>
    </div>

    <div class="d-flex flex-column gap-3">
      <?php $__currentLoopData = $progress['items']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
          $req = $item['requirement'];
          $doc = $item['document'];
          $st = $item['status'];
        ?>

        <div class="card border rounded-4 p-4"
             style="<?php echo e($st === 'rejected' ? 'border-color:#fecaca!important' : ($st === 'approved' ? 'border-color:#a7f3d0!important' : '')); ?>">
          <div class="d-flex align-items-start justify-content-between gap-3 flex-wrap mb-2">
            <div class="min-w-0">
              <p class="fw-bold text-dark mb-1" style="font-size:14px">
                <?php echo e($req->name); ?>

                <?php if (! ($req->is_required)): ?>
                  <span class="badge badge-soft-secondary" style="font-size:9px">opsional</span>
                <?php endif; ?>
              </p>
              <?php if($req->description): ?>
                <p class="text-muted mb-0" style="font-size:12px"><?php echo e($req->description); ?></p>
              <?php endif; ?>
            </div>

            <?php
              $badge = match ($st) {
                'approved' => ['Disetujui', '#d1fae5', '#047857'],
                'rejected' => ['Ditolak', '#fee2e2', '#991b1b'],
                'pending'  => ['Sedang ditinjau', '#fef3c7', '#b45309'],
                default    => ['Belum diunggah', '#f1f5f9', '#64748b'],
              };
            ?>
            <span class="badge flex-shrink-0" style="font-size:10px;background:<?php echo e($badge[1]); ?>;color:<?php echo e($badge[2]); ?>"><?php echo e($badge[0]); ?></span>
          </div>

          <?php if($doc): ?>
            <div class="d-flex align-items-center gap-2 rounded-3 px-3 py-2 mb-2" style="background:#f8fafc">
              <i class="fa-regular fa-file text-muted" style="font-size:12px"></i>
              <a href="<?php echo e(route('client.domains.documents.file', $doc)); ?>" target="_blank"
                 class="text-decoration-none text-dark text-truncate" style="font-size:13px"><?php echo e($doc->original_name); ?></a>
              <span class="text-muted ms-auto flex-shrink-0" style="font-size:11px"><?php echo e($doc->created_at->format('d M Y')); ?></span>
            </div>
          <?php endif; ?>

          <?php if($st === 'rejected' && $doc?->admin_note): ?>
            <div class="rounded-3 px-3 py-2 mb-2" style="background:#fef2f2;border:1px solid #fecaca">
              <p class="mb-0" style="font-size:12px;color:#991b1b">
                <b>Alasan ditolak:</b> <?php echo e($doc->admin_note); ?>

              </p>
            </div>
          <?php endif; ?>

          <?php if($st !== 'approved'): ?>
            <form method="POST" action="<?php echo e(route('client.domains.documents.upload', $domain)); ?>"
                  enctype="multipart/form-data" class="d-flex gap-2 flex-wrap align-items-start">
              <?php echo csrf_field(); ?>
              <input type="hidden" name="document_requirement_id" value="<?php echo e($req->id); ?>">
              <input type="file" name="file" required accept=".zip,.rar,.pdf,.jpg,.jpeg,.png"
                     class="form-control form-control-sm" style="max-width:20rem">
              <button type="submit" class="btn btn-theme btn-sm">
                <i class="fa-solid fa-upload" style="font-size:11px"></i>
                <?php echo e($doc ? 'Unggah Ulang' : 'Unggah'); ?>

              </button>
            </form>
            <p class="text-muted mt-2 mb-0" style="font-size:11px">
              Format: ZIP, RAR, PDF, JPG, PNG &middot; maksimal 2 MB.
            </p>
          <?php endif; ?>
        </div>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
  <?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('client.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/themes/client-themes/modern/client/domains/documents.blade.php ENDPATH**/ ?>