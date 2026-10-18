@extends('layouts.admin')
@section('title', 'Pengaturan Footer')
@section('content')
  @include('admin.pages._nav')

  @php use App\Models\Setting; use App\Support\FooterConfig; @endphp

  <div class="mb-4 d-flex align-items-center justify-content-between flex-wrap gap-2">
    <div>
      <h1 class="h4 fw-bold text-dark mb-1">Pengaturan Footer</h1>
      <p class="small text-muted mb-0">Atur ukuran, tata letak, serta urutan dan tampil/sembunyi menu di footer situs publik.</p>
    </div>
    <a href="{{ route('home') }}#publicFooter" target="_blank" class="btn btn-outline-secondary btn-sm">
      <i class="fa-solid fa-arrow-up-right-from-square" style="font-size:11px"></i> Lihat Footer
    </a>
  </div>

  <form method="POST" action="{{ route('admin.settings.footer.update') }}" class="card border rounded-4 p-4" style="max-width:42rem">
    @csrf

    <h2 class="small fw-bold text-dark mb-2">Ukuran Footer</h2>
    <div class="d-flex flex-wrap gap-2 mb-4">
      @foreach (FooterConfig::SIZES as $value => $label)
        <label class="d-flex align-items-center gap-2 rounded-3 border px-3 py-2 bg-white" style="cursor:pointer;font-size:13px">
          <input type="radio" class="form-check-input m-0" name="footer_size" value="{{ $value }}" @checked(old('footer_size', $size) === $value)>
          {{ $label }}
        </label>
      @endforeach
      <p class="text-muted w-100 mb-0" style="font-size:11px">Mengubah jarak, ukuran huruf judul, dan ukuran tautan. "Sedang" memakai tampilan bawaan tema.</p>
    </div>

    <h2 class="small fw-bold text-dark mb-2">Tata Letak</h2>
    <div class="d-flex flex-wrap gap-2 mb-4">
      @foreach (FooterConfig::LAYOUTS as $value => $label)
        <label class="d-flex align-items-center gap-2 rounded-3 border px-3 py-2 bg-white" style="cursor:pointer;font-size:13px">
          <input type="radio" class="form-check-input m-0" name="footer_layout" value="{{ $value }}" @checked(old('footer_layout', $layout) === $value)>
          {{ $label }}
        </label>
      @endforeach
      <p class="text-muted w-100 mb-0" style="font-size:11px">Kolom: tiap blok menjadi satu kolom berjudul. Satu baris: semua tautan berderet dalam satu baris ringkas.</p>
    </div>

    <h2 class="small fw-bold text-dark mb-1">Susunan Menu Footer</h2>
    <p class="text-muted mb-3" style="font-size:12px">
      Seret <i class="fa-solid fa-grip-vertical" style="font-size:10px"></i> atau pakai tombol panah untuk mengubah urutan (kiri ke kanan di footer),
      matikan sakelar untuk menyembunyikan. Blok tautan yang isinya kosong otomatis tidak tampil.
      <b>Klik "Simpan Footer" agar perubahan tampil di situs.</b>
    </p>

    <input type="hidden" name="footer_order" id="footerOrder" value="{{ implode(',', $order) }}">

    <div id="footerList" class="d-flex flex-column gap-2 mb-4">
      @foreach ($order as $key)
        @php $meta = $blocks[$key]; @endphp
        <div class="d-flex align-items-center gap-2 rounded-3 border px-3 py-2 bg-white" draggable="true" data-key="{{ $key }}">
          <span class="text-muted" style="cursor:grab;font-size:12px"><i class="fa-solid fa-grip-vertical"></i></span>
          <span class="badge badge-soft-secondary" style="font-size:10px;min-width:1.5rem" data-pos>{{ $loop->iteration }}</span>
          <span class="flex-grow-1 min-w-0">
            <span class="d-block fw-medium text-dark" style="font-size:13px">{{ $meta['label'] }}</span>
            <span class="d-block text-muted" style="font-size:11px">{{ $meta['desc'] }}</span>
          </span>
          <div class="btn-group btn-group-sm flex-shrink-0" role="group" aria-label="Pindah posisi">
            <button type="button" class="btn btn-outline-secondary py-0 px-2" data-move="-1" aria-label="Naik"><i class="fa-solid fa-chevron-up" style="font-size:10px"></i></button>
            <button type="button" class="btn btn-outline-secondary py-0 px-2" data-move="1" aria-label="Turun"><i class="fa-solid fa-chevron-down" style="font-size:10px"></i></button>
          </div>
          <div class="form-check form-switch m-0">
            <input type="checkbox" role="switch" class="form-check-input" name="footer_show_{{ $key }}" value="1"
                   @checked(Setting::get('footer_show_' . $key, '1') === '1')>
          </div>
        </div>
      @endforeach
    </div>

    <p class="text-muted mb-3" style="font-size:11px">
      Teks hak cipta di bagian bawah footer diubah di Pengaturan Umum (kolom Teks Footer). Halaman di blok "Informasi"
      diatur lewat centang "Tampilkan link di footer" pada tiap halaman.
    </p>

    <button type="submit" class="btn btn-primary btn-sm" style="width:fit-content"><i class="fa-solid fa-check" style="font-size:11px"></i> Simpan Footer</button>
  </form>

  <script @nonce>
    (function () {
      const list = document.getElementById('footerList');
      const orderInput = document.getElementById('footerOrder');

      if (! list || ! orderInput) return;

      let dragged = null;

      function sync() {
        const keys = Array.from(list.children).map(function (el, i) {
          const pos = el.querySelector('[data-pos]');
          if (pos) pos.textContent = i + 1;
          return el.dataset.key;
        });
        orderInput.value = keys.join(',');
      }

      list.querySelectorAll('[data-move]').forEach(function (btn) {
        btn.addEventListener('click', function () {
          const row = btn.closest('[data-key]');
          const dir = parseInt(btn.dataset.move, 10);

          if (dir < 0 && row.previousElementSibling) {
            list.insertBefore(row, row.previousElementSibling);
          } else if (dir > 0 && row.nextElementSibling) {
            list.insertBefore(row.nextElementSibling, row);
          }

          sync();
        });
      });

      const form = list.closest('form');
      if (form) form.addEventListener('submit', sync);

      list.querySelectorAll('[draggable="true"]').forEach(function (row) {
        row.addEventListener('dragstart', function () { dragged = row; row.style.opacity = '.4'; });
        row.addEventListener('dragend', function () { row.style.opacity = ''; dragged = null; sync(); });
        row.addEventListener('dragover', function (e) {
          e.preventDefault();
          if (! dragged || dragged === row) return;
          const box = row.getBoundingClientRect();
          const after = (e.clientY - box.top) > (box.height / 2);
          list.insertBefore(dragged, after ? row.nextSibling : row);
        });
      });

      sync();
    })();
  </script>
@endsection
