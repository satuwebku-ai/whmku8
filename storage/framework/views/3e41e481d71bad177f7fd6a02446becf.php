<?php $__env->startSection('title', 'Status & Tampilan TLD'); ?>

<?php $__env->startSection('content'); ?>

  <?php echo $__env->make('admin.domains._nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

  <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <div>
      <h1 class="h4 fw-bold text-dark mb-1">Status &amp; Tampilan TLD</h1>
      <p class="small text-muted mb-0">Aktifkan/nonaktifkan TLD dan atur mana yang tampil di halaman Cek Domain. Harga jual, tarik harga registrar, markup massal, dan tambah TLD baru diatur di <a href="<?php echo e(route('admin.tlds.pricing')); ?>" class="text-accent">TLD Pricing</a>.</p>
    </div>
    <div class="d-flex align-items-center gap-2">
      <a href="<?php echo e(route('admin.tlds.pricing')); ?>" class="btn btn-outline-primary btn-sm">
        <i class="fa-solid fa-tags" style="font-size:11px"></i> TLD Pricing
      </a>
    </div>
  </div>

  <div class="card border rounded-4 p-4 mb-4" style="max-width:28rem">
    <h2 class="small fw-bold text-dark mb-1">Harga Add-On Domain</h2>
    <p class="text-muted mb-3" style="font-size:12px">
      Pengaturan harga & eligibilitas ID Protection (WHOIS Privacy) sekarang punya halaman sendiri —
      supaya tabel di bawah ini tidak makin padat.
    </p>
    <a href="<?php echo e(route('admin.tlds.privacy')); ?>" class="btn btn-primary btn-sm">
      <i class="fa-solid fa-user-shield" style="font-size:11px"></i> Buka Pengaturan ID Protection
    </a>
  </div>

  <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
    <?php $st = request('status'); ?>
    <a href="<?php echo e(route('admin.tlds.index')); ?>" class="px-3 py-2 rounded-pill small fw-medium text-decoration-none <?php echo e(!$st ? 'text-white' : 'text-muted'); ?>" style="<?php echo e(!$st ? 'background:#4f46e5' : 'background:#f1f5f9'); ?>">
      Semua (<?php echo e($counts['all']); ?>)
    </a>
    <a href="<?php echo e(route('admin.tlds.index', ['status' => 'active'])); ?>" class="px-3 py-2 rounded-pill small fw-medium text-decoration-none <?php echo e($st === 'active' ? 'text-white' : 'text-muted'); ?>" style="<?php echo e($st === 'active' ? 'background:#4f46e5' : 'background:#f1f5f9'); ?>">
      Aktif (<?php echo e($counts['active']); ?>)
    </a>
    <a href="<?php echo e(route('admin.tlds.index', ['status' => 'inactive'])); ?>" class="px-3 py-2 rounded-pill small fw-medium text-decoration-none <?php echo e($st === 'inactive' ? 'text-white' : 'text-muted'); ?>" style="<?php echo e($st === 'inactive' ? 'background:#4f46e5' : 'background:#f1f5f9'); ?>">
      Nonaktif (<?php echo e($counts['inactive']); ?>)
    </a>

    <span style="width:1px;height:20px;background:#e2e8f0"></span>

    <?php $wb = request('web'); ?>
    <a href="<?php echo e(route('admin.tlds.index', array_filter(['status' => $st, 'web' => null]))); ?>" class="px-3 py-2 rounded-pill small fw-medium text-decoration-none <?php echo e(!$wb ? 'text-white' : 'text-muted'); ?>" style="<?php echo e(!$wb ? 'background:#4f46e5' : 'background:#f1f5f9'); ?>">
      Semua Tampil-di-Web
    </a>
    <a href="<?php echo e(route('admin.tlds.index', array_filter(['status' => $st, 'web' => 'shown']))); ?>" class="px-3 py-2 rounded-pill small fw-medium text-decoration-none <?php echo e($wb === 'shown' ? 'text-white' : 'text-muted'); ?>" style="<?php echo e($wb === 'shown' ? 'background:#059669' : 'background:#f1f5f9'); ?>">
      Tampil di Web (<?php echo e($counts['shown']); ?>)
    </a>
    <a href="<?php echo e(route('admin.tlds.index', array_filter(['status' => $st, 'web' => 'hidden']))); ?>" class="px-3 py-2 rounded-pill small fw-medium text-decoration-none <?php echo e($wb === 'hidden' ? 'text-white' : 'text-muted'); ?>" style="<?php echo e($wb === 'hidden' ? 'background:#475569' : 'background:#f1f5f9'); ?>">
      Disembunyikan (<?php echo e($counts['hidden']); ?>)
    </a>
  </div>

  <div id="tldTableWrap">
  <div class="card border rounded-4 overflow-hidden">
    <form method="GET" class="px-4 py-3 border-bottom d-flex flex-wrap gap-2">
      <?php if(request('status')): ?>
        <input type="hidden" name="status" value="<?php echo e(request('status')); ?>">
      <?php endif; ?>
      <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari ekstensi, mis. .com" class="form-control form-control-sm" style="max-width:14rem">
      <select name="registrar" class="form-select form-select-sm" style="max-width:12rem">
        <option value="">Semua Registrar</option>
        <option value="none" <?php if(request('registrar') === 'none'): echo 'selected'; endif; ?>>— Tidak ditentukan —</option>
        <?php $__currentLoopData = $registrars; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <option value="<?php echo e($r->id); ?>" <?php if((string) request('registrar') === (string) $r->id): echo 'selected'; endif; ?>><?php echo e($r->name); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </select>
      <select name="per_page" class="form-select form-select-sm" style="max-width:9rem">
        <?php $__currentLoopData = [25, 50, 100, 200]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <option value="<?php echo e($n); ?>" <?php if((int) request('per_page', 25) === $n): echo 'selected'; endif; ?>><?php echo e($n); ?> baris</option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </select>
      <button type="submit" class="btn btn-outline-secondary btn-sm">Tampilkan</button>
      <?php if(request('search') || request('registrar')): ?>
        <a href="<?php echo e(route('admin.tlds.index', ['status' => request('status')])); ?>" class="btn btn-outline-secondary btn-sm">Reset</a>
      <?php endif; ?>
    </form>

    <form method="POST" action="<?php echo e(route('admin.tld.bulk-update')); ?>" id="bulkForm">
      <?php echo csrf_field(); ?>

      <datalist id="searchGroups">
        <?php $__currentLoopData = ['Populer', 'Indonesia', 'Bisnis', 'Teknologi', 'Umum']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $g): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <option value="<?php echo e($g); ?>"></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </datalist>

      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" style="font-size:13px">
          <thead>
            <tr class="small text-uppercase text-muted" style="background:#f8fafc">
              <th class="px-3 py-3 text-center"><input type="checkbox" id="checkAllRows" title="Pilih semua" style="margin:0"></th>
              <th class="py-3">Ekstensi</th>
              <th class="text-end py-3">Harga Jual</th>
              <th class="text-center py-3">Aktif</th>
              <th class="text-center py-3" title="Tampil di halaman Cek Domain publik">Tampil di Web</th>
              <th class="text-center py-3" title="Tampil di beranda (kotak pencarian & tabel harga). Kosong semua = 6 TLD termurah">Tampil di Beranda</th>
              <th class="py-3">Grup</th>
              <th class="text-end px-4 py-3">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $tlds; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tld): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
              <?php
                // Warna baris cuma dipakai kalau ekstensi ini memang
                // dijual >1 registrar -- kalau tunggal, tidak ada yang
                // dibandingkan, jadi dibiarkan polos.
                $cmp = $priceCompare[$tld->id] ?? null;
                $rowTone = match ($cmp['rank'] ?? null) {
                  'cheapest' => 'background:rgba(16,185,129,.07)',
                  'priciest' => 'background:rgba(239,68,68,.06)',
                  'middle'   => 'background:rgba(245,158,11,.06)',
                  default    => '',
                };
              ?>
              <tr data-row data-tld-id="<?php echo e($tld->id); ?>" data-extension="<?php echo e($tld->extension); ?>" style="<?php echo e($rowTone); ?>">
                <td class="px-3 py-2 text-center">
                  <input type="checkbox" value="<?php echo e($tld->id); ?>" data-select style="margin:0">
                </td>
                <td class="py-2 fw-medium text-dark text-nowrap">
                  <?php echo e($tld->extension); ?>

                  <?php if($tld->is_demo): ?>
                    <span class="badge" style="font-size:9px;background:#fef3c7;color:#b45309">DEMO</span>
                  <?php endif; ?>
                  <?php if($cmp): ?>
                    <span class="badge" style="font-size:9px;background:#e0e7ff;color:#4338ca" title="Ekstensi ini dijual <?php echo e($cmp['count']); ?> registrar">
                      <?php echo e($cmp['count']); ?>&times;
                    </span>
                  <?php endif; ?>
                  <span class="d-block fw-normal text-muted" style="font-size:10px"><?php echo e($tld->registrar->name ?? 'manual'); ?></span>
                </td>

                <td class="text-end py-2">
                  <?php if($tld->register_price > 0): ?>
                    <span class="fw-medium <?php echo e(($cmp['rank'] ?? null) === 'cheapest' ? 'text-success' : ((($cmp['rank'] ?? null) === 'priciest') ? 'text-danger' : 'text-dark')); ?>">
                      Rp <?php echo e(number_format($tld->register_price, 0, ',', '.')); ?>

                    </span>
                    <span class="d-block text-muted" style="font-size:10px">
                      renew Rp <?php echo e(number_format($tld->renew_price, 0, ',', '.')); ?>

                      <?php if($cmp && $cmp['rank'] !== 'cheapest'): ?>
                        · <span class="text-danger">+Rp <?php echo e(number_format($tld->register_price - $cmp['min'], 0, ',', '.')); ?></span>
                      <?php endif; ?>
                    </span>
                  <?php else: ?>
                    <a href="<?php echo e(route('admin.tlds.pricing', ['registrar' => $tld->registrar_id ?: 'none'])); ?>" class="text-danger" style="font-size:11px">
                      <i class="fa-solid fa-triangle-exclamation"></i> Belum ada harga
                    </a>
                  <?php endif; ?>
                </td>

                <td class="text-center py-2">
                  
                  <div class="form-check form-switch d-inline-block m-0">
                    <input type="checkbox" role="switch"
                           class="form-check-input tld-active-switch"
                           data-tld-id="<?php echo e($tld->id); ?>"
                           data-extension="<?php echo e($tld->extension); ?>"
                           data-no-price="<?php echo e($tld->register_price <= 0 ? '1' : '0'); ?>"
                           <?php if($tld->is_active): echo 'checked'; endif; ?>
                           <?php if($tld->register_price <= 0): echo 'disabled'; endif; ?>
                           style="cursor:<?php echo e($tld->register_price > 0 ? 'pointer' : 'not-allowed'); ?>"
                           title="<?php echo e($tld->register_price > 0 ? 'Aktifkan/nonaktifkan ' . $tld->extension : 'Isi harga jualnya dulu di TLD Pricing'); ?>">
                  </div>
                </td>

                <td class="text-center py-2">
                  <input type="checkbox" name="in_search[]" value="<?php echo e($tld->id); ?>" <?php if($tld->show_in_search): echo 'checked'; endif; ?> style="margin:0">
                </td>

                <td class="text-center py-2">
                  <input type="checkbox" name="on_home[]" value="<?php echo e($tld->id); ?>" <?php if($tld->show_on_home): echo 'checked'; endif; ?> <?php if($tld->register_price <= 0): echo 'disabled'; endif; ?> style="margin:0">
                </td>

                <td class="py-2">
                  <input type="text" name="rows[<?php echo e($tld->id); ?>][search_group]"
                         value="<?php echo e($tld->search_group); ?>" placeholder="mis. Populer"
                         list="searchGroups" class="form-control form-control-sm" style="width:7rem">
                </td>

                <td class="text-end px-4 py-2">
                  <button type="submit" form="del-<?php echo e($tld->id); ?>"
                          class="btn btn-outline-danger btn-sm d-inline-flex align-items-center justify-content-center" style="width:32px;height:32px;padding:0" title="Hapus <?php echo e($tld->extension); ?>">
                    <i class="fa-regular fa-trash-can" style="font-size:11px"></i>
                  </button>
                </td>
              </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
              <tr>
                <td colspan="8" class="text-center py-5">
                  <p class="text-muted mb-1" style="font-size:14px">Belum ada TLD.</p>
                  <p class="text-muted mb-0" style="font-size:11px">
                    Tambahkan manual, atau impor otomatis dari registrar:
                    buka tab <a href="<?php echo e(route('admin.registrars.index')); ?>" class="text-accent">Registrar</a>
                    lalu klik ikon <i class="fa-solid fa-rotate" style="font-size:10px"></i> (Sinkronkan daftar TLD).
                  </p>
                </td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

      <?php if($tlds->isNotEmpty()): ?>
        <div class="px-4 py-3 border-top d-flex align-items-center justify-content-between flex-wrap gap-2" style="background:#f8fafc">
          <p class="text-muted mb-0" style="font-size:11px">
            Ketik harga langsung di tabel, lalu klik Simpan. Perubahan berlaku untuk halaman ini saja
            (<?php echo e($tlds->count()); ?> baris) — pindah halaman tanpa menyimpan akan membatalkan perubahan.
          </p>
          <button type="submit" class="btn btn-primary btn-sm">
            <i class="fa-solid fa-floppy-disk" style="font-size:11px"></i> Simpan Perubahan
          </button>
        </div>
      <?php endif; ?>
    </form>

    <?php $__currentLoopData = $tlds; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tld): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <form id="del-<?php echo e($tld->id); ?>" method="POST" action="<?php echo e(route('admin.tlds.destroy', $tld)); ?>" class="d-none"
            data-confirm="Hapus TLD <?php echo e($tld->extension); ?>?" data-confirm-title="Hapus Data"
            data-confirm-style="danger" data-confirm-label="Ya, Hapus">
        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
      </form>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <?php if($tlds->hasPages()): ?>
      <div class="px-4 py-3 border-top"><?php echo e($tlds->links('pagination.bootstrap')); ?></div>
    <?php endif; ?>
  </div>

  </div>

<script <?php echo 'nonce="' . e(app(\App\Support\CspNonce::class)->value()) . '"'; ?>>
    document.querySelectorAll('[data-mode-radio]').forEach(function (radio) {
      radio.addEventListener('change', function () {
        document.querySelectorAll('[data-mode-panel]').forEach(function (panel) {
          panel.classList.toggle('d-none', panel.dataset.modePanel !== radio.value);
        });
      });
    });
  </script>

  <script <?php echo 'nonce="' . e(app(\App\Support\CspNonce::class)->value()) . '"'; ?>>
    (function () {
      window.initTldMargins = function () {};
    })();
  </script>

  <script <?php echo 'nonce="' . e(app(\App\Support\CspNonce::class)->value()) . '"'; ?>>
    (function () {
      // Panel "Tarik Harga Registrar" & "Markup Massal" sudah PINDAH ke
      // halaman TLD Pricing -- handler-nya ikut pindah ke sana. Yang
      // tersisa di halaman ini cuma pilih-semua untuk baris tabel.
      window.initTldSelectAll = function () {
        const all = document.getElementById('checkAllRows');
        const boxes = Array.from(document.querySelectorAll('[data-select]'));

        all?.addEventListener('change', function () {
          boxes.forEach(function (b) { b.checked = all.checked; });
        });
      };

      window.initTldSelectAll();
    })();
  </script>

  <script <?php echo 'nonce="' . e(app(\App\Support\CspNonce::class)->value()) . '"'; ?>>
    // Toggle Aktif lewat AJAX -- tidak reload halaman, jadi posisi
    // scroll & filter tetap. Server yang menegakkan aturan "satu
    // ekstensi cuma boleh aktif di satu registrar"; respons-nya memberi
    // tahu switch mana saja yang ikut dimatikan supaya tampilan langsung
    // menyesuaikan tanpa perlu muat ulang.
    (function () {
      const csrf = document.querySelector('meta[name="csrf-token"]')?.content
                || document.querySelector('input[name="_token"]')?.value;

      function toast(message, ok) {
        let box = document.getElementById('tldToast');

        if (! box) {
          box = document.createElement('div');
          box.id = 'tldToast';
          box.style.cssText = 'position:fixed;right:1.5rem;bottom:1.5rem;z-index:1080;max-width:26rem';
          document.body.appendChild(box);
        }

        const el = document.createElement('div');
        el.className = 'rounded-3 px-3 py-2 mb-2 shadow-sm';
        el.style.cssText = 'font-size:13px;background:' + (ok ? '#ecfdf5' : '#fef2f2')
                         + ';border:1px solid ' + (ok ? '#a7f3d0' : '#fecaca')
                         + ';color:' + (ok ? '#065f46' : '#991b1b');
        el.textContent = message;
        box.appendChild(el);

        setTimeout(function () {
          el.style.transition = 'opacity .4s';
          el.style.opacity = '0';
          setTimeout(function () { el.remove(); }, 400);
        }, 4000);
      }

      function syncRowTone(row, active) {
        row.style.outline = active ? '2px solid rgba(79,70,229,.35)' : '';
        row.style.outlineOffset = active ? '-2px' : '';
      }

      document.querySelectorAll('.tld-active-switch').forEach(function (sw) {
        syncRowTone(sw.closest('[data-row]'), sw.checked);

        sw.addEventListener('change', function () {
          const wanted = sw.checked;
          sw.disabled = true;

          fetch('<?php echo e(route('admin.tld.status')); ?>', {
            method: 'POST',
            headers: {
              'X-Requested-With': 'XMLHttpRequest',
              'X-CSRF-TOKEN': csrf,
              'Accept': 'application/json',
            },
            body: new URLSearchParams({ tld_id: sw.dataset.tldId }),
          })
            .then(function (res) {
              return res.json().then(function (body) {
                if (! res.ok) throw new Error(body.message || ('HTTP ' + res.status));
                return body;
              });
            })
            .then(function (body) {
              sw.checked = body.is_active;
              syncRowTone(sw.closest('[data-row]'), body.is_active);

              // Matikan switch saudara se-ekstensi yang ikut dinonaktifkan
              // server, tanpa reload.
              (body.deactivated_ids || []).forEach(function (id) {
                const other = document.querySelector('.tld-active-switch[data-tld-id="' + id + '"]');
                if (other) {
                  other.checked = false;
                  syncRowTone(other.closest('[data-row]'), false);
                }
              });

              toast(body.message, true);
            })
            .catch(function (err) {
              // Kembalikan ke posisi semula supaya tampilan tidak
              // berbohong soal keadaan sebenarnya di server.
              sw.checked = ! wanted;
              toast(err.message, false);
            })
            .finally(function () {
              sw.disabled = sw.dataset.noPrice === '1';
            });
        });
      });
    })();
  </script>

<script <?php echo 'nonce="' . e(app(\App\Support\CspNonce::class)->value()) . '"'; ?>>
  (function () {
    const wrap = document.getElementById('tldTableWrap');
    if (!wrap) return;

    let dirty = false;

    function markDirty() { dirty = true; }

    function toast(message, tone) {
      const box = document.createElement('div');
      box.className = 'position-fixed rounded-3 border px-3 py-2 shadow';
      box.style.cssText = 'bottom:20px;right:20px;z-index:1090;font-size:14px;' +
        (tone === 'error' ? 'background:#fef2f2;border-color:#fecaca;color:#b91c1c'
                           : 'background:#f0fdf4;border-color:#bbf7d0;color:#15803d');
      box.textContent = message;
      document.body.appendChild(box);
      setTimeout(function () { box.remove(); }, 4000);
    }

    async function saveChanges() {
      const form = document.getElementById('bulkForm');
      if (!form) return true;

      const res = await fetch(form.action, {
        method: 'POST',
        body: new FormData(form),
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
      });

      if (!res.ok) {
        toast('Gagal menyimpan (HTTP ' + res.status + ').', 'error');
        return false;
      }

      const data = await res.json().catch(function () { return null; });
      dirty = false;
      toast(data?.message || 'Perubahan tersimpan.', 'success');
      return true;
    }

    async function loadTable(url) {
      wrap.style.opacity = '0.5';

      try {
        const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
        const html = await res.text();
        const doc = new DOMParser().parseFromString(html, 'text/html');
        const fresh = doc.getElementById('tldTableWrap');

        if (!fresh) { window.location.href = url; return; }

        wrap.innerHTML = fresh.innerHTML;
        window.history.pushState({}, '', url);
        dirty = false;
        bind();
        window.scrollTo({ top: wrap.offsetTop - 100, behavior: 'smooth' });
      } catch (e) {
        window.location.href = url;
      } finally {
        wrap.style.opacity = '';
      }
    }

    function bind() {
      wrap.querySelectorAll('#bulkForm input').forEach(function (el) {
        el.addEventListener('input', markDirty);
        el.addEventListener('change', markDirty);
      });

      const saveBtn = wrap.querySelector('#bulkForm button[type="submit"]');
      saveBtn?.addEventListener('click', async function (e) {
        e.preventDefault();
        saveBtn.disabled = true;
        await saveChanges();
        saveBtn.disabled = false;
      });

      wrap.querySelectorAll('nav a[href], .pagination a[href]').forEach(function (link) {
        link.addEventListener('click', async function (e) {
          e.preventDefault();

          if (dirty) {
            const simpan = await window.confirmDialog({
              title: 'Perubahan Belum Disimpan',
              message: 'Ada harga yang kamu ubah tapi belum disimpan. Simpan dulu sebelum pindah halaman?',
              style: 'warn',
              label: 'Simpan & Lanjut',
            });

            if (simpan) {
              const ok = await saveChanges();
              if (!ok) return;
            }
          }

          loadTable(link.href);
        });
      });

      if (typeof window.initTldMargins === 'function') window.initTldMargins();
      if (typeof window.initTldSelectAll === 'function') window.initTldSelectAll();
    }

    bind();

    window.addEventListener('popstate', function () { loadTable(window.location.href); });

  })();
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/admin/tlds/index.blade.php ENDPATH**/ ?>