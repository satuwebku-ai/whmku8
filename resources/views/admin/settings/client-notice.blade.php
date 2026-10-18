@extends('layouts.admin')
@section('title', 'Pengumuman Area Klien')
@section('content')
  @include('admin.pages._nav')

  @php use App\Support\ClientNotice; @endphp

  <div class="mb-4">
    <h1 class="h4 fw-bold text-dark mb-1">Pengumuman di Area Klien</h1>
    <p class="small text-muted mb-0">
      Atur pita teks berjalan di atas halaman, modal pengumuman di dashboard, dan banner promo untuk klien yang sudah login.
      Isi pengumumannya dikelola di tab <a href="{{ route('admin.announcements') }}">Pengumuman</a>.
    </p>
  </div>

  <form method="POST" action="{{ route('admin.settings.client-notice.update') }}" class="card border rounded-4 p-4" style="max-width:42rem">
    @csrf

    <div class="d-flex flex-column gap-3 mb-4">
      <label class="d-flex align-items-start gap-3 rounded-3 border px-3 py-3 bg-white" style="cursor:pointer">
        <span class="form-check form-switch m-0 pt-1">
          <input type="checkbox" role="switch" class="form-check-input" name="client_ticker_enabled" value="1" @checked(old('client_ticker_enabled', $ticker))>
        </span>
        <span>
          <span class="d-block fw-medium text-dark" style="font-size:13px">Teks berjalan "Info pengumuman" di bagian atas</span>
          <span class="d-block text-muted" style="font-size:11px">Muncul di semua halaman area klien selama ada pengumuman aktif. Klien dapat menutupnya untuk sesi itu.</span>
        </span>
      </label>

      <label class="d-flex align-items-start gap-3 rounded-3 border px-3 py-3 bg-white" style="cursor:pointer">
        <span class="form-check form-switch m-0 pt-1">
          <input type="checkbox" role="switch" class="form-check-input" name="client_modal_enabled" value="1" @checked(old('client_modal_enabled', $modal))>
        </span>
        <span>
          <span class="d-block fw-medium text-dark" style="font-size:13px">Modal pengumuman di dashboard</span>
          <span class="d-block text-muted" style="font-size:11px">Menampilkan pengumuman teratas (yang disematkan lebih dulu). Hanya sekali per pengumuman di tiap browser.</span>
        </span>
      </label>

      <label class="d-flex align-items-start gap-3 rounded-3 border px-3 py-3 bg-white" style="cursor:pointer">
        <span class="form-check form-switch m-0 pt-1">
          <input type="checkbox" role="switch" class="form-check-input" name="client_banner_enabled" value="1" @checked(old('client_banner_enabled', $banner))>
        </span>
        <span>
          <span class="d-block fw-medium text-dark" style="font-size:13px">Banner di dashboard</span>
          <span class="d-block text-muted" style="font-size:11px">
            Memakai <a href="{{ route('admin.promo-banners.index') }}">Banner Promo</a> yang tujuan tampilnya "Area Klien" (banner "Semua Halaman" tidak ikut).
            Saat ini {{ $bannerCount }} banner aktif.
          </span>
        </span>
      </label>
    </div>

    <h2 class="small fw-bold text-dark mb-2">Kecepatan Teks Berjalan</h2>
    <div class="d-flex flex-wrap gap-2 mb-4">
      @foreach (ClientNotice::SPEEDS as $value => $label)
        <label class="d-flex align-items-center gap-2 rounded-3 border px-3 py-2 bg-white" style="cursor:pointer;font-size:13px">
          <input type="radio" class="form-check-input m-0" name="client_ticker_speed" value="{{ $value }}" @checked(old('client_ticker_speed', $speed) === $value)>
          {{ $label }}
        </label>
      @endforeach
    </div>

    <h2 class="small fw-bold text-dark mb-2">Batas Usia Pengumuman</h2>
    <div class="d-flex align-items-center gap-2 mb-1">
      <input type="number" name="client_notice_days" min="0" max="365" class="form-control form-control-sm" style="width:6rem"
             value="{{ old('client_notice_days', $days) }}">
      <span class="small text-muted">hari terakhir</span>
    </div>
    <p class="text-muted mb-4" style="font-size:11px">
      Hanya pengumuman yang terbit dalam rentang ini yang tampil (yang disematkan selalu tampil). Isi 0 untuk menampilkan semua.
      Saat ini ada {{ $activeCount }} pengumuman yang memenuhi syarat.
    </p>

    <button type="submit" class="btn btn-primary btn-sm" style="width:fit-content"><i class="fa-solid fa-check" style="font-size:11px"></i> Simpan</button>
  </form>
@endsection
