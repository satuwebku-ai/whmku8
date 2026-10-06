<?php $__env->startSection('title', 'Edit Template — ' . $meta['label']); ?>

<?php $__env->startSection('content'); ?>

  <a href="<?php echo e(route('admin.notification-templates.index')); ?>" class="text-decoration-none text-muted" style="font-size:12px">
    <i class="fa-solid fa-arrow-left"></i> Kembali ke Template Notifikasi
  </a>

  <div class="d-flex align-items-center justify-content-between mt-2 mb-4 flex-wrap gap-3">
    <div>
      <div class="d-flex align-items-center gap-2">
        <span class="rounded-3 d-flex align-items-center justify-content-center" style="width:34px;height:34px;background:#eef2ff;color:#4f46e5"><i class="fa-solid fa-pen-nib" style="font-size:13px"></i></span>
        <h1 class="h4 fw-bold text-dark mb-0"><?php echo e($meta['label']); ?></h1>
      </div>
      <?php if(! empty($meta['note'])): ?>
        <p class="mt-2 mb-0 rounded-3 px-3 py-2" style="font-size:13px;color:#92400e;background:#fffbeb;border:1px solid #fde68a;max-width:36rem">
          <i class="fa-solid fa-circle-info"></i> <?php echo e($meta['note']); ?>

        </p>
      <?php endif; ?>
    </div>
    <div class="d-flex align-items-center gap-2">
      <?php if($isCustomized): ?>
        <form method="POST" action="<?php echo e(route('admin.notification-templates.reset', $key)); ?>"
              data-confirm="Kembalikan ke kata-kata bawaan? Perubahan yang sudah kamu buat akan hilang." data-confirm-title="Reset Template" data-confirm-style="danger" data-confirm-label="Ya, Reset">
          <?php echo csrf_field(); ?>
          <button type="submit" class="btn btn-outline-danger btn-sm">
            <i class="fa-solid fa-rotate-left" style="font-size:11px"></i> Reset ke Bawaan
          </button>
        </form>
      <?php endif; ?>
      <form method="POST" action="<?php echo e(route('admin.notification-templates.preview.draft', $key)); ?>" target="_blank" id="previewForm">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="subject" id="previewSubject">
        <input type="hidden" name="body_mail" id="previewBodyMail">
        <input type="hidden" name="body_whatsapp" id="previewBodyWhatsapp">
        <input type="hidden" name="body_sms" id="previewBodySms">
        <button type="submit" class="btn btn-outline-secondary btn-sm">
          <i class="fa-solid fa-eye" style="font-size:11px"></i> Lihat Pratinjau
        </button>
      </form>
    </div>
  </div>

  <div class="row g-4">
    <div class="col-12 col-lg-8">
      <form method="POST" action="<?php echo e(route('admin.notification-templates.update', $key)); ?>" class="d-flex flex-column gap-3" id="editForm">
        <?php echo csrf_field(); ?>

        <?php if(! is_null($meta['subject'])): ?>
          <div class="card border rounded-4 p-4 template-editor-card">
            <label class="form-label small fw-medium text-dark">Subjek Email</label>
            <input type="text" name="subject" value="<?php echo e(old('subject', $effective['subject'])); ?>" class="form-control form-control-sm">
            <?php $__errorArgs = ['subject'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>
        <?php endif; ?>

        <div class="card border rounded-4 p-4 template-editor-card">
          <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
            <label class="form-label small fw-medium text-dark mb-0">Isi Email</label>
            <span class="badge badge-soft-primary"><i class="fa-regular fa-envelope me-1"></i> HTML email</span>
          </div>
          <textarea name="body_mail" rows="12" class="form-control form-control-sm template-textarea" style="font-family:monospace;font-size:12px;line-height:1.6"><?php echo e(old('body_mail', $effective['body_mail'])); ?></textarea>
          <?php $__errorArgs = ['body_mail'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          <p class="text-muted mt-2 mb-0" style="font-size:11px">
            Satu baris = satu paragraf. Baris kosong dilewati (tidak jadi paragraf kosong).
            Untuk tombol aksi, tulis di baris tersendiri: <code class="bg-light px-1 rounded">[ACTION:Teks Tombol:{invoice_url}]</code>
          </p>
        </div>

        <?php if(! is_null($meta['body_whatsapp'])): ?>
          <div class="card border rounded-4 p-4 template-editor-card">
            <label class="form-label small fw-medium text-dark">Isi Pesan WhatsApp</label>
            <textarea name="body_whatsapp" rows="6" class="form-control form-control-sm" style="font-family:monospace;font-size:12px;line-height:1.6"><?php echo e(old('body_whatsapp', $effective['body_whatsapp'])); ?></textarea>
            <?php $__errorArgs = ['body_whatsapp'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            <p class="text-muted mt-2 mb-0" style="font-size:11px">
              Cuma dikirim kalau WhatsApp aktif di Pengaturan → Notifikasi. Pakai *bintang* untuk teks tebal (format asli WhatsApp).
            </p>
          </div>
        <?php endif; ?>

        <?php if(array_key_exists('body_sms', $meta) && ! is_null($meta['body_sms'])): ?>
          <div class="card border rounded-4 p-4 template-editor-card">
            <label class="form-label small fw-medium text-dark">Isi Pesan SMS</label>
            <textarea name="body_sms" rows="3" class="form-control form-control-sm" style="font-family:monospace;font-size:12px;line-height:1.6" maxlength="1000"><?php echo e(old('body_sms', $effective['body_sms'])); ?></textarea>
            <?php $__errorArgs = ['body_sms'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            <p class="text-muted mt-2 mb-0" style="font-size:11px">
              Cuma dikirim kalau gateway SMS aktif di Pengaturan → Notifikasi. Tulis singkat & polos (tanpa *bintang*/format) —
              SMS dikenakan biaya per segmen 160 karakter, tiap segmen tambahan menambah biaya kirim.
            </p>
          </div>
        <?php endif; ?>

        <button type="submit" class="btn btn-primary" style="width:fit-content">
          <i class="fa-solid fa-check" style="font-size:11px"></i> Simpan Template
        </button>
      </form>
    </div>

    <div class="col-12 col-lg-4">
      <div class="card border rounded-4 p-4 template-editor-card">
        <h2 class="small fw-bold text-dark mb-2">Variabel Tersedia</h2>
        <p class="text-muted mb-3" style="font-size:12px">Klik untuk menyalin, lalu tempel di isi email/WhatsApp.</p>
        <div class="d-flex flex-wrap gap-2">
          <?php $__currentLoopData = $meta['variables']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <button type="button" data-copy-var="<?php echo e($v); ?>"
                    class="copy-var-btn btn btn-outline-secondary" style="font-size:11px;font-family:monospace;padding:.25rem .5rem">
              <?php echo e('{' . $v . '}'); ?>

            </button>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
      </div>
    </div>
  </div>

  <script <?php echo 'nonce="' . e(app(\App\Support\CspNonce::class)->value()) . '"'; ?>>
    document.querySelectorAll('.copy-var-btn').forEach(function (btn) {
      btn.addEventListener('click', function () {
        const text = '{' + btn.dataset.copyVar + '}';
        navigator.clipboard.writeText(text);

        const original = btn.textContent;
        btn.textContent = 'Disalin!';
        setTimeout(() => { btn.textContent = original; }, 1000);
      });
    });

    document.getElementById('previewForm').addEventListener('submit', function () {
      const mainForm = document.getElementById('editForm');
      document.getElementById('previewSubject').value = mainForm.querySelector('[name="subject"]')?.value || '';
      document.getElementById('previewBodyMail').value = mainForm.querySelector('[name="body_mail"]')?.value || '';
      document.getElementById('previewBodyWhatsapp').value = mainForm.querySelector('[name="body_whatsapp"]')?.value || '';
      document.getElementById('previewBodySms').value = mainForm.querySelector('[name="body_sms"]')?.value || '';
    });
  </script>
  <style>
    .template-editor-card{ box-shadow:0 8px 24px rgba(15,23,42,.04); }
    .template-textarea{ border-color:#c7d2fe; background:#fbfdff; }
    .template-textarea:focus{ background:#fff; box-shadow:0 0 0 3px rgba(99,102,241,.12); }
    .copy-var-btn{ transition:transform .15s ease,background-color .15s ease; }
    .copy-var-btn:hover{ transform:translateY(-1px); background:#eef2ff; }
  </style>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/admin/notification-templates/form.blade.php ENDPATH**/ ?>