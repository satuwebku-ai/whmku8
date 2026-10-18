{{-- Modal pengumuman di dashboard klien: pengumuman teratas ditampilkan sekali per
     pengumuman per browser. Jendela dibuat tanpa Bootstrap JS supaya aman di semua tema. --}}
@if (request()->routeIs('client.dashboard', 'client.dashboard.alt'))
  @php
    $ann = \App\Support\ClientNotice::modal();
    $annLabel = $ann ? (\App\Support\ClientNotice::CATEGORY_LABELS[$ann->category] ?? 'Info') : null;
  @endphp
  @if ($ann)
    <div id="clientAnnModal" class="d-none position-fixed top-0 start-0 end-0 bottom-0 align-items-center justify-content-center p-3"
         style="background:rgba(15,23,42,.65);z-index:1090" role="dialog" aria-modal="true" aria-labelledby="clientAnnTitle" data-id="{{ $ann->id }}">
      <div class="bg-white rounded-4 shadow position-relative w-100 overflow-hidden" style="max-width:32rem">
        <button type="button" id="clientAnnClose" aria-label="Tutup"
                class="position-absolute d-flex align-items-center justify-content-center border-0 rounded-circle"
                style="top:12px;right:12px;width:32px;height:32px;background:#f1f5f9;color:#475569">
          <i class="fa-solid fa-xmark" style="font-size:13px"></i>
        </button>
        <div class="p-4">
          <div class="d-flex align-items-center gap-2 mb-2">
            <span class="badge rounded-pill" style="background:#eef2ff;color:#4338ca;font-size:11px">{{ $annLabel }}</span>
            <span class="text-muted" style="font-size:12px">{{ ($ann->published_at ?? $ann->created_at)->translatedFormat('d M Y') }}</span>
          </div>
          <h2 id="clientAnnTitle" class="fw-bold mb-2 pe-4" style="font-size:1.15rem;color:#0f172a">{{ $ann->title }}</h2>
          <p class="mb-4" style="font-size:14px;color:#475569;line-height:1.6">
            {{ $ann->excerpt ?: \Illuminate\Support\Str::limit(strip_tags((string) $ann->content), 220) }}
          </p>
          <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('announcements.show', $ann->slug) }}" class="btn btn-primary btn-sm">Baca selengkapnya</a>
            <button type="button" id="clientAnnDismiss" class="btn btn-outline-secondary btn-sm">Tutup</button>
          </div>
        </div>
      </div>
    </div>

    <script @nonce>
      (function () {
        const overlay = document.getElementById('clientAnnModal');
        if (! overlay) return;
        const KEY = 'client_ann_modal_seen';
        const id = overlay.dataset.id;

        try { if (localStorage.getItem(KEY) === id) return; } catch (e) { /* tampilkan saja */ }

        function close() {
          overlay.classList.add('d-none');
          overlay.classList.remove('d-flex');
          try { localStorage.setItem(KEY, id); } catch (e) { /* tidak krusial */ }
        }

        setTimeout(function () { overlay.classList.remove('d-none'); overlay.classList.add('d-flex'); }, 600);

        document.getElementById('clientAnnClose').addEventListener('click', close);
        document.getElementById('clientAnnDismiss').addEventListener('click', close);
        overlay.addEventListener('click', function (e) { if (e.target === overlay) close(); });
        document.addEventListener('keydown', function (e) {
          if (e.key === 'Escape' && ! overlay.classList.contains('d-none')) close();
        });
      })();
    </script>
  @endif
@endif
