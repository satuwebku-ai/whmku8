@extends('client.layout')
@section('title', 'Pesan Layanan Baru')

@section('content')
  @php
    $chips = ['' => 'Semua', 'hosting' => 'Hosting', 'vps' => 'VPS / Cloud'];
  @endphp

  <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
    <div>
      <h1 class="h4 fw-bold text-dark mb-1">Pesan Layanan Baru</h1>
      <p class="text-muted mb-0">Pilih kategori layanan, lalu lanjutkan ke paket yang cocok.</p>
    </div>
    <a href="{{ route('client.cart') }}" class="btn btn-outline-secondary btn-sm">
      <i class="fa-solid fa-cart-shopping" style="font-size:11px"></i> Keranjang
    </a>
  </div>

  <form method="GET" class="d-flex align-items-center gap-2 flex-wrap mb-4">
    <input type="text" name="q" value="{{ $search }}" placeholder="Cari kategori atau paket..." class="form-control form-control-sm" style="max-width:20rem;flex:1 1 200px">
    @if ($type)<input type="hidden" name="type" value="{{ $type }}">@endif
    <button type="submit" class="btn btn-outline-secondary btn-sm">Cari</button>
    <div class="d-flex align-items-center gap-1 ms-md-2">
      @foreach ($chips as $key => $label)
        <a href="{{ route('client.store', array_filter(['type' => $key ?: null, 'q' => $search ?: null])) }}"
           class="px-3 py-1 rounded-pill small fw-medium text-decoration-none {{ ($type ?? '') === $key ? 'text-white' : 'text-muted' }}"
           style="{{ ($type ?? '') === $key ? 'background:var(--lumora-theme)' : 'background:#f1f5f9' }}">{{ $label }}</a>
      @endforeach
    </div>
  </form>

  @if ($categories->isEmpty())
    <div class="dash-card p-5 text-center">
      <p class="fw-semibold text-dark mb-1">Belum ada layanan yang cocok</p>
      <p class="text-muted mb-0" style="font-size:14px">Coba kata kunci lain atau tampilkan semua kategori.</p>
    </div>
  @else
    <div class="row g-3">
      @foreach ($categories as $category)
        @php
          $from = $category->products->map->starting_price->filter(fn ($p) => $p !== null)->min();
        @endphp
        <div class="col-sm-6 col-xl-4">
          <a href="{{ route('client.store.category', $category->slug) }}" class="dash-card dash-card-hover p-4 d-block h-100 text-decoration-none">
            <div class="d-flex align-items-start justify-content-between mb-3">
              <span class="rounded-3 d-flex align-items-center justify-content-center" style="width:44px;height:44px;background:rgba(79,70,229,.1);color:var(--lumora-theme)">
                <i class="fa-solid {{ $category->icon ?: 'fa-box' }}"></i>
              </span>
              <span class="badge {{ $category->type === 'vps' ? 'badge-soft-success' : 'badge-soft-secondary' }}" style="font-size:10px">{{ $category->type === 'vps' ? 'VPS' : 'Hosting' }}</span>
            </div>
            <h2 class="fw-semibold text-dark mb-1" style="font-size:15px">{{ $category->name }}</h2>
            @if ($category->description)
              <p class="text-muted mb-3" style="font-size:13px">{{ $category->description }}</p>
            @endif
            <div class="d-flex align-items-center justify-content-between pt-3 border-top mt-auto">
              <span class="text-muted" style="font-size:12px">{{ $category->products->count() }} paket</span>
              @if ($from)
                <span class="fw-semibold text-dark" style="font-size:13px">mulai Rp {{ number_format($from, 0, ',', '.') }}</span>
              @endif
            </div>
          </a>
        </div>
      @endforeach
    </div>
  @endif
@endsection
