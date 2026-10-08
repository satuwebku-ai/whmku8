{{--
  Pembungkus halaman toko (katalog, domain, lisensi, keranjang).

  View toko meng-extend layout ini, bukan layout publik langsung, dan
  menaruh isinya di @section('store-content') (atau @section('full-width')
  untuk halaman yang memakai latar penuh). Layout induknya dipilih otomatis
  oleh ThemeRegistry::storeLayout():
    - klien login + tema Publik = tema Client -> layout portal client
      (sidebar + menu); isi dibatasi lebarnya, section full-width dibungkus
      kartu membulat karena layout client tidak punya area full-bleed.
    - selain itu (tamu / tema berbeda)        -> layout publik apa adanya.
--}}
@php $storeLayout = \App\Support\ThemeRegistry::storeLayout(); @endphp
@extends($storeLayout)

@if ($storeLayout === 'client.layout')
  @section('title', $seoTitle ?? 'Toko')

  @section('content')
    <div class="mx-auto" style="max-width:72rem">
      @hasSection('full-width')
        <div class="rounded-4 overflow-hidden">
          @yield('full-width')
        </div>
      @else
        @yield('store-content')
      @endif
    </div>
  @endsection
@else
  @section('content')
    @yield('store-content')
  @endsection
@endif
