<?php $__env->startSection('title', 'Otomatisasi Email'); ?>

<?php $__env->startSection('content'); ?>

  <div class="ix-app">
    <?php echo $__env->make('admin.mail._sidebar', ['folder' => 'settings'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <section class="ix-main">
      <div class="ix-head">
        <h1><i class="fa-solid fa-robot" style="color:#4f46e5"></i> Otomatisasi &amp; Template</h1>
      </div>

      <form method="POST" action="<?php echo e(route('admin.mail.settings.update')); ?>" class="ix-form" style="border-bottom:1px solid #e2e8f0">
        <?php echo csrf_field(); ?>

        <h2 style="font-size:14px;font-weight:700;color:#0f172a">Balasan otomatis (robot)</h2>
        <p style="font-size:12px;color:#64748b">Dikirim sekali saat email pertama dari pelanggan masuk, sebagai tanda terima. Robot tidak membalas lagi sampai admin membalas sendiri, dan tidak membalas email otomatis/bounce.</p>

        <label class="d-flex align-items-center gap-2 mb-3" style="font-size:13px">
          <input type="checkbox" class="ix-chk" name="mail_autoreply_enabled" value="1" <?php if($v['mail_autoreply_enabled'] === '1'): echo 'checked'; endif; ?>> Aktifkan balasan otomatis
        </label>
        <textarea name="mail_autoreply_body" rows="8" class="form-control mb-1" style="font-size:13px" required><?php echo e(old('mail_autoreply_body', $v['mail_autoreply_body'])); ?></textarea>
        <?php $__errorArgs = ['mail_autoreply_body'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="ix-err"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        <p style="font-size:11px;color:#94a3b8">Penanda: <code>{nama}</code> <code>{site}</code> <code>{ref}</code> (nomor referensi) <code>{jam_kerja}</code></p>

        <h2 style="font-size:14px;font-weight:700;color:#0f172a;margin-top:1.5rem">Tutup otomatis jika tidak ada balasan</h2>
        <p style="font-size:12px;color:#64748b">Hanya thread yang pesan terakhirnya balasan admin yang ditutup. Thread yang menunggu balasan admin tidak pernah ditutup otomatis. Kalau pelanggan membalas lagi, thread terbuka kembali sendiri.</p>

        <label class="d-flex align-items-center gap-2 mb-2" style="font-size:13px">
          <input type="checkbox" class="ix-chk" name="mail_autoclose_enabled" value="1" <?php if($v['mail_autoclose_enabled'] === '1'): echo 'checked'; endif; ?>> Aktifkan tutup otomatis
        </label>
        <div class="d-flex align-items-center gap-2 mb-2" style="font-size:13px">
          Tutup setelah
          <input type="number" name="mail_autoclose_hours" min="1" max="720" value="<?php echo e(old('mail_autoclose_hours', $v['mail_autoclose_hours'])); ?>" class="form-control form-control-sm" style="width:90px">
          jam tanpa balasan pelanggan
        </div>
        <?php $__errorArgs = ['mail_autoclose_hours'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="ix-err"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        <label class="d-flex align-items-center gap-2 mb-2" style="font-size:13px">
          <input type="checkbox" class="ix-chk" name="mail_idle_prompt_enabled" value="1" <?php if($v['mail_idle_prompt_enabled'] === '1'): echo 'checked'; endif; ?>> Tanya dulu "masih perlu bantuan?" sebelum menutup
        </label>
        <div class="d-flex align-items-center gap-2 mb-2" style="font-size:13px">
          Kirim pertanyaan
          <input type="number" name="mail_idle_grace_hours" min="1" max="336" value="<?php echo e(old('mail_idle_grace_hours', $v['mail_idle_grace_hours'])); ?>" class="form-control form-control-sm" style="width:90px">
          jam sebelum batas tutup
        </div>
        <?php $__errorArgs = ['mail_idle_grace_hours'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="ix-err"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        <textarea name="mail_idle_prompt_body" rows="7" class="form-control mb-1" style="font-size:13px"><?php echo e(old('mail_idle_prompt_body', $v['mail_idle_prompt_body'])); ?></textarea>
        <p style="font-size:11px;color:#94a3b8">Penanda: <code>{nama}</code> <code>{site}</code> <code>{ref}</code> <code>{jam_sisa}</code>. Kalau pelanggan membalas, penutupan dibatalkan.</p>

        <label class="d-flex align-items-center gap-2 mb-2" style="font-size:13px">
          <input type="checkbox" class="ix-chk" name="mail_autoclose_notice" value="1" <?php if($v['mail_autoclose_notice'] === '1'): echo 'checked'; endif; ?>> Kirim email pemberitahuan ke pelanggan saat ditutup
        </label>
        <textarea name="mail_autoclose_body" rows="7" class="form-control mb-1" style="font-size:13px" required><?php echo e(old('mail_autoclose_body', $v['mail_autoclose_body'])); ?></textarea>
        <?php $__errorArgs = ['mail_autoclose_body'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="ix-err"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        <p style="font-size:11px;color:#94a3b8">Penanda: <code>{nama}</code> <code>{site}</code> <code>{jam}</code>. Dijalankan oleh cron <b>Tutup Email Tanpa Balasan</b> (Pengaturan → Cron Jobs).</p>

        <button type="submit" class="ix-btn pri mt-2">Simpan</button>
      </form>

      <div class="ix-form">
        <h2 style="font-size:14px;font-weight:700;color:#0f172a">Template balasan (teks support)</h2>
        <p style="font-size:12px;color:#64748b">Template sekarang punya menu sendiri: tambah, ubah, kategori, aktif/nonaktif, dan sambungan ke AI chat.</p>
        <a href="<?php echo e(route('admin.templates.index')); ?>" class="ix-btn pri" style="text-decoration:none;display:inline-block"><i class="fa-solid fa-bolt"></i> Buka Template Balasan (<?php echo e($templates->count()); ?>)</a>
      </div>
    </section>
  </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/admin/mail/settings.blade.php ENDPATH**/ ?>