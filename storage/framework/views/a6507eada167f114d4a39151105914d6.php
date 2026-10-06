<?php $__env->startSection('title', 'Detail Domain — ' . $domain->domain_name); ?>

<?php $__env->startSection('content'); ?>

  <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <div>
      <a href="<?php echo e(route('admin.domains')); ?>" class="text-decoration-none text-muted" style="font-size:12px"><i class="fa-solid fa-arrow-left"></i> Kembali ke Domain</a>
      <h1 class="h4 fw-bold text-dark mt-1 mb-0"><?php echo e($domain->domain_name); ?>

        <?php if($domain->is_premium): ?>
          <span class="badge ms-1 align-middle" style="background:#fef3c7;color:#92400e;font-weight:600;font-size:11px">Premium</span>
        <?php endif; ?>
      </h1>
    </div>
    <?php
      $badgeMap = ['active' => 'badge-soft-success', 'pending' => 'badge-soft-warning', 'suspended' => 'badge-soft-danger', 'cancelled' => 'badge-soft-secondary'];
      $displayStatus = $domain->status === 'expired' ? 'suspended' : $domain->status;
    ?>
    <span class="badge <?php echo e($badgeMap[$displayStatus] ?? 'badge-soft-secondary'); ?>" style="font-size:13px;padding:.4rem .8rem"><?php echo e(ucfirst($domain->status)); ?></span>
  </div>

  
  <?php if($domain->is_premium && $domain->provision_status !== 'registered'): ?>
    <?php
      $premiumPaid = $domain->order_id
        && \App\Models\Invoice::where('status', 'paid')->whereHas('items', fn ($q) => $q->where('order_id', $domain->order_id))->exists();
    ?>
    <div class="card border rounded-4 p-4 mb-3 <?php echo e($premiumPaid ? 'border-warning bg-warning bg-opacity-10' : ''); ?>">
      <h2 class="small fw-bold text-dark mb-1"><i class="fa-solid fa-crown text-warning"></i> Registrasi Domain Premium (Manual)</h2>
      <?php if($premiumPaid): ?>
        <p class="small text-muted mb-3">Invoice sudah lunas. API DNAMA <strong>tidak bisa</strong> mendaftarkan domain premium — daftarkan <strong><?php echo e($domain->domain_name); ?></strong> lewat panel DNAMA (order premium), lalu sinkronkan di sini. Setelah itu domain menjadi Aktif dan klien bisa mengelola DNS-nya.</p>
        <?php if($domain->registrar && $domain->registrar->provider === 'dnama'): ?>
          <form method="POST" action="<?php echo e(route('admin.domains.sync-premium', $domain)); ?>" class="mb-3 pb-3 border-bottom">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-cloud-arrow-down" style="font-size:11px"></i> Cek &amp; Sinkron dari DNAMA</button>
            <span class="text-muted ms-2" style="font-size:11px">Memeriksa lewat API apakah domain sudah ACTIVE di akun DNAMA; tanggal &amp; nameserver diambil otomatis.</span>
          </form>
          <p class="small fw-medium text-dark mb-2">Atau isi manual (cadangan):</p>
        <?php endif; ?>
        <form method="POST" action="<?php echo e(route('admin.domains.complete-manual', $domain)); ?>" data-confirm="Tandai <?php echo e($domain->domain_name); ?> sudah terdaftar di registrar? Status domain akan menjadi Aktif." data-confirm-title="Selesaikan Registrasi" data-confirm-style="info" data-confirm-label="Ya, Selesai">
          <?php echo csrf_field(); ?>
          <div class="row g-2 mb-2">
            <div class="col-sm-4">
              <label class="form-label small fw-medium text-dark mb-1">Tanggal Registrasi</label>
              <input type="date" name="register_date" value="<?php echo e(old('register_date', now()->format('Y-m-d'))); ?>" class="form-control form-control-sm">
            </div>
            <div class="col-sm-4">
              <label class="form-label small fw-medium text-dark mb-1">Jatuh Tempo</label>
              <input type="date" name="expiry_date" value="<?php echo e(old('expiry_date', now()->addYears(max($domain->years ?: 1, 1))->format('Y-m-d'))); ?>" class="form-control form-control-sm">
            </div>
            <div class="col-sm-4">
              <label class="form-label small fw-medium text-dark mb-1">Catatan (opsional)</label>
              <input type="text" name="admin_note" maxlength="500" value="<?php echo e(old('admin_note')); ?>" class="form-control form-control-sm" placeholder="mis. didaftarkan via panel DNAMA">
            </div>
          </div>
          <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-check" style="font-size:11px"></i> Tandai Sudah Terdaftar</button>
        </form>
      <?php else: ?>
        <p class="small text-muted mb-0">Menunggu pembayaran. Setelah invoice disetujui lunas, form penyelesaian registrasi akan muncul di sini.</p>
      <?php endif; ?>
    </div>
  <?php endif; ?>

  
  <?php if($domain->provision_status === 'needs_documents' || $domain->documents->isNotEmpty()): ?>
    <div class="card border rounded-4 p-4 mb-3 <?php echo e($domain->provision_status === 'needs_documents' ? 'border-warning bg-warning bg-opacity-10' : ''); ?>">
      <div class="d-flex align-items-center justify-content-between mb-2 flex-wrap gap-2">
        <h2 class="small fw-bold mb-0 <?php echo e($domain->provision_status === 'needs_documents' ? 'text-warning' : 'text-dark'); ?>">
          <i class="fa-solid fa-file-lines"></i> Dokumen Persyaratan Domain
        </h2>
        <?php if($domain->provision_status === 'needs_documents'): ?>
          <form method="POST" action="<?php echo e(route('admin.domains.verify-documents', $domain)); ?>"
                data-confirm="Tandai dokumen untuk &quot;<?php echo e($domain->domain_name); ?>&quot; sudah lengkap dan lanjutkan pendaftaran?"
                data-confirm-title="Dokumen Lengkap?" data-confirm-style="info" data-confirm-label="Ya, Lanjutkan">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-check" style="font-size:11px"></i> Dokumen Lengkap, Lanjutkan</button>
          </form>
        <?php endif; ?>
      </div>

      <?php $__empty_1 = true; $__currentLoopData = $domain->documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="d-flex align-items-center justify-content-between py-2 border-bottom flex-wrap gap-2">
          <a href="<?php echo e(route('admin.domain-documents.file', $doc)); ?>" target="_blank" class="small text-dark text-decoration-none d-flex align-items-center gap-2 min-w-0">
            <i class="fa-solid fa-file text-muted flex-shrink-0"></i>
            <span class="text-truncate"><?php echo e($doc->original_name); ?></span>
          </a>
          <form method="POST" action="<?php echo e(route('admin.domain-documents.review', $doc)); ?>" class="d-flex align-items-center gap-2 flex-shrink-0">
            <?php echo csrf_field(); ?>
            <select name="status" class="form-select" style="padding:.2rem .5rem;font-size:.8rem;border-radius:.375rem">
              <option value="pending" <?php if($doc->status === 'pending'): echo 'selected'; endif; ?>>Menunggu</option>
              <option value="approved" <?php if($doc->status === 'approved'): echo 'selected'; endif; ?>>Setuju</option>
              <option value="rejected" <?php if($doc->status === 'rejected'): echo 'selected'; endif; ?>>Tolak</option>
            </select>
            <input type="text" name="admin_note" value="<?php echo e($doc->admin_note); ?>" placeholder="Catatan (kalau ditolak)" class="form-control form-control-sm" style="width:10rem">
            <button type="submit" class="btn btn-outline-secondary btn-sm">Simpan</button>
          </form>
        </div>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <p class="small text-muted mb-0">Klien belum mengunggah dokumen apa pun.</p>
      <?php endif; ?>

      <?php if($domain->documents->where('status', '!=', 'replaced')->isNotEmpty()): ?>
        <details class="mt-3 pt-3 border-top">
          <summary class="small fw-medium text-accent" style="cursor:pointer">
            <i class="fa-solid fa-paper-plane" style="font-size:11px"></i> Kirim Dokumen ke Registrar
          </summary>
          <form method="POST" action="<?php echo e(route('admin.domains.send-documents', $domain)); ?>" class="row g-2 align-items-end mt-2">
            <?php echo csrf_field(); ?>
            <div class="col-sm-5">
              <label class="text-muted mb-1 d-block" style="font-size:11px">Email Tujuan</label>
              <input type="email" name="recipient_email" value="<?php echo e($domain->registrar->documents_email ?? ''); ?>"
                     placeholder="verifikasi@registrar.com" class="form-control form-control-sm" required>
            </div>
            <div class="col-sm-5">
              <label class="text-muted mb-1 d-block" style="font-size:11px">Catatan (opsional)</label>
              <input type="text" name="note" placeholder="mis. mohon diproses segera" class="form-control form-control-sm">
            </div>
            <div class="col-sm-2">
              <button type="submit" class="btn btn-outline-primary btn-sm w-100">Kirim</button>
            </div>
          </form>
          <p class="text-muted mt-2 mb-0" style="font-size:11px">
            Mengirim <?php echo e($domain->documents->where('status', '!=', 'replaced')->count()); ?> berkas yang sedang berlaku
            (bukan versi lama yang sudah diganti) sebagai lampiran email.
            <?php if($domain->registrar?->documents_email): ?>
              Email tujuan terisi otomatis dari pengaturan registrar <?php echo e($domain->registrar->name); ?> — boleh diganti manual di atas.
            <?php else: ?>
              Belum ada email bawaan untuk registrar ini — atur di halaman Registrar supaya terisi otomatis lain kali.
            <?php endif; ?>
          </p>
        </details>
      <?php endif; ?>
    </div>
  <?php endif; ?>

  
  <?php if($domain->is_transfer && $domain->provision_status === 'transfer_pending'): ?>
    <div class="card border border-warning bg-warning bg-opacity-10 rounded-4 p-3 mb-3">
      <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap">
        <p class="small text-warning mb-0">
          <i class="fa-solid fa-clock"></i>
          Permintaan transfer sudah dikirim, menunggu persetujuan pemilik domain di registrar lama
          (biasanya 5–7 hari). Cek status di dashboard Liqu.id, lalu konfirmasi di sini kalau sudah selesai.
        </p>
        <form method="POST" action="<?php echo e(route('admin.domains.transfer-complete', $domain)); ?>"
              data-confirm="Konfirmasi transfer &quot;<?php echo e($domain->domain_name); ?>&quot; sudah benar-benar selesai di Liqu.id?"
              data-confirm-title="Konfirmasi Transfer Selesai" data-confirm-style="info" data-confirm-label="Ya, Sudah Selesai">
          <?php echo csrf_field(); ?>
          <button type="submit" class="btn btn-primary btn-sm flex-shrink-0"><i class="fa-solid fa-check" style="font-size:11px"></i> Tandai Transfer Selesai</button>
        </form>
      </div>
    </div>
  <?php endif; ?>

  
  <?php if($domain->status === 'expired' && $domain->registrar): ?>
    <div class="card border border-danger bg-danger bg-opacity-10 rounded-4 p-3 mb-3">
      <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap">
        <p class="small text-danger mb-0">
          <i class="fa-solid fa-triangle-exclamation"></i>
          Domain ini sudah kedaluwarsa. Masih mungkin dipulihkan lewat masa tenggang registrar
          (biasanya ~30 hari sejak kedaluwarsa, beda-beda tiap TLD) — ada biaya tambahan dari registrar.
        </p>
        <form method="POST" action="<?php echo e(route('admin.domains.restore', $domain)); ?>"
              data-confirm="Coba pulihkan &quot;<?php echo e($domain->domain_name); ?>&quot; dari masa tenggang? Registrar mungkin mengenakan biaya tambahan."
              data-confirm-title="Pulihkan Domain" data-confirm-style="warn" data-confirm-label="Ya, Coba Pulihkan">
          <?php echo csrf_field(); ?>
          <button type="submit" class="btn btn-danger btn-sm flex-shrink-0"><i class="fa-solid fa-rotate-left" style="font-size:11px"></i> Coba Pulihkan</button>
        </form>
      </div>
    </div>
  <?php endif; ?>

  
  <?php if($domain->provision_status === 'failed'): ?>
    <div class="card border border-danger bg-danger bg-opacity-10 rounded-4 p-3 mb-3">
      <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap">
        <div>
          <p class="small text-danger fw-medium mb-0"><i class="fa-solid fa-circle-exclamation"></i> Pendaftaran domain gagal</p>
          <p class="text-danger mb-0 mt-1" style="font-size:12px"><?php echo e($domain->provision_message); ?></p>
        </div>
        <form method="POST" action="<?php echo e(route('admin.domains.retry', $domain)); ?>"
              data-confirm="Coba daftarkan &quot;<?php echo e($domain->domain_name); ?>&quot; lagi sekarang?" data-confirm-title="Coba Ulang" data-confirm-style="info" data-confirm-label="Ya, Coba Lagi">
          <?php echo csrf_field(); ?>
          <button type="submit" class="btn btn-danger btn-sm flex-shrink-0"><i class="fa-solid fa-rotate-right" style="font-size:11px"></i> Coba Daftarkan Ulang</button>
        </form>
      </div>
    </div>
  <?php endif; ?>

  
  <?php if($domain->provision_status === 'needs_eligibility'): ?>
    <?php
      $tldExt = ltrim($domain->tld?->extension ?? '', '.');
      $hasExample = in_array($tldExt, ['us', 'asia']);
      $exampleValue = ['us' => 'us_purpose=business&us_category=citizen', 'asia' => 'asia_contact_id=0'][$tldExt] ?? null;
    ?>
    <div class="card border border-warning bg-warning bg-opacity-10 rounded-4 p-4 mb-3">
      <h2 class="small fw-bold text-warning mb-1"><i class="fa-solid fa-clipboard-list"></i> Butuh Data Kelayakan (Eligibility)</h2>
      <p class="small text-warning mb-3">
        Domain <b>.<?php echo e($tldExt); ?></b> mewajibkan data kelayakan tambahan dari registry aslinya sebelum bisa
        didaftarkan — belum otomatis diproses, menunggu diisi di sini.
        <?php if(! $hasExample): ?>
          <br><b>Perhatian:</b> saya tidak punya format resmi untuk <code>.<?php echo e($tldExt); ?></code> —
          cek dulu di dashboard Liqu.id atau tanya support mereka sebelum mengisi, supaya tidak salah format.
        <?php endif; ?>
      </p>
      <form method="POST" action="<?php echo e(route('admin.domains.eligibility', $domain)); ?>" class="row g-2">
        <?php echo csrf_field(); ?>
        <div class="col-sm-6">
          <label class="form-label small fw-medium text-dark">Eligibility Criteria</label>
          <input type="text" name="eligibility_criteria" value="<?php echo e(old('eligibility_criteria', $hasExample ? $tldExt : '')); ?>" class="form-control form-control-sm" placeholder="<?php echo e($tldExt); ?>">
        </div>
        <div class="col-sm-6">
          <label class="form-label small fw-medium text-dark">Extra Data</label>
          <input type="text" name="eligibility_extra" value="<?php echo e(old('eligibility_extra')); ?>" class="form-control form-control-sm" placeholder="<?php echo e($exampleValue ?? 'format sesuai TLD, cek dokumentasi Liqu.id'); ?>">
        </div>
        <?php $__errorArgs = ['eligibility_criteria'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        <?php $__errorArgs = ['eligibility_extra'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        <div class="col-12">
          <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-check" style="font-size:11px"></i> Simpan &amp; Coba Daftarkan</button>
        </div>
      </form>
    </div>
  <?php endif; ?>

  <div class="row g-3">
    <div class="col-12 col-lg-8">

      <div class="card border rounded-4 p-4 mb-3">
        <h2 class="small fw-bold text-dark mb-3">Informasi Domain</h2>
        <div class="row g-3 small">
          <div class="col-sm-6">
            <p class="text-muted mb-1" style="font-size:11px">KLIEN</p>
            <p class="fw-medium text-dark mb-0"><?php echo e($domain->client->name ?? '—'); ?></p>
          </div>
          <div class="col-sm-6">
            <p class="text-muted mb-1" style="font-size:11px">REGISTRAR</p>
            <p class="fw-medium text-dark mb-0"><?php echo e($domain->registrar->name ?? 'Manual'); ?></p>
          </div>
          <div class="col-sm-6">
            <p class="text-muted mb-1" style="font-size:11px">TANGGAL REGISTER</p>
            <p class="fw-medium text-dark mb-0"><?php echo e($domain->register_date?->format('d M Y') ?? '—'); ?></p>
          </div>
          <div class="col-sm-6">
            <p class="text-muted mb-1" style="font-size:11px">JATUH TEMPO</p>
            <p class="fw-medium text-dark mb-0"><?php echo e($domain->expiry_date?->format('d M Y') ?? '—'); ?></p>
          </div>
          <div class="col-sm-6">
            <p class="text-muted mb-1" style="font-size:11px">AUTO RENEW</p>
            <p class="fw-medium text-dark mb-0"><?php echo e($domain->auto_renew ? 'Ya' : 'Tidak'); ?></p>
          </div>
          <div class="col-sm-6">
            <p class="text-muted mb-1" style="font-size:11px">WHOIS PRIVACY</p>
            <p class="fw-medium text-dark mb-0">
              <?php echo e($domain->hasActivePrivacy() ? 'Aktif' : 'Nonaktif'); ?>

              <?php if($domain->privacy_expires_at): ?>
                <span class="text-muted fw-normal" style="font-size:11px">(s.d. <?php echo e($domain->privacy_expires_at->format('d M Y')); ?>)</span>
              <?php endif; ?>
              <?php if($domain->privacy_invoice_id): ?>
                <span class="badge badge-soft-warning" style="font-size:10px">Menunggu bayar</span>
              <?php endif; ?>
              <?php if(! is_null($privacyAtRegistrar) && $privacyAtRegistrar !== $domain->hasActivePrivacy()): ?>
                <p class="text-danger fw-normal mb-0 mt-1" style="font-size:11px">
                  <i class="fa-solid fa-triangle-exclamation"></i>
                  Di registrar: <b><?php echo e($privacyAtRegistrar ? 'Aktif' : 'Nonaktif'); ?></b> — tidak cocok.
                  <?php if($privacyAtRegistrar): ?>
                    Kita masih ditagih registrar padahal klien tidak membayar.
                  <?php else: ?>
                    Klien sudah bayar tapi belum aktif di registrar.
                  <?php endif; ?>
                </p>
              <?php endif; ?>
            </p>
          </div>
          <div class="col-sm-6">
            <p class="text-muted mb-1" style="font-size:11px">NAMESERVER</p>
            <p class="fw-medium text-dark mb-0">
              <?php if(! empty($domain->nameservers)): ?>
                <?php $__currentLoopData = $domain->nameservers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ns): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <span class="d-block" style="font-family:monospace"><?php echo e($ns); ?></span>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              <?php else: ?>
                <span class="text-danger"><i class="fa-solid fa-triangle-exclamation"></i> Belum diatur</span>
                <?php if($domain->registrar && $domain->registrar->default_ns1): ?>
                  <form method="POST" action="<?php echo e(route('admin.domains.apply-default-ns', $domain)); ?>" class="mt-1"
                        data-confirm="Terapkan nameserver default (<?php echo e($domain->registrar->default_ns1); ?>) ke domain ini?" data-confirm-title="Terapkan Nameserver Default" data-confirm-style="info" data-confirm-label="Ya, Terapkan">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-link p-0 text-accent" style="font-size:12px">Terapkan Nameserver Default</button>
                  </form>
                <?php endif; ?>
              <?php endif; ?>
            </p>
          </div>
          <div class="col-sm-6">
            <p class="text-muted mb-1" style="font-size:11px">ORDER TERKAIT</p>
            <p class="fw-medium text-dark mb-0">
              <?php if($domain->order): ?>
                <a href="<?php echo e(route('admin.orders.details', $domain->order)); ?>" class="text-decoration-none text-accent">#<?php echo e($domain->order->order_number); ?></a>
              <?php else: ?>
                —
              <?php endif; ?>
            </p>
          </div>
        </div>

        <?php if($domain->provision_message): ?>
          <div class="mt-3 pt-3 border-top small">
            <span class="text-muted" style="font-size:11px">STATUS REGISTRASI TERAKHIR</span>
            <p class="mb-0 mt-1 <?php echo e($domain->provision_status === 'registered' ? 'text-success' : (in_array($domain->provision_status, ['manual', 'needs_documents', 'needs_eligibility'], true) ? 'text-muted' : 'text-danger')); ?>"><?php echo e($domain->provision_message); ?></p>
          </div>
        <?php endif; ?>
      </div>

      <div class="card border rounded-4 p-4">
        <h2 class="small fw-bold text-dark mb-2">Catatan Internal</h2>
        <form method="POST" action="<?php echo e(route('admin.domain.notes')); ?>">
          <?php echo csrf_field(); ?>
          <input type="hidden" name="domain_id" value="<?php echo e($domain->id); ?>">
          <textarea name="internal_notes" rows="4" class="form-control form-control-sm" placeholder="Catatan staf tentang domain ini..."><?php echo e(old('internal_notes', $domain->internal_notes)); ?></textarea>
          <button type="submit" class="btn btn-outline-secondary btn-sm mt-2"><i class="fa-solid fa-floppy-disk" style="font-size:11px"></i> Simpan Catatan</button>
        </form>
      </div>
    </div>

    <div class="col-12 col-lg-4">
      <div class="card border rounded-4 p-4">
        <h2 class="small fw-bold text-dark mb-2">Aksi</h2>
        <div class="d-flex flex-column gap-2">
          <?php if($domain->registrar): ?>
            <form method="POST" action="<?php echo e(route('admin.domains.renew', $domain)); ?>" data-confirm="Perpanjang domain ini 1 tahun via registrar?" data-confirm-title="Perpanjang Domain" data-confirm-style="info" data-confirm-label="Ya, Perpanjang">
              <?php echo csrf_field(); ?>
              <button type="submit" class="btn btn-primary btn-sm w-100 text-start"><i class="fa-solid fa-rotate" style="font-size:11px"></i> Perpanjang 1 Tahun</button>
            </form>
          <?php endif; ?>
          <?php if($domain->status !== 'cancelled'): ?>
            <form method="POST" action="<?php echo e(route('admin.domain.cancel')); ?>" data-confirm="Batalkan domain ini?" data-confirm-title="Batalkan" data-confirm-style="warn" data-confirm-label="Ya, Batalkan">
              <?php echo csrf_field(); ?>
              <input type="hidden" name="domain_id" value="<?php echo e($domain->id); ?>">
              <button type="submit" class="btn btn-outline-danger btn-sm w-100 text-start"><i class="fa-solid fa-xmark" style="font-size:11px"></i> Batalkan</button>
            </form>
          <?php endif; ?>
          <a href="<?php echo e(route('admin.domain.edit.page', $domain)); ?>" class="btn btn-outline-secondary btn-sm w-100 text-start">
            <i class="fa-regular fa-pen-to-square" style="font-size:11px"></i> Edit Data
          </a>
        </div>
      </div>
    </div>
  </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/admin/domains/details.blade.php ENDPATH**/ ?>