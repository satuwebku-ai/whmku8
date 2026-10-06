@extends('client.layout')
@section('title', $category->name)

@section('content')
  @php
    $unit = ['monthly' => '/bulan', 'quarterly' => '/3 bulan', 'semi_annually' => '/6 bulan', 'annually' => '/tahun'];
  @endphp

  <a href="{{ route('client.store') }}" class="text-decoration-none text-muted" style="font-size:12px">&larr; Semua kategori</a>

  <div class="mt-2 mb-4">
    <h1 class="h4 fw-bold text-dark mb-1">{{ $category->name }}</h1>
    @if ($category->description)
      <p class="text-muted mb-0">{{ $category->description }}</p>
    @endif
  </div>

  @if ($products->isEmpty())
    <div class="dash-card p-5 text-center text-muted" style="font-size:14px">Belum ada paket di kategori ini.</div>
  @else
    <div class="d-flex flex-column gap-3">
      @foreach ($products as $product)
        @php
          $cycles = $product->availableCycles();
          $firstKey = array_key_first($cycles);
          $stock = $product->isInStock();
        @endphp
        <div class="dash-card p-4 d-flex align-items-center justify-content-between gap-3 flex-wrap">
          <div class="min-w-0" style="flex:1 1 280px">
            <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
              <h2 class="fw-semibold text-dark mb-0" style="font-size:16px">{{ $product->name }}</h2>
              @if ($product->is_featured && $stock)<span class="badge badge-soft-success" style="font-size:10px">Unggulan</span>@endif
              @if ($product->isDepositBilled())<span class="badge badge-soft-secondary" style="font-size:10px">Bayar per jam</span>@endif
              @unless ($stock)<span class="badge badge-soft-danger" style="font-size:10px">Stok habis</span>@endunless
            </div>
            @if ($product->tagline)
              <p class="text-muted mb-2" style="font-size:13px">{{ $product->tagline }}</p>
            @endif
            @if ($product->features)
              <p class="text-muted mb-0" style="font-size:12px">
                {{ collect($product->features)->take(4)->implode(' · ') }}
              </p>
            @endif
          </div>

          <div class="text-end flex-shrink-0">
            @if ($product->isDepositBilled() && ($hourly = $product->estimatedHourlyRate()))
              <p class="fw-bold text-dark mb-0" style="font-size:1.2rem">Rp {{ number_format($hourly, 2, ',', '.') }}<span class="text-muted fw-normal" style="font-size:12px"> / jam</span></p>
            @elseif ($product->starting_price !== null)
              <p class="fw-bold text-dark mb-0" style="font-size:1.2rem">Rp {{ number_format($product->starting_price, 0, ',', '.') }}<span class="text-muted fw-normal" style="font-size:12px"> {{ $unit[$firstKey] ?? '' }}</span></p>
            @else
              <p class="text-danger mb-0" style="font-size:13px">Harga belum tersedia</p>
            @endif
            <a href="{{ route('client.store.product', [$category->slug, $product->slug]) }}"
               class="btn {{ $stock ? 'btn-theme' : 'btn-outline-secondary' }} btn-sm mt-2">
              {{ $stock ? 'Pilih paket' : 'Lihat detail' }}
            </a>
          </div>
        </div>
      @endforeach
    </div>
  @endif
@endsection
