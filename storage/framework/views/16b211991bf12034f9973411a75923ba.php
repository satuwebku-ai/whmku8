<?php $__env->startSection('title', 'Verifikasi Berkas Domain'); ?>
<?php $__env->startSection('content'); ?>

  <div class="mb-4">
    <h1 class="h4 fw-bold text-dark mb-1">Verifikasi Berkas Domain</h1>
    <p class="small text-muted mb-0">
      Domain yang ekstensinya butuh berkas persyaratan. Klien baru bisa lanjut membayar
      setelah semua berkas wajib disetujui.
    </p>
  </div>

  <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
    <?php $__currentLoopData = ['waiting' => 'Menunggu Verifikasi', 'rejected' => 'Ada yang Ditolak', 'complete' => 'Sudah Lengkap', 'all' => 'Semua']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <a href="<?php echo e(route('admin.domain-documents.index', ['status' => $key, 'search' => request('search')])); ?>"
         class="px-3 py-2 rounded-3 text-decoration-none <?php echo e($status === $key ? 'text-white' : 'text-muted border'); ?>"
         style="font-size:12px;<?php echo e($status === $key ? 'background:#4f46e5' : ''); ?>">
        <?php echo e($label); ?>

      </a>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <form method="GET" class="d-flex gap-2 ms-auto">
      <input type="hidden" name="status" value="<?php echo e($status); ?>">
      <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari domain / klien..." class="form-control form-control-sm" style="width:14rem">
      <button type="submit" class="btn btn-outline-secondary btn-sm">Cari</button>
    </form>
  </div>

  <?php $__empty_1 = true; $__currentLoopData = $domains; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $domain): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <?php $p = $progress[$domain->id]; ?>

    <div class="card border rounded-4 overflow-hidden mb-3">
      <div class="px-4 py-3 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2"
           style="background:<?php echo e($p['complete'] ? 'rgba(16,185,129,.04)' : ($p['rejected'] > 0 ? 'rgba(239,68,68,.04)' : '#f8fafc')); ?>">
        <div class="min-w-0">
          <p class="fw-bold text-dark mb-0" style="font-size:14px">
            <?php echo e($domain->domain_name); ?>

            <span class="badge <?php echo e($p['complete'] ? 'badge-soft-success' : 'badge-soft-secondary'); ?>" style="font-size:9px">
              <?php echo e($p['approved']); ?>/<?php echo e($p['required']); ?> wajib disetujui
              <?php $opsional = $p['items']->count() - $p['required']; ?>
              <?php if($opsional > 0): ?>
                <span class="text-muted">(+<?php echo e($opsional); ?> opsional)</span>
              <?php endif; ?>
            </span>
          </p>
          <p class="text-muted mb-0" style="font-size:11px">
            <?php echo e($domain->client->name ?? '—'); ?> &middot; <?php echo e($domain->client->email ?? '—'); ?>

            &middot; ID Klien #<?php echo e($domain->client_id); ?>

          </p>
        </div>

        <div class="d-flex align-items-center gap-2">
          
          <?php if($p['complete']): ?>
            <span class="badge badge-soft-success" style="font-size:10px">
              <i class="fa-solid fa-check"></i> Lengkap — klien bisa lanjut bayar
            </span>
          <?php else: ?>
            <span class="badge badge-soft-secondary" style="font-size:10px">
              Menunggu <?php echo e($p['items']->where('status', '!=', 'approved')->count()); ?> berkas
            </span>
          <?php endif; ?>
          <a href="<?php echo e(route('admin.domains.details', $domain)); ?>" class="btn btn-outline-secondary btn-sm">Detail Domain</a>
        </div>
      </div>

      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" style="font-size:13px">
          <thead>
            <tr class="small text-uppercase text-muted" style="background:#f8fafc">
              <th class="px-4 py-2">Persyaratan</th>
              <th class="py-2">Berkas</th>
              <th class="text-center py-2">Status</th>
              <th class="text-end px-4 py-2">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php $__currentLoopData = $p['items']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <?php $doc = $item['document']; ?>
              <tr>
                <td class="px-4 py-2">
                  <span class="fw-medium text-dark"><?php echo e($item['requirement']->name); ?></span>
                  <?php if (! ($item['requirement']->is_required)): ?>
                    <span class="badge badge-soft-secondary" style="font-size:9px">opsional</span>
                  <?php endif; ?>
                </td>
                <td class="py-2">
                  <?php if($doc): ?>
                    <a href="<?php echo e(route('admin.domain-documents.file', $doc)); ?>" target="_blank" class="text-accent text-decoration-none">
                      <i class="fa-regular fa-file" style="font-size:11px"></i> <?php echo e(Str::limit($doc->original_name, 32)); ?>

                    </a>
                    <?php if($doc->admin_note): ?>
                      <span class="d-block text-muted" style="font-size:10px">Catatan: <?php echo e($doc->admin_note); ?></span>
                    <?php endif; ?>
                  <?php else: ?>
                    <span class="text-muted" style="font-size:12px">Belum diunggah</span>
                  <?php endif; ?>
                </td>
                <td class="text-center py-2">
                  <?php
                    $badge = match ($item['status']) {
                      'approved' => ['Disetujui', '#d1fae5', '#047857'],
                      'rejected' => ['Ditolak', '#fee2e2', '#991b1b'],
                      'pending'  => ['Menunggu', '#fef3c7', '#b45309'],
                      default    => ['Belum ada', '#f1f5f9', '#64748b'],
                    };
                  ?>
                  <span class="badge" style="font-size:10px;background:<?php echo e($badge[1]); ?>;color:<?php echo e($badge[2]); ?>"><?php echo e($badge[0]); ?></span>
                </td>
                <td class="text-end px-4 py-2">
                  <?php if($doc && $item['status'] !== 'approved'): ?>
                    <div class="d-flex align-items-center justify-content-end gap-2">
                      <form method="POST" action="<?php echo e(route('admin.domain-documents.review', $doc)); ?>">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="status" value="approved">
                        <button type="submit" class="btn btn-outline-success btn-sm" style="font-size:11px">
                          <i class="fa-solid fa-check"></i> Verifikasi
                        </button>
                      </form>
                      <button type="button" class="btn btn-outline-danger btn-sm" style="font-size:11px"
                              data-action="call" data-call="tolakBerkas" data-args="<?php echo e(json_encode([$doc->id])); ?>">
                        <i class="fa-solid fa-xmark"></i> Tolak
                      </button>
                    </div>

                    <form method="POST" action="<?php echo e(route('admin.domain-documents.review', $doc)); ?>"
                          id="tolak-<?php echo e($doc->id); ?>" class="d-none mt-2">
                      <?php echo csrf_field(); ?>
                      <input type="hidden" name="status" value="rejected">
                      <div class="d-flex gap-2 justify-content-end">
                        <input type="text" name="admin_note" required maxlength="500"
                               placeholder="Alasan ditolak (dibaca klien)" class="form-control form-control-sm" style="max-width:18rem">
                        <button type="submit" class="btn btn-danger btn-sm" style="font-size:11px">Kirim</button>
                      </div>
                    </form>
                  <?php elseif($item['status'] === 'approved'): ?>
                    <span class="text-muted" style="font-size:11px">—</span>
                  <?php else: ?>
                    <span class="text-muted" style="font-size:11px">Menunggu klien</span>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </tbody>
        </table>
      </div>
    </div>
  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <div class="card border rounded-4 p-5 text-center">
      <i class="fa-solid fa-inbox text-muted mb-3" style="font-size:1.75rem"></i>
      <p class="text-muted mb-0" style="font-size:14px">Tidak ada domain pada filter ini.</p>
    </div>
  <?php endif; ?>

  <?php if($domains->hasPages()): ?>
    <div class="mt-3"><?php echo e($domains->links('pagination.bootstrap')); ?></div>
  <?php endif; ?>

  <script <?php echo 'nonce="' . e(app(\App\Support\CspNonce::class)->value()) . '"'; ?>>
    // Form alasan penolakan disembunyikan sampai dibutuhkan -- alasan
    // WAJIB diisi karena teks inilah yang dibaca klien untuk tahu apa
    // yang harus diperbaiki saat mengunggah ulang.
    function tolakBerkas(id) {
      const form = document.getElementById('tolak-' + id);
      form.classList.toggle('d-none');
      form.querySelector('input[name="admin_note"]').focus();
    }
    (window.LumoraActions = window.LumoraActions || {}).tolakBerkas = tolakBerkas;
  </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/admin/domain-documents/index.blade.php ENDPATH**/ ?>