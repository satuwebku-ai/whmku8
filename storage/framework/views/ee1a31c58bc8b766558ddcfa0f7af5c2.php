<?php
  $me = auth('admin')->user();
  $initials = fn ($n) => strtoupper(mb_substr(trim($n), 0, 1) . (preg_match('/\s(\S)/u', trim($n), $m) ? $m[1] : ''));
  $when = fn ($d) => ! $d ? '' : ($d->isToday() ? $d->format('H:i') : ($d->isYesterday() ? 'Kemarin' : ($d->isSameYear(now()) ? $d->translatedFormat('d M') : $d->format('d/m/y'))));
  $chIcon = ['whatsapp' => ['wa', 'fa-brands fa-whatsapp'], 'email' => ['em', 'fa-regular fa-envelope'], 'web' => ['web', 'fa-solid fa-globe']];
  $chat = $chat ?? null;
?>

<link rel="stylesheet" href="<?php echo e(asset('assets/css/lumora-inbox.css')); ?>">

<div class="ix-app ix-chat <?php echo e($chat ? 'has-chat' : ''); ?>">

  
  <aside class="ix-clist">
    <div class="ix-me">
      <span class="ix-av me"><?php echo e($initials($me->name)); ?></span>
      <div style="min-width:0">
        <div class="nm"><?php echo e($me->name); ?></div>
        <div class="rl text-capitalize"><?php echo e($me->role); ?></div>
      </div>
      <a href="<?php echo e(route('admin.settings.livechat')); ?>" class="ix-icon-btn" title="Atur Widget"><i class="fa-solid fa-gear"></i></a>
    </div>

    <form method="GET" action="<?php echo e(route('admin.chats')); ?>" class="ix-search">
      <?php if($tab === 'closed'): ?><input type="hidden" name="status" value="closed"><?php endif; ?>
      <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari nama, email, WhatsApp...">
      <button type="submit" aria-label="Cari"><i class="fa-solid fa-magnifying-glass"></i></button>
    </form>

    <div class="ix-tabs">
      <a href="<?php echo e(route('admin.chats')); ?>" class="<?php echo e($tab !== 'closed' ? 'active' : ''); ?>">
        <i class="fa-regular fa-comment"></i> Aktif (<?php echo e($counts['open']); ?>)
        <?php if($counts['unread'] > 0): ?><span class="ix-badge" style="margin-left:3px"><?php echo e($counts['unread']); ?></span><?php endif; ?>
      </a>
      <a href="<?php echo e(route('admin.chats', ['status' => 'closed'])); ?>" class="<?php echo e($tab === 'closed' ? 'active' : ''); ?>">
        <i class="fa-solid fa-check-double"></i> Ditutup (<?php echo e($counts['closed']); ?>)
      </a>
    </div>

    <div class="lbl">Percakapan terbaru</div>

    <div class="ix-citems">
      <?php $__empty_1 = true; $__currentLoopData = $conversations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <?php
          $last = $c->latestMessage;
          $preview = $last ? ($last->message ?: 'Lampiran') : 'Belum ada pesan';
          $ch = $chIcon[$c->channel] ?? $chIcon['web'];
        ?>
        <a href="<?php echo e(route('admin.chats.show', $c)); ?>" class="ix-ci <?php echo e($c->unread_for_admin > 0 ? 'unread' : ''); ?> <?php echo e($chat && $chat->id === $c->id ? 'active' : ''); ?>">
          <span class="ix-av"><?php echo e($c->initials); ?><span class="ix-ch <?php echo e($ch[0]); ?>"><i class="<?php echo e($ch[1]); ?>"></i></span></span>
          <div class="mid">
            <div class="t1"><b><?php echo e($c->display_name); ?></b><span><?php echo e($when($c->last_message_at)); ?></span></div>
            <div class="t2">
              <em><?php echo e($last && $last->sender === 'admin' ? 'Anda: ' : ''); ?><?php echo e(\Illuminate\Support\Str::limit($preview, 48)); ?></em>
              <?php if($c->unread_for_admin > 0): ?><span class="ix-badge"><?php echo e($c->unread_for_admin); ?></span><?php endif; ?>
            </div>
            <?php if($c->assignedAdmin): ?>
              <div class="t3"><i class="fa-solid fa-user" style="font-size:8px"></i> <?php echo e($c->assignedAdmin->id === $me->id ? 'Anda' : $c->assignedAdmin->name); ?> · <?php echo e($c->client_id ? 'Klien' : 'Tamu'); ?></div>
            <?php elseif($c->status === 'open'): ?>
              <div class="t3 warn"><i class="fa-solid fa-circle-exclamation" style="font-size:8px"></i> Belum dipegang · <?php echo e($c->client_id ? 'Klien' : 'Tamu'); ?></div>
            <?php endif; ?>
          </div>
        </a>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="ix-empty">
          <b><?php echo e(request('search') ? 'Tidak ada yang cocok.' : 'Belum ada percakapan.'); ?></b>
          Pastikan widget aktif di <a href="<?php echo e(route('admin.settings.livechat')); ?>">Pengaturan → Live Chat</a>.
        </div>
      <?php endif; ?>
    </div>

    <?php if($conversations->hasPages()): ?>
      <div class="ix-cpager">
        <?php if($conversations->onFirstPage()): ?> <span style="color:#cbd5e1">‹ Sebelumnya</span> <?php else: ?> <a href="<?php echo e($conversations->previousPageUrl()); ?>">‹ Sebelumnya</a> <?php endif; ?>
        <?php if($conversations->hasMorePages()): ?> <a href="<?php echo e($conversations->nextPageUrl()); ?>">Berikutnya ›</a> <?php else: ?> <span style="color:#cbd5e1">Berikutnya ›</span> <?php endif; ?>
      </div>
    <?php endif; ?>
  </aside>

  
  <section class="ix-conv">
    <?php if(! $chat): ?>
      <div class="ix-none">
        <i class="fa-regular fa-comments"></i>
        <div>Pilih percakapan di kiri untuk mulai membalas.</div>
      </div>
    <?php else: ?>
      <?php $ch = $chIcon[$chat->channel] ?? $chIcon['web']; $mine = $chat->assignedAdmin && $chat->assignedAdmin->id === $me->id; ?>

      <div class="ix-conv-h">
        <a href="<?php echo e(route('admin.chats', $chat->status === 'closed' ? ['status' => 'closed'] : [])); ?>" class="ix-icon-btn ix-back" aria-label="Kembali"><i class="fa-solid fa-arrow-left"></i></a>
        <span class="ix-av"><?php echo e($chat->initials); ?><span class="ix-ch <?php echo e($ch[0]); ?>"><i class="<?php echo e($ch[1]); ?>"></i></span></span>
        <div style="min-width:0">
          <div class="nm">
            <?php echo e($chat->display_name); ?>

            <?php if($chat->client_id): ?><span class="ix-pill ok">Klien</span><?php else: ?><span class="ix-pill mute">Tamu</span><?php endif; ?>
            <?php if($chat->status === 'closed'): ?><span class="ix-pill mute">Ditutup</span><?php endif; ?>
          </div>
          <div class="sb">
            <?php if($chat->client): ?><a href="<?php echo e(route('admin.clients.details', $chat->client)); ?>" class="text-decoration-none">Profil klien</a> · <?php endif; ?>
            <?php echo e($chat->email ?: 'email tidak diisi'); ?>

            <?php if($chat->phone): ?> · <a href="https://wa.me/<?php echo e(preg_replace('/\D/', '', $chat->phone)); ?>" target="_blank" rel="noopener" class="text-decoration-none text-success"><i class="fa-brands fa-whatsapp"></i> <?php echo e($chat->phone); ?></a><?php endif; ?>
          </div>
        </div>

        <div class="ix-actions">
          <button type="button" class="ix-icon-btn" id="admInfoBtn" title="Informasi"><i class="fa-solid fa-circle-info"></i></button>
          <?php if($chat->ticket_id): ?>
            <a href="<?php echo e(route('admin.tickets.details', $chat->ticket_id)); ?>" class="ix-icon-btn" title="Lihat tiket <?php echo e($chat->ticket?->ticket_number); ?>"><i class="fa-solid fa-ticket"></i></a>
          <?php elseif($chat->client_id): ?>
            <form method="POST" action="<?php echo e(route('admin.chats.convert-to-ticket', $chat)); ?>"
                  data-confirm="Jadikan percakapan ini tiket support? Transkrip chat akan disalin jadi pesan pertama, dan balasan Anda selanjutnya otomatis dikirim ke email klien."
                  data-confirm-title="Jadikan Tiket" data-confirm-style="info" data-confirm-label="Ya, Jadikan Tiket">
              <?php echo csrf_field(); ?>
              <button type="submit" class="ix-icon-btn" title="Jadikan tiket"><i class="fa-solid fa-ticket"></i></button>
            </form>
          <?php endif; ?>
          <?php if($chat->status === 'open'): ?>
            <form method="POST" action="<?php echo e(route('admin.chats.close', $chat)); ?>"><?php echo csrf_field(); ?>
              <button type="submit" class="ix-icon-btn" title="Tutup percakapan"><i class="fa-solid fa-check"></i></button>
            </form>
          <?php endif; ?>
          <form method="POST" action="<?php echo e(route('admin.chats.delete', $chat)); ?>"
                data-confirm="Hapus percakapan ini beserta semua pesannya?"
                data-confirm-title="Hapus Percakapan" data-confirm-style="danger" data-confirm-label="Ya, Hapus">
            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
            <button type="submit" class="ix-icon-btn danger" title="Hapus"><i class="fa-regular fa-trash-can"></i></button>
          </form>
        </div>
      </div>

      <div class="ix-claim <?php echo e($mine ? 'mine' : ''); ?>">
        <?php if($mine): ?>
          <i class="fa-solid fa-user-check"></i> Dipegang: <b>Anda</b>
        <?php elseif($chat->assignedAdmin): ?>
          <i class="fa-solid fa-user"></i> Dipegang: <b><?php echo e($chat->assignedAdmin->name); ?></b>
          <form method="POST" action="<?php echo e(route('admin.chats.claim', $chat)); ?>" class="d-inline"><?php echo csrf_field(); ?><button type="submit">Ambil Alih</button></form>
        <?php else: ?>
          <i class="fa-solid fa-circle-exclamation"></i> Belum ada yang memegang
          <form method="POST" action="<?php echo e(route('admin.chats.claim', $chat)); ?>" class="d-inline"><?php echo csrf_field(); ?><button type="submit">Ambil Alih</button></form>
        <?php endif; ?>
      </div>

      <div class="ix-info" id="admInfo">
        <div><b>STATUS</b><?php echo e($chat->status === 'open' ? 'Aktif' : 'Ditutup'); ?></div>
        <div><b>DIMULAI</b><?php echo e($chat->created_at->format('d M Y H:i')); ?></div>
        <div><b>KANAL</b><?php echo e(['whatsapp' => 'WhatsApp', 'email' => 'Email'][$chat->channel] ?? 'Website'); ?></div>
        <?php if($chat->page_url): ?><div style="max-width:24rem;word-break:break-all"><b>DARI HALAMAN</b><?php echo e($chat->page_url); ?></div><?php endif; ?>
        <div><b>ALAMAT IP</b><?php echo e($chat->ip_address ?: '—'); ?></div>
      </div>

      <div id="adminChatBody" class="ix-body"></div>

      <form id="adminChatForm" class="ix-compose-bar">
        <?php echo csrf_field(); ?>
        <div class="ix-chip" id="admFileChip">
          <i class="fa-solid fa-paperclip"></i><span id="admFileName"></span>
          <button type="button" id="admFileRemove"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="row">
          <label class="ix-round mb-0" title="Lampirkan berkas">
            <i class="fa-solid fa-paperclip" style="font-size:13px"></i>
            <input type="file" id="admFile" name="attachment" accept="image/*,application/pdf" class="d-none">
          </label>
          <?php $chatTpls = \App\Models\MailTemplate::active()->ordered()->get(['id', 'title', 'body']); ?>
          <?php if($chatTpls->isNotEmpty()): ?>
            <select id="admTpl" class="ix-tpl" title="Template balasan" style="max-width:120px">
              <option value="">⚡ Template</option>
              <?php $__currentLoopData = $chatTpls; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($t->id); ?>"><?php echo e($t->title); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
          <?php endif; ?>
          <?php if(app(\App\Services\Chat\AiReplyDrafter::class)->available()): ?>
            <button type="button" id="admAiDraft" class="ix-tpl" style="cursor:pointer" title="Minta AI menulis draf balasan">✨ Draf AI</button>
          <?php endif; ?>
          <textarea id="admInput" name="message" rows="1" placeholder="Tulis pesan… (Enter kirim, Shift+Enter baris baru)"></textarea>
          <button type="submit" id="admSend" class="ix-round send" aria-label="Kirim"><i class="fa-solid fa-paper-plane" style="font-size:13px"></i></button>
        </div>
        <p id="admError" class="ix-err d-none mb-0"></p>
      </form>

      <script <?php echo 'nonce="' . e(app(\App\Support\CspNonce::class)->value()) . '"'; ?>>
        (function () {
          const body   = document.getElementById('adminChatBody');
          const form   = document.getElementById('adminChatForm');
          const input  = document.getElementById('admInput');
          const fileIn = document.getElementById('admFile');
          const chip   = document.getElementById('admFileChip');
          const chipNm = document.getElementById('admFileName');
          const errBox = document.getElementById('admError');
          const sendBt = document.getElementById('admSend');

          const token    = document.querySelector('meta[name="csrf-token"]')?.content;
          const urlPoll  = <?php echo json_encode(route('admin.chats.poll', $chat), 512) ?>;
          const urlReply = <?php echo json_encode(route('admin.chats.reply', $chat), 512) ?>;
          const guestIni = <?php echo json_encode($chat->initials, 15, 512) ?>;

          let lastId = 0;

          function esc(t) { const d = document.createElement('div'); d.textContent = t ?? ''; return d.innerHTML; }
          function ini(n) {
            const p = String(n || 'T').trim().split(/\s+/);
            return (p[0][0] + (p[1] ? p[1][0] : '')).toUpperCase();
          }

          function append(msg) {
            if (msg.id && document.querySelector('[data-mid="' + msg.id + '"]')) return;

            const out = msg.sender === 'admin';
            const bot = msg.sender === 'bot';

            let content = '';
            if (msg.message) {
              content += esc(msg.message)
                .replace(/(https?:\/\/[^\s]+)/g, '<a href="$1" target="_blank" rel="noopener">$1</a>')
                .replace(/\n/g, '<br>');
            }
            if (msg.attachment_url) {
              content += msg.is_image
                ? '<a href="' + msg.attachment_url + '" target="_blank"><img src="' + msg.attachment_url + '" alt=""></a>'
                : '<a href="' + msg.attachment_url + '" target="_blank" style="display:block;margin-top:4px;font-size:12px"><i class="fa-solid fa-file-arrow-down"></i> ' + esc(msg.attachment_name) + '</a>';
            }

            const av = out ? ini(msg.author) : (bot ? '<i class="fa-solid fa-robot"></i>' : guestIni);
            const who = (!out && bot) ? 'Bot' : (out ? (msg.author || 'Tim Support') : '');

            const el = document.createElement('div');
            el.className = 'ix-msg ' + (out ? 'out' : '') + ' ' + (bot ? 'bot' : '');
            if (msg.id) el.dataset.mid = msg.id;
            el.innerHTML = '<span class="ix-av sm ' + (out ? 'me' : '') + '">' + av + '</span>'
              + '<div><div class="ix-bubble">' + content + '</div>'
              + '<div class="ix-time">' + (who ? esc(who) + ' · ' : '') + esc(msg.time || '') + '</div></div>';

            body.appendChild(el);
            body.scrollTop = body.scrollHeight;
            if (msg.id) lastId = Math.max(lastId, msg.id);
          }

          async function poll() {
            try {
              const res = await fetch(urlPoll + '?after=' + lastId, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
              const data = await res.json();
              (data.messages || []).forEach(append);
            } catch (e) { /* diam: lanjut saat koneksi pulih */ }
          }

          const tpls = <?php echo json_encode($chatTpls->keyBy('id'), 15, 512) ?>;
          const tplSel = document.getElementById('admTpl');
          if (tplSel) {
            tplSel.addEventListener('change', function () {
              const t = tpls[tplSel.value]; tplSel.value = '';
              if (!t) return;
              input.value = t.body
                .split('{nama}').join(<?php echo json_encode($chat->display_name, 15, 512) ?>)
                .split('{email}').join(<?php echo json_encode((string) $chat->email, 15, 512) ?>)
                .split('{site}').join(<?php echo json_encode((string) \App\Models\Setting::get('site_name', config('app.name')), 512) ?>)
                .split('{admin}').join(<?php echo json_encode($me->name, 15, 512) ?>);
              input.focus();
              input.dispatchEvent(new Event('input'));
            });
          }

          const aiBtn = document.getElementById('admAiDraft');
          if (aiBtn) {
            aiBtn.addEventListener('click', async function () {
              const label = aiBtn.textContent;
              aiBtn.disabled = true; aiBtn.textContent = 'Menulis…';
              try {
                const res = await fetch(<?php echo json_encode(route('admin.chats.ai-draft', $chat), 512) ?>, {
                  method: 'POST',
                  headers: { 'X-CSRF-TOKEN': token, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                });
                const data = await res.json();
                if (!res.ok || !data.ok) { errBox.textContent = data.message || 'AI belum bisa membuat draf.'; errBox.classList.remove('d-none'); return; }
                errBox.classList.add('d-none');
                input.value = input.value.trim() === '' ? data.text : input.value.replace(/\s+$/, '') + '\n\n' + data.text;
                input.focus();
                input.dispatchEvent(new Event('input'));
              } catch (e) {
                errBox.textContent = 'Tidak bisa terhubung ke server.'; errBox.classList.remove('d-none');
              } finally { aiBtn.disabled = false; aiBtn.textContent = label; }
            });
          }

          document.getElementById('admInfoBtn').addEventListener('click', function () {
            document.getElementById('admInfo').classList.toggle('open');
          });

          fileIn.addEventListener('change', function () {
            if (!fileIn.files.length) return;
            chipNm.textContent = fileIn.files[0].name;
            chip.classList.add('show');
          });
          document.getElementById('admFileRemove').addEventListener('click', function () {
            fileIn.value = ''; chip.classList.remove('show');
          });

          input.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); form.requestSubmit(); }
          });
          input.addEventListener('input', function () {
            input.style.height = 'auto';
            input.style.height = Math.min(input.scrollHeight, 96) + 'px';
          });

          form.addEventListener('submit', async function (e) {
            e.preventDefault();
            errBox.classList.add('d-none');

            const fd = new FormData(form);
            if (!fd.get('message')?.trim() && !fileIn.files.length) return;

            sendBt.disabled = true;
            try {
              const res = await fetch(urlReply, {
                method: 'POST', body: fd,
                headers: { 'X-CSRF-TOKEN': token, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
              });
              const data = await res.json();
              if (!res.ok || !data.ok) {
                errBox.textContent = data.message || 'Gagal mengirim.';
                errBox.classList.remove('d-none');
                return;
              }
              append(data.message);
              input.value = ''; input.style.height = 'auto'; fileIn.value = ''; chip.classList.remove('show');

              if (data.email_failed) {
                errBox.textContent = 'Balasan tersimpan, tetapi email ke pengunjung gagal terkirim. Periksa Pengaturan → Email.';
                errBox.classList.remove('d-none');
              }

              if (data.auto_assigned) {
                const card = document.createElement('div');
                card.className = 'ix-auto';
                card.innerHTML = '<span><i class="fa-solid fa-inbox" style="color:#6366f1"></i> Chat baru otomatis ditugaskan: <b>' + esc(data.auto_assigned.name) + '</b></span>'
                  + '<a href="' + data.auto_assigned.url + '" class="ix-btn pri" style="padding:.3rem .8rem;font-size:12px">Buka</a>';
                form.parentElement.insertBefore(card, form);
              }
            } catch (err) {
              errBox.textContent = 'Tidak bisa terhubung.';
              errBox.classList.remove('d-none');
            } finally { sendBt.disabled = false; }
          });

          poll();
          setInterval(poll, 5000);
        })();
      </script>
    <?php endif; ?>
  </section>
</div>
<?php /**PATH /home/runner/workspace/hosting-billing/resources/views/admin/chats/_workspace.blade.php ENDPATH**/ ?>