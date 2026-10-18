{{-- Pita "Info pengumuman" berteks berjalan di bagian paling atas area klien.
     Muncul hanya bila ada pengumuman aktif (App\Support\ClientNotice). Berhenti saat
     disorot kursor, dan statis bila perangkat meminta gerakan dikurangi. --}}
@php $tk = \App\Support\ClientNotice::ticker(); @endphp
@if ($tk)
  <div id="clientTicker" data-sig="{{ $tk['signature'] }}" role="region" aria-label="Info pengumuman"
       style="background:{{ $tk['bg'] }};color:#fff;font-size:13px;position:relative;z-index:1035">
    <div class="d-flex align-items-center">
      <span class="flex-shrink-0 d-flex align-items-center gap-2 px-3 py-2 fw-semibold" style="background:rgba(255,255,255,.14)">
        <i class="fa-solid fa-bullhorn" aria-hidden="true"></i><span class="d-none d-sm-inline">Info</span>
      </span>
      <div class="flex-grow-1 overflow-hidden" style="min-width:0">
        <div class="ct-track" style="--ct-dur:{{ $tk['duration'] }}s">
          @foreach ([0, 1] as $copy)
            <span class="ct-group" @if ($copy) aria-hidden="true" @endif>
              @foreach ($tk['items'] as $item)
                <a href="{{ $item['url'] }}" class="ct-item" @if ($copy) tabindex="-1" @endif>
                  <span class="ct-badge">{{ $item['label'] }}</span> {{ $item['title'] }}
                </a>
              @endforeach
            </span>
          @endforeach
        </div>
      </div>
      <button type="button" id="clientTickerClose" aria-label="Tutup info pengumuman"
              class="flex-shrink-0 border-0 bg-transparent text-white px-3 py-2" style="opacity:.8">
        <i class="fa-solid fa-xmark" style="font-size:12px"></i>
      </button>
    </div>
  </div>

  <style>
    #clientTicker .ct-track{display:inline-flex;white-space:nowrap;padding:.5rem 0;animation:ctScroll var(--ct-dur,30s) linear infinite}
    #clientTicker .ct-track:hover{animation-play-state:paused}
    #clientTicker .ct-group{display:inline-flex;gap:2.5rem;padding-right:2.5rem;min-width:100vw}
    #clientTicker .ct-item{color:#fff;text-decoration:none}
    #clientTicker .ct-item:hover{text-decoration:underline}
    #clientTicker .ct-badge{background:rgba(255,255,255,.2);border-radius:999px;padding:1px 8px;font-size:11px;font-weight:600;margin-right:.25rem}
    @keyframes ctScroll{from{transform:translateX(0)}to{transform:translateX(-50%)}}
    @media (prefers-reduced-motion:reduce){
      #clientTicker .ct-track{animation:none;white-space:normal;flex-wrap:wrap}
      #clientTicker .ct-group{min-width:0;flex-wrap:wrap;gap:.25rem 1.5rem}
      #clientTicker .ct-group[aria-hidden]{display:none}
    }
  </style>

  <script @nonce>
    (function () {
      const bar = document.getElementById('clientTicker');
      if (! bar) return;
      const KEY = 'client_ticker_closed';

      try {
        if (sessionStorage.getItem(KEY) === bar.dataset.sig) { bar.remove(); return; }
      } catch (e) { /* tidak krusial */ }

      document.getElementById('clientTickerClose').addEventListener('click', function () {
        try { sessionStorage.setItem(KEY, bar.dataset.sig); } catch (e) { /* tidak krusial */ }
        bar.remove();
      });
    })();
  </script>
@endif
