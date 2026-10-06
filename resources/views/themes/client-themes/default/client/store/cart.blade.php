@extends('client.layout')
@section('title', 'Keranjang')

@section('content')
  @php
    $isDomain = fn ($t) => in_array($t, ['domain', 'domain_premium'], true);
  @endphp

  <a href="{{ route('client.store') }}" class="text-decoration-none text-muted" style="font-size:12px">&larr; Lanjut belanja</a>
  <h1 class="h4 fw-bold text-dark mt-2 mb-4">Keranjang</h1>

  @if (empty($items))
    <div class="dash-card p-5 text-center">
      <p class="fw-semibold text-dark mb-1">Keranjang Anda masih kosong</p>
      <p class="text-muted mb-3" style="font-size:14px">Pilih layanan dulu, lalu kembali ke sini untuk checkout.</p>
      <a href="{{ route('client.store') }}" class="btn btn-theme mx-auto" style="width:fit-content">Pesan Layanan Baru</a>
    </div>
  @else
    <div class="row g-4">
      <div class="col-12 col-lg-8 d-flex flex-column gap-3">
        @foreach ($items as $item)
          <div class="dash-card p-4 d-flex align-items-start justify-content-between gap-3 flex-wrap">
            <div class="d-flex align-items-start gap-3 min-w-0">
              <span class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width:40px;height:40px;background:rgba(79,70,229,.1);color:var(--lumora-theme)">
                <i class="fa-solid {{ $isDomain($item['type']) ? 'fa-globe' : 'fa-server' }}"></i>
              </span>
              <div class="min-w-0">
                @if ($item['type'] === 'product')
                  <p class="fw-semibold text-dark mb-0">{{ $item['name'] }}</p>
                  <form method="POST" action="{{ route('client.cart.cycle') }}" class="mt-2">
                    @csrf
                    <input type="hidden" name="key" value="{{ $item['key'] }}">
                    <select name="billing_cycle" data-auto-submit class="form-select form-select-sm" style="width:auto">
                      @foreach (\App\Models\Product::CYCLES as $ck => $label)
                        <option value="{{ $ck }}" @selected($item['billing_cycle'] === $ck)>{{ $label }}</option>
                      @endforeach
                    </select>
                  </form>
                  @if (! empty($item['domain_name']))
                    <p class="text-muted mt-2 mb-0" style="font-size:11px"><i class="fa-solid fa-globe" style="font-size:10px"></i> {{ $item['domain_mode'] === 'register' ? 'Daftar baru' : 'Domain sendiri' }}: {{ $item['domain_name'] }}</p>
                  @endif
                  @if (! empty($item['selected_options']))
                    <ul class="list-unstyled mt-2 mb-0 d-flex flex-column gap-1">
                      @foreach ($item['selected_options'] as $opt)
                        <li class="text-muted" style="font-size:11px">+ {{ $opt['name'] }} <span class="text-dark fw-medium">— Rp {{ number_format($opt['price'], 0, ',', '.') }}</span></li>
                      @endforeach
                    </ul>
                  @endif
                @elseif ($item['type'] === 'addon')
                  <p class="fw-semibold text-dark mb-0">{{ $item['name'] }}</p>
                  <p class="text-muted mt-1 mb-0" style="font-size:11px">Lisensi digital{{ ! empty($item['license_ip']) ? ' · IP ' . $item['license_ip'] : '' }}</p>
                  <form method="POST" action="{{ route('client.cart.cycle') }}" class="mt-2">
                    @csrf
                    <input type="hidden" name="key" value="{{ $item['key'] }}">
                    <select name="billing_cycle" data-auto-submit class="form-select form-select-sm" style="width:auto">
                      @foreach (['monthly' => 'Bulanan', 'quarterly' => '3 Bulan', 'semi_annually' => '6 Bulan', 'annually' => 'Tahunan'] as $ck => $label)
                        <option value="{{ $ck }}" @selected($item['billing_cycle'] === $ck)>{{ $label }}</option>
                      @endforeach
                    </select>
                  </form>
                @elseif ($item['type'] === 'domain_premium')
                  <p class="fw-semibold text-dark mb-0">{{ $item['domain_name'] }} <span class="badge badge-soft-warning ms-1" style="font-size:10px">Premium</span></p>
                  <p class="text-muted mt-1 mb-0" style="font-size:11px">Registrasi domain premium, 1 tahun (harga tetap)</p>
                @else
                  <p class="fw-semibold text-dark mb-0">{{ $item['domain_name'] }}</p>
                  <form method="POST" action="{{ route('client.cart.years') }}" class="mt-2">
                    @csrf
                    <input type="hidden" name="key" value="{{ $item['key'] }}">
                    <select name="years" data-auto-submit class="form-select form-select-sm" style="width:auto">
                      @for ($y = 1; $y <= 10; $y++)
                        <option value="{{ $y }}" @selected($item['years'] == $y)>{{ $y }} Tahun</option>
                      @endfor
                    </select>
                  </form>
                @endif
              </div>
            </div>

            <div class="text-end flex-shrink-0">
              <p class="fw-semibold text-dark mb-0">Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
              @if (! empty($item['setup_fee']))
                <p class="text-muted mb-0" style="font-size:11px">+ setup Rp {{ number_format($item['setup_fee'], 0, ',', '.') }}</p>
              @endif
              <form method="POST" action="{{ route('client.cart.remove') }}" class="mt-2">
                @csrf
                <input type="hidden" name="key" value="{{ $item['key'] }}">
                <button type="submit" class="btn btn-link p-0 text-danger" style="font-size:12px;text-decoration:none"><i class="fa-regular fa-trash-can"></i> Hapus</button>
              </form>
            </div>
          </div>
        @endforeach

        <form method="POST" action="{{ route('client.cart.clear') }}"
              data-confirm="Kosongkan seluruh keranjang?" data-confirm-title="Kosongkan Keranjang"
              data-confirm-style="danger" data-confirm-label="Ya, Kosongkan">
          @csrf
          <button type="submit" class="btn btn-link p-0 text-muted" style="font-size:12px;text-decoration:none"><i class="fa-regular fa-trash-can"></i> Kosongkan keranjang</button>
        </form>
      </div>

      <div class="col-12 col-lg-4">
        <div class="dash-card p-4" style="position:sticky;top:5rem">
          <h2 class="small fw-bold text-dark mb-3">Ringkasan</h2>
          <div class="d-flex justify-content-between mb-2" style="font-size:14px">
            <span class="text-muted">Subtotal</span>
            <span class="fw-medium text-dark">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
          </div>
          <p class="text-muted mb-3" style="font-size:11px">Biaya setup (jika ada) dan pajak dihitung saat checkout.</p>
          <a href="{{ route('client.checkout') }}" class="btn btn-theme w-100">Lanjut ke Checkout <i class="fa-solid fa-arrow-right" style="font-size:11px"></i></a>
          <a href="{{ route('client.store') }}" class="btn btn-outline-secondary w-100 mt-2">Tambah layanan lain</a>
        </div>
      </div>
    </div>
  @endif
@endsection
