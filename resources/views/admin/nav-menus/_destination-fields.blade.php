@php
  // Satu dropdown "Tujuan" menggantikan tiga pilihan lama (Halaman Bawaan /
  // Halaman Saya / Tautan Bebas). Isinya dari App\Support\LinkTargets.
  $targets  = \App\Support\LinkTargets::groups();
  $selected = (string) old('target', $menu->link_target);
  $known    = collect($targets)->collapse()->has($selected);
@endphp

<div class="mb-3">
  <label class="form-label small fw-medium text-dark" for="navTarget">Tujuan Tautan</label>
  <select name="target" id="navTarget" class="form-select form-select-sm" required>
    <option value="">— Pilih tujuan —</option>
    @if ($selected !== '' && ! $known)
      <option value="{{ $selected }}" selected>(tujuan tidak tersedia lagi) {{ $selected }}</option>
    @endif
    @foreach ($targets as $group => $items)
      <optgroup label="{{ $group }}">
        @foreach ($items as $value => $label)
          <option value="{{ $value }}" @selected($selected === (string) $value)>{{ $label }}</option>
        @endforeach
      </optgroup>
    @endforeach
  </select>
  @error('type') <p class="text-danger mt-1 mb-0" style="font-size:12px">{{ $message }}</p> @enderror
  @error('route_name') <p class="text-danger mt-1 mb-0" style="font-size:12px">{{ $message }}</p> @enderror
  @error('page_id') <p class="text-danger mt-1 mb-0" style="font-size:12px">{{ $message }}</p> @enderror
  @error('target_ref') <p class="text-danger mt-1 mb-0" style="font-size:12px">{{ $message }}</p> @enderror
  <p class="text-muted mt-1 mb-0" style="font-size:11px">Pilih halaman, produk, atau halaman sistem. Untuk alamat lain pilih “Tulis sendiri”.</p>
</div>

<div id="navCustomUrl" class="mb-3 {{ $selected === \App\Support\LinkTargets::CUSTOM ? '' : 'd-none' }}">
  <label class="form-label small fw-medium text-dark" for="navUrl">Alamat Tautan</label>
  <input type="text" name="url" id="navUrl" value="{{ old('url', $menu->url) }}" class="form-control form-control-sm" placeholder="/hosting atau https://wa.me/6281234567890">
  @error('url') <p class="text-danger mt-1 mb-0" style="font-size:12px">{{ $message }}</p> @enderror
  <p class="text-muted mt-1 mb-0" style="font-size:11px">Awali dengan <code>/</code> untuk halaman di situs ini, atau <code>https://</code> untuk situs luar.</p>
</div>

<label class="d-flex align-items-center gap-2 small text-dark mb-3">
  <input type="checkbox" name="open_in_new_tab" value="1" @checked(old('open_in_new_tab', $menu->open_in_new_tab)) class="form-check-input" style="margin-top:0">
  Buka di tab baru
</label>

<script @nonce>
(function () {
  const select = document.getElementById('navTarget');
  const custom = document.getElementById('navCustomUrl');
  const label  = select.form ? select.form.querySelector('input[name="label"]') : null;
  let lastAuto = '';

  function sync() {
    custom.classList.toggle('d-none', select.value !== 'custom');
  }

  // Nama menu masih kosong -> isi otomatis dari nama tujuan yang dipilih.
  function autofill() {
    if (!label || !select.value || select.value === 'custom' || select.value === 'route:#') return;
    if (label.value.trim() !== '' && label.value !== lastAuto) return; // admin sudah mengetik sendiri

    const text = select.options[select.selectedIndex].text.split('›').pop().trim();
    lastAuto = text.slice(0, 50);
    label.value = lastAuto;
  }

  select.addEventListener('change', function () { sync(); autofill(); });
  sync();
})();
</script>
