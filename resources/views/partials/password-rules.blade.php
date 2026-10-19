{{-- Checklist syarat password (real-time). Pakai: @include('partials.password-rules', ['target' => 'password']) --}}
@php $prId = 'pr-' . ($target ?? 'password'); @endphp
<ul id="{{ $prId }}" class="pr-list" style="list-style:none;padding:0;margin:8px 0 0;font-size:12.5px">
  <li data-rule="length"><span class="pr-icon"></span> Minimal 8 karakter</li>
  <li data-rule="letters"><span class="pr-icon"></span> Mengandung huruf</li>
  <li data-rule="numbers"><span class="pr-icon"></span> Mengandung angka</li>
</ul>

<style>
  .pr-list li{display:flex;align-items:center;gap:8px;margin-bottom:3px;color:#94a3b8;transition:color .15s}
  .pr-list .pr-icon{display:inline-flex;align-items:center;justify-content:center;width:16px;height:16px;
    border-radius:50%;border:1.5px solid #cbd5e1;font-size:10px;font-weight:700;line-height:1;color:#fff;transition:all .15s}
  .pr-list li.ok{color:#059669}
  .pr-list li.ok .pr-icon{background:#10b981;border-color:#10b981}
  .pr-list li.ok .pr-icon::before{content:"\2713"}
</style>

<script @nonce>
  (function () {
    var input = document.getElementById(@json($target ?? 'password'));
    var list  = document.getElementById(@json($prId));
    if (!input || !list) return;

    var rules = {
      length:  function (v) { return v.length >= 8; },
      letters: function (v) { return /[A-Za-z]/.test(v); },
      numbers: function (v) { return /[0-9]/.test(v); }
    };

    function check() {
      Object.keys(rules).forEach(function (key) {
        var li = list.querySelector('[data-rule="' + key + '"]');
        if (li) li.classList.toggle('ok', rules[key](input.value));
      });
    }

    input.addEventListener('input', check);
    check();
  })();
</script>
