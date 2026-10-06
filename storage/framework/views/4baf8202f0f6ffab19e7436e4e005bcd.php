<?php $__env->startSection('title', 'Live Chat'); ?>
<?php $__env->startSection('content'); ?>
  <?php echo $__env->make('admin.settings._nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

  <?php use App\Models\Setting; $provider = Setting::get('livechat_provider', 'none'); ?>

  <div class="mb-4 d-flex align-items-center gap-3 flex-wrap">
    <div>
      <h1 class="h4 fw-bold text-dark mb-1">Live Chat</h1>
      <p class="small text-muted mb-0">Widget chat yang tampil di halaman publik.</p>
    </div>
    <?php
      $liveChatStatus = Setting::get('livechat_last_test_status');
      $liveChatTestedAt = Setting::get('livechat_last_test_at');
    ?>
    <?php if($liveChatStatus === 'success'): ?>
      <span class="badge badge-soft-success" title="Diuji <?php echo e(\Carbon\Carbon::parse($liveChatTestedAt)->diffForHumans()); ?>">
        <i class="fa-solid fa-check" style="font-size:10px"></i> Success
      </span>
    <?php elseif($liveChatStatus === 'failed'): ?>
      <span class="badge badge-soft-danger" title="Diuji <?php echo e(\Carbon\Carbon::parse($liveChatTestedAt)->diffForHumans()); ?>">
        <i class="fa-solid fa-xmark" style="font-size:10px"></i> Ditolak
      </span>
    <?php endif; ?>
  </div>

  <div class="rounded-3 px-3 py-2 mb-3" style="max-width:42rem;background:#fffbeb;border:1px solid #fde68a;font-size:12px;color:#92400e">
    <i class="fa-solid fa-circle-info"></i>
    Live chat di sini memakai layanan pihak ketiga (Tawk.to / Crisp) atau tombol WhatsApp —
    bukan sistem chat yang dibangun sendiri. Chat realtime buatan sendiri butuh WebSocket server
    dan staf yang standby di dashboard, jadi untuk kebanyakan bisnis hosting layanan eksternal
    lebih praktis dan gratis di tier dasarnya.
  </div>

  <form method="POST" action="<?php echo e(route('admin.settings.livechat.update')); ?>" class="card border rounded-4 p-4" style="max-width:42rem">
    <?php echo csrf_field(); ?>

    <div class="mb-3">
      <label class="form-label small fw-medium text-dark">Penyedia</label>
      <select name="livechat_provider" id="providerSelect" class="form-select" style="padding:.25rem .6rem;font-size:.875rem;border-radius:.375rem">
        <option value="none" <?php if($provider === 'none'): echo 'selected'; endif; ?>>Nonaktif</option>
        <option value="widget" <?php if($provider === 'widget'): echo 'selected'; endif; ?>>Widget Bawaan (WhatsApp + Tiket + Email)</option>
        <option value="whatsapp" <?php if($provider === 'whatsapp'): echo 'selected'; endif; ?>>Tombol WhatsApp (paling sederhana)</option>
        <option value="tawkto" <?php if($provider === 'tawkto'): echo 'selected'; endif; ?>>Tawk.to (gratis)</option>
        <option value="crisp" <?php if($provider === 'crisp'): echo 'selected'; endif; ?>>Crisp</option>
      </select>
    </div>

    <div id="fieldProperty" class="d-none mb-3">
      <label class="form-label small fw-medium text-dark"><span id="labelProperty">Property ID</span></label>
      <input type="text" name="livechat_property_id" value="<?php echo e(old('livechat_property_id', Setting::get('livechat_property_id'))); ?>" class="form-control form-control-sm" placeholder="contoh: 65a1b2c3d4e5f6/1abcdefg">
      <?php $__errorArgs = ['livechat_property_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      <p class="text-muted mt-1 mb-0" style="font-size:11px" id="hintProperty"></p>
    </div>

    <div id="fieldWa" class="d-none">
      <div class="mb-3">
        <label class="form-label small fw-medium text-dark">Nomor WhatsApp</label>
        <input type="text" name="livechat_whatsapp" value="<?php echo e(old('livechat_whatsapp', Setting::get('livechat_whatsapp'))); ?>" class="form-control form-control-sm" placeholder="6281234567890">
        <?php $__errorArgs = ['livechat_whatsapp'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger mt-1 mb-0" style="font-size:12px"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        <p class="text-muted mt-1 mb-0" style="font-size:11px">Diawali kode negara tanpa tanda +. Contoh: 6281234567890</p>
      </div>
      <div class="mb-3">
        <label class="form-label small fw-medium text-dark">Pesan Pembuka</label>
        <input type="text" name="livechat_greeting" value="<?php echo e(old('livechat_greeting', Setting::get('livechat_greeting', 'Halo, saya ingin bertanya tentang layanan hosting.'))); ?>" class="form-control form-control-sm">
        <p class="text-muted mt-1 mb-0" style="font-size:11px">Teks yang otomatis terisi saat pengunjung membuka chat.</p>
      </div>
      <div class="mb-3">
        <label class="form-label small fw-medium text-dark">Pesan Sambutan Otomatis</label>
        <input type="text" name="chat_greeting_1" value="<?php echo e(old('chat_greeting_1', Setting::get('chat_greeting_1', 'Selamat datang! Ada yang bisa kami bantu?'))); ?>" class="form-control form-control-sm">
        <p class="text-muted mt-1 mb-0" style="font-size:11px">Muncul otomatis saat pengunjung membuka chat pertama kali.</p>
      </div>
      <div class="mb-3">
        <label class="form-label small fw-medium text-dark">Pesan Kedua / Promo <span class="text-muted fw-normal">(opsional)</span></label>
        <input type="text" name="chat_greeting_2" value="<?php echo e(old('chat_greeting_2', Setting::get('chat_greeting_2'))); ?>" class="form-control form-control-sm"
               placeholder="Promo hosting + domain gratis, cek di halaman Hosting!">
        <p class="text-muted mt-1 mb-0" style="font-size:11px">Ditampilkan setelah pesan sambutan. Boleh berisi tautan.</p>
      </div>
      <div class="mb-3">
        <label class="form-label small fw-medium text-dark">Jam Operasional <span class="text-muted fw-normal">(opsional)</span></label>
        <input type="text" name="support_hours" value="<?php echo e(old('support_hours', Setting::get('support_hours'))); ?>" class="form-control form-control-sm" placeholder="Senin–Jumat, 09.00–17.00 WIB">
        <p class="text-muted mt-1 mb-0" style="font-size:11px">Ditampilkan di widget supaya pengunjung tahu kapan bisa dibalas cepat.</p>
      </div>
    </div>

    
    <div id="fieldMenu" class="d-none rounded-4 border p-3 mb-3" style="background:#f8fafc">
      <p class="fw-bold text-dark mb-1" style="font-size:13px"><i class="fa-solid fa-list"></i> Pilihan di Layar Awal Widget</p>
      <p class="text-muted mb-2" style="font-size:11px">Saat tombol chat diklik, pengunjung memilih ingin menghubungi lewat mana. Matikan yang tidak dipakai. Email muncul kalau email support atau SMTP sudah diisi, WhatsApp muncul kalau nomornya diisi.</p>
      <?php $__currentLoopData = ['chat' => 'Live Chat', 'email' => 'Email', 'ticket' => 'Support / Ticket', 'wa' => 'WhatsApp']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <label class="d-flex align-items-center gap-2 mb-1" style="font-size:12px">
          <input type="checkbox" name="livechat_menu_<?php echo e($k); ?>" value="1" <?php if(old('livechat_menu_'.$k, Setting::get('livechat_menu_'.$k, '1')) !== '0'): echo 'checked'; endif; ?> class="form-check-input" style="margin-top:0">
          <?php echo e($label); ?>

        </label>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <div id="hintWidget" class="d-none rounded-3 px-3 py-2 mb-3" style="background:#eef2ff;border:1px solid #c7d2fe;font-size:12px;color:#4338ca">
      <i class="fa-solid fa-circle-info"></i>
      <b>Widget Bawaan</b> menampilkan tombol mengambang di pojok kanan bawah berisi pilihan:
      Live Chat, Email, Support/Ticket, dan WhatsApp. Tidak memuat script pihak ketiga sama sekali,
      jadi halaman tetap ringan dan data pengunjung tidak dikirim ke layanan luar.
      Email support diambil dari <b>Pengaturan → Umum</b>.
    </div>

    
    <div id="fieldAiBot" class="d-none rounded-4 border p-3 mb-3" style="background:#f8fafc">
      <div class="d-flex align-items-center justify-content-between mb-2">
        <p class="fw-bold text-dark mb-0" style="font-size:13px"><i class="fa-solid fa-robot"></i> Balasan Otomatis dengan AI</p>
        <label class="d-flex align-items-center gap-2 mb-0" style="font-size:12px">
          <input type="checkbox" name="ai_chat_enabled" value="1" <?php if(Setting::get('ai_chat_enabled') === '1'): echo 'checked'; endif; ?> class="form-check-input" style="margin-top:0">
          Aktif
        </label>
      </div>
      <p class="text-muted mb-3" style="font-size:11px">
        Saat pengunjung mengirim pesan dan belum ada admin yang menangani percakapannya, AI akan
        membalas otomatis pakai AI. Begitu ada admin yang ikut membalas, bot
        otomatis berhenti — staf manusia dianggap sudah mengambil alih.
      </p>

      <div class="mb-3">
        <label class="form-label small fw-medium text-dark">Provider AI</label>
        <select name="ai_chat_provider" id="aiProviderSelect" class="form-select form-select-sm">
          <?php $aiProvider = Setting::get('ai_chat_provider', 'anthropic'); ?>
          <?php $__currentLoopData = \App\Services\Chat\AiProviderFactory::PROVIDERS; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($key); ?>" <?php if($aiProvider === $key): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <p class="text-muted mt-1 mb-0" style="font-size:11px">
          Bisa diganti kapan saja — konteks bisnis di bawah dipakai sama untuk provider manapun,
          cuma kunci API & model yang beda.
        </p>
      </div>

      
      <div id="fieldAnthropic" class="mb-3">
        <label class="form-label small fw-medium text-dark">Anthropic API Key <?php echo e(filled(Setting::get('ai_chat_api_key')) ? '(sudah tersimpan, kosongkan jika tidak diganti)' : ''); ?></label>
        <input type="password" name="ai_chat_api_key" class="form-control form-control-sm" placeholder="<?php echo e(filled(Setting::get('ai_chat_api_key')) ? '••••••••••••' : 'sk-ant-...'); ?>">
        <p class="text-muted mt-1 mb-0" style="font-size:11px">
          Ambil dari <a href="https://console.anthropic.com/settings/keys" target="_blank" class="text-decoration-underline">console.anthropic.com</a>.
          Ini terpisah dari langganan Claude.ai biasa — API dikenai biaya per penggunaan sendiri.
        </p>
        <label class="form-label small fw-medium text-dark mt-2">Model</label>
        <select name="ai_chat_model_anthropic" class="form-select form-select-sm">
          <?php $modelA = Setting::get('ai_chat_model', 'claude-sonnet-4-6'); ?>
          <option value="claude-haiku-4-5-20251001" <?php if($modelA === 'claude-haiku-4-5-20251001'): echo 'selected'; endif; ?>>Claude Haiku 4.5 — tercepat & termurah</option>
          <option value="claude-sonnet-5" <?php if($modelA === 'claude-sonnet-5'): echo 'selected'; endif; ?>>Claude Sonnet 5 — seimbang (disarankan)</option>
          <option value="claude-opus-4-8" <?php if($modelA === 'claude-opus-4-8'): echo 'selected'; endif; ?>>Claude Opus 4.8 — paling mampu, lebih mahal</option>
        </select>
      </div>

      
      <div id="fieldOpenAi" class="mb-3 d-none">
        <label class="form-label small fw-medium text-dark">OpenAI API Key <?php echo e(filled(Setting::get('ai_chat_openai_api_key')) ? '(sudah tersimpan, kosongkan jika tidak diganti)' : ''); ?></label>
        <input type="password" name="ai_chat_openai_api_key" class="form-control form-control-sm" placeholder="<?php echo e(filled(Setting::get('ai_chat_openai_api_key')) ? '••••••••••••' : 'sk-...'); ?>">
        <p class="text-muted mt-1 mb-0" style="font-size:11px">
          Ambil dari <a href="https://platform.openai.com/api-keys" target="_blank" class="text-decoration-underline">platform.openai.com</a>.
          Terpisah dari langganan ChatGPT Plus — API dikenai biaya per penggunaan sendiri.
        </p>
        <label class="form-label small fw-medium text-dark mt-2">Model</label>
        <select name="ai_chat_model_openai" class="form-select form-select-sm">
          <?php $modelO = Setting::get('ai_chat_model_openai', 'gpt-4o-mini'); ?>
          <option value="gpt-4o-mini" <?php if($modelO === 'gpt-4o-mini'): echo 'selected'; endif; ?>>GPT-4o mini — tercepat & termurah</option>
          <option value="gpt-4o" <?php if($modelO === 'gpt-4o'): echo 'selected'; endif; ?>>GPT-4o — seimbang</option>
        </select>
      </div>

      <label class="d-flex align-items-start gap-2 mb-3" style="font-size:12px">
        <input type="checkbox" name="ai_chat_first_only" value="1" <?php if(Setting::get('ai_chat_first_only', '1') === '1'): echo 'checked'; endif; ?> class="form-check-input" style="margin-top:.2rem">
        <span><b>Balasan awal saja.</b> Bot hanya menjawab pesan pertama pengunjung, lalu diam sampai admin membalas.
        Matikan kalau bot boleh terus menjawab sampai admin ikut membalas.</span>
      </label>

      <label class="d-flex align-items-start gap-2 mb-3" style="font-size:12px">
        <input type="checkbox" name="ai_chat_whatsapp" value="1" <?php if(Setting::get('ai_chat_whatsapp', '0') === '1'): echo 'checked'; endif; ?> class="form-check-input" style="margin-top:.2rem">
        <span><b>Bot AI juga membalas pesan WhatsApp masuk.</b> Default mati: AI hanya di widget Live Chat web.
        Kalau dinyalakan, bot membalas ke nomor pengirim lewat gateway WhatsApp Anda.</span>
      </label>

      <div>
        <label class="form-label small fw-medium text-dark">Info Bisnis untuk Bot</label>
        <textarea name="ai_chat_context" rows="6" class="form-control form-control-sm" placeholder="Contoh:
- Kami menjual shared hosting, VPS, dan domain.
- Jam kerja support: Senin–Jumat 09.00–17.00 WIB.
- Untuk masalah pembayaran/refund, arahkan ke tiket support.
- Harga hosting mulai Rp 15.000/bulan, VPS mulai Rp 75.000/bulan."><?php echo e(old('ai_chat_context', Setting::get('ai_chat_context'))); ?></textarea>
        <p class="text-muted mt-1 mb-0" style="font-size:11px">
          Ini yang membuat bot benar-benar tahu soal bisnismu — tanpa diisi, bot cuma bisa menjawab
          umum. Jangan tulis data sensitif (kredensial, harga rahasia) karena ini dikirim sebagai
          konteks ke setiap percakapan.
        </p>
      </div>
    </div>

    <div class="d-flex align-items-center gap-2">
      <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-check" style="font-size:11px"></i> Simpan Pengaturan</button>
      <button type="submit" formaction="<?php echo e(route('admin.settings.livechat.test')); ?>" formnovalidate class="btn btn-outline-secondary btn-sm">
        <i class="fa-solid fa-plug" style="font-size:11px"></i> Coba Sambungkan
      </button>
    </div>
  </form>

  <script <?php echo 'nonce="' . e(app(\App\Support\CspNonce::class)->value()) . '"'; ?>>
    (function () {
      const select = document.getElementById('providerSelect');
      const fProp  = document.getElementById('fieldProperty');
      const fWa    = document.getElementById('fieldWa');
      const lProp  = document.getElementById('labelProperty');
      const hProp  = document.getElementById('hintProperty');

      function sync() {
        const v = select.value;
        fProp.classList.toggle('d-none', v !== 'tawkto' && v !== 'crisp');
        // Nomor WhatsApp dipakai oleh mode 'whatsapp' maupun 'widget'.
        fWa.classList.toggle('d-none', v !== 'whatsapp' && v !== 'widget');
        document.getElementById('hintWidget').classList.toggle('d-none', v !== 'widget');
        document.getElementById('fieldAiBot').classList.toggle('d-none', v !== 'widget');
        document.getElementById('fieldMenu').classList.toggle('d-none', v !== 'widget' && v !== 'whatsapp');

        if (v === 'tawkto') {
          lProp.textContent = 'Tawk.to Property ID / Widget ID';
          hProp.textContent = 'Ambil dari Tawk.to Dashboard » Administration » Chat Widget. Formatnya: propertyId/widgetId';
        } else if (v === 'crisp') {
          lProp.textContent = 'Crisp Website ID';
          hProp.textContent = 'Ambil dari Crisp Settings » Website Settings » Setup Instructions.';
        }
      }

      select.addEventListener('change', sync);
      sync();
    })();

    (function () {
      const provSelect = document.getElementById('aiProviderSelect');
      const fAnthropic = document.getElementById('fieldAnthropic');
      const fOpenAi = document.getElementById('fieldOpenAi');
      if (! provSelect) return;

      function syncProvider() {
        fAnthropic.classList.toggle('d-none', provSelect.value !== 'anthropic');
        fOpenAi.classList.toggle('d-none', provSelect.value !== 'openai');
      }

      provSelect.addEventListener('change', syncProvider);
      syncProvider();
    })();
  </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/workspace/hosting-billing/resources/views/admin/settings/livechat.blade.php ENDPATH**/ ?>