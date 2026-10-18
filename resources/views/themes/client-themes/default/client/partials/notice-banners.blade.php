{{-- Banner promo di dashboard klien (Banner Promo dengan tujuan "Area Klien"). --}}
@php $banners = \App\Support\ClientNotice::banners(); @endphp
@if ($banners->isNotEmpty())
  <div id="clientBannerCarousel" class="position-relative rounded-4 overflow-hidden mb-4">
    @foreach ($banners as $i => $banner)
      @php $bannerTitle = trim((string) $banner->title) === '-' ? '' : $banner->title; @endphp
      <div class="cb-slide {{ $i === 0 ? '' : 'd-none' }}" data-slide="{{ $i }}">
        @if ($banner->link_url)
          <a href="{{ $banner->link_url }}" @if ($banner->open_in_new_tab) target="_blank" rel="noopener noreferrer" @endif class="d-block position-relative">
        @else
          <div class="position-relative">
        @endif
          <img src="{{ route('banner.file', $banner->image) }}" alt="{{ $bannerTitle ?: 'Banner' }}" class="w-100" style="display:block;height:auto">
          @if ($bannerTitle || $banner->subtitle || $banner->button_text)
            <div class="position-absolute top-0 start-0 end-0 bottom-0 d-flex align-items-center" style="background:linear-gradient(to right, rgba(0,0,0,.6), rgba(0,0,0,.2) 60%, transparent)">
              <div class="px-4" style="max-width:32rem">
                @if ($bannerTitle)<h2 class="text-white fw-bold mb-1" style="font-size:1.25rem">{{ $bannerTitle }}</h2>@endif
                @if ($banner->subtitle)<p class="text-white mb-2" style="opacity:.85;font-size:14px">{{ $banner->subtitle }}</p>@endif
                @if ($banner->button_text)<span class="btn btn-light btn-sm">{{ $banner->button_text }}</span>@endif
              </div>
            </div>
          @endif
        @if ($banner->link_url) </a> @else </div> @endif
      </div>
    @endforeach

    @if ($banners->count() > 1)
      <div class="position-absolute d-flex gap-2" style="bottom:10px;left:50%;transform:translateX(-50%)">
        @foreach ($banners as $i => $banner)
          <button type="button" class="cb-dot rounded-circle border-0 p-0" aria-label="Banner {{ $i + 1 }}" data-dot="{{ $i }}"
                  style="width:8px;height:8px;background:{{ $i === 0 ? '#fff' : 'rgba(255,255,255,.45)' }}"></button>
        @endforeach
      </div>
    @endif
  </div>

  @if ($banners->count() > 1)
    <script @nonce>
      (function () {
        const slides = document.querySelectorAll('#clientBannerCarousel .cb-slide');
        const dots = document.querySelectorAll('#clientBannerCarousel .cb-dot');
        let current = 0;

        function show(index) {
          slides.forEach(function (s, i) { s.classList.toggle('d-none', i !== index); });
          dots.forEach(function (d, i) { d.style.background = i === index ? '#fff' : 'rgba(255,255,255,.45)'; });
          current = index;
        }

        dots.forEach(function (dot) { dot.addEventListener('click', function () { show(parseInt(dot.dataset.dot, 10)); }); });
        setInterval(function () { show((current + 1) % slides.length); }, 5000);
      })();
    </script>
  @endif
@endif
