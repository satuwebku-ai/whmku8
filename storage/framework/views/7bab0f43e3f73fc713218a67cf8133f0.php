<?php $__env->startSection('title', 'Konsol Web'); ?>

<?php $__env->startSection('content'); ?>

  <div class="mb-4">
    <h1 class="h4 fw-bold text-dark mb-1">Konsol Web</h1>
    <p class="small text-muted mb-0">
      Jalankan perintah pemeliharaan tanpa perlu Terminal/SSH — berguna kalau hosting-mu tidak menyediakan akses itu.
      Cuma perintah yang aman & sudah ditentukan yang bisa dijalankan dari sini.
    </p>
  </div>

  
  <div class="card border rounded-4 p-4 mb-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
      <div>
        <h2 class="h6 fw-bold text-dark mb-1">Status Setup Aplikasi</h2>
        <p class="small text-muted mb-0">
          <?php echo e($setup['done']); ?> dari <?php echo e($setup['total']); ?> item sudah beres
          <?php if($setup['pending'] > 0): ?>
            — <span class="text-danger fw-medium"><?php echo e($setup['pending']); ?> belum</span>
          <?php else: ?>
            — <span class="text-success fw-medium">semua beres 🎉</span>
          <?php endif; ?>
        </p>
      </div>
      <div style="min-width:10rem">
        <div class="progress" style="height:6px">
          <div class="progress-bar <?php echo e($setup['pending'] > 0 ? '' : 'bg-success'); ?>" style="width:<?php echo e($setup['percent']); ?>%"></div>
        </div>
        <div class="text-muted text-end" style="font-size:11px"><?php echo e($setup['percent']); ?>%</div>
      </div>
    </div>

    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead>
          <tr class="small text-muted">
            <th>Item</th>
            <th style="width:7.5rem">Status</th>
            <th>Keterangan</th>
            <th class="text-end" style="width:11rem">Aksi</th>
          </tr>
        </thead>
        <tbody>
          
          <?php $__currentLoopData = collect($setup['items'])->sortBy('complete')->values(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
              <td>
                <div class="fw-medium text-dark small"><?php echo e($item['title']); ?></div>
                <div class="text-muted" style="font-size:11px"><?php echo e($item['description']); ?></div>
              </td>
              <td>
                <?php if($item['ok']): ?>
                  <span class="badge badge-soft-success">Beres</span>
                <?php elseif($item['skipped']): ?>
                  <span class="badge badge-soft-secondary">Dilewati</span>
                <?php else: ?>
                  <span class="badge badge-soft-danger">Belum</span>
                <?php endif; ?>
              </td>
              <td class="small text-muted"><?php echo e($item['detail'] ?? '—'); ?></td>
              <td class="text-end">
                <div class="d-inline-flex gap-1">
                  <?php if(! $item['ok'] && $item['url']): ?>
                    <a href="<?php echo e($item['url']); ?>" class="btn btn-outline-primary btn-sm">Buka</a>
                  <?php endif; ?>

                  <?php if($item['skippable'] && ! $item['ok']): ?>
                    <form method="POST" action="<?php echo e(route('admin.setup-checklist.skip')); ?>">
                      <?php echo csrf_field(); ?>
                      <input type="hidden" name="key" value="<?php echo e($item['key']); ?>">
                      <input type="hidden" name="stay" value="1">
                      <?php if($item['skipped']): ?>
                        <input type="hidden" name="restore" value="1">
                        <button class="btn btn-outline-secondary btn-sm">Batalkan lewati</button>
                      <?php else: ?>
                        <button class="btn btn-outline-secondary btn-sm">Lewati</button>
                      <?php endif; ?>
                    </form>
                  <?php endif; ?>
                </div>
              </td>
            </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
      </table>
    </div>
  </div>

  <?php if(session('output')): ?>
    <div class="card border rounded-4 p-3 mb-4" style="background:#0f172a;color:#e2e8f0;font-family:monospace;font-size:12px;white-space:pre-wrap;overflow-x:auto"><?php echo e(session('output') ?: '(tidak ada keluaran)'); ?></div>
  <?php endif; ?>

  <div class="card border rounded-4 p-4" style="max-width:36rem">
    <form method="POST" action="<?php echo e(route('admin.console.run')); ?>" id="consoleForm">
      <?php echo csrf_field(); ?>
      <div class="mb-3">
        <label class="form-label small fw-medium text-dark">Pilih Perintah</label>
        <select name="command" id="commandSelect" class="form-select" style="padding:.25rem .6rem;font-size:.875rem;border-radius:.375rem" required>
          <option value="">— Pilih —</option>
          <?php $__currentLoopData = $commands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cmd => $desc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($cmd); ?>" <?php if(old('command') === $cmd): echo 'selected'; endif; ?>><?php echo e($cmd); ?></option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <p id="commandDesc" class="text-muted mt-1 mb-0" style="font-size:11px"></p>
      </div>

      <div id="argumentField" class="d-none mb-3">
        <label class="form-label small fw-medium text-dark">Alamat Email Tujuan</label>
        <input type="email" name="argument" value="<?php echo e(old('argument')); ?>" class="form-control form-control-sm" placeholder="kamu@email.com">
      </div>

      <div id="dryField" class="d-none mb-3">
        <label class="d-flex align-items-center gap-2 small text-dark mb-1">
          <input type="checkbox" name="dry" value="1" checked class="form-check-input" style="margin-top:0">
          Mode aman (simulasi) — tidak benar-benar mengubah apa pun, cuma menunjukkan yang akan terjadi
        </label>
        <p class="text-warning mb-0" style="font-size:11px">
          <i class="fa-solid fa-triangle-exclamation"></i>
          Hilangkan centang untuk benar-benar menjalankan (perpanjangan/suspend/pengingat sungguhan).
        </p>
      </div>

      <button type="submit" class="btn btn-primary btn-sm">
        <i class="fa-solid fa-terminal" style="font-size:11px"></i> Jalankan
      </button>
    </form>
  </div>

  <script <?php echo 'nonce="' . e(app(\App\Support\CspNonce::class)->value()) . '"'; ?>>
    const descriptions = <?php echo json_encode($commands, 15, 512) ?>;
    const dryRunCommands = <?php echo json_encode($dryRunCommands, 15, 512) ?>;

    const select = document.getElementById('commandSelect');
    const descEl = document.getElementById('commandDesc');
    const argField = document.getElementById('argumentField');
    const dryField = document.getElementById('dryField');

    function sync() {
      const cmd = select.value;
      descEl.textContent = descriptions[cmd] || '';
      argField.classList.toggle('d-none', cmd !== 'lumora:test-mail');
      dryField.classList.toggle('d-none', !dryRunCommands.includes(cmd));
    }

    select.addEventListener('change', sync);
    sync();
  </script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/admin/console/index.blade.php ENDPATH**/ ?>