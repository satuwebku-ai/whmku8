{{-- Footer tema Default & Modern (Modern memakai file ini lewat fallback tema).
     Isi, urutan, ukuran, dan tata letak diatur dari Admin > CMS > Footer
     (App\Support\FooterConfig). --}}
@php
  $ft = \App\Support\FooterConfig::resolve($footerPages ?? collect());
  $st = $ft['style'];
@endphp
<footer id="publicFooter" @if ($st['footer']) style="{{ $st['footer'] }}" @endif>
  <div class="container" style="max-width:72rem">
    @if ($ft['layout'] === 'inline')
      @foreach ($ft['blocks'] as $block)
        @if ($block['key'] === 'brand')
          <div class="mb-3">
            @include('public.partials.footer-brand')
            @if ($ft['tagline'])
              <p class="text-muted mb-0" style="{{ $st['link'] }}">{{ $ft['tagline'] }}</p>
            @endif
          </div>
        @endif
      @endforeach
      <nav class="d-flex flex-wrap gap-3 mb-3" aria-label="Footer">
        @foreach ($ft['blocks'] as $block)
          @foreach ($block['links'] as $link)
            <a href="{{ $link['url'] }}" style="{{ $st['link'] }}">{{ $link['label'] }}</a>
          @endforeach
        @endforeach
      </nav>
    @else
      <div class="row {{ $st['gap'] }} mb-4">
        @foreach ($ft['blocks'] as $block)
          @if ($block['key'] === 'brand')
            <div class="col-12 col-lg-4">
              <div class="mb-2">@include('public.partials.footer-brand')</div>
              @if ($ft['tagline'])
                <p class="text-muted mb-0" style="{{ $st['link'] }}">{{ $ft['tagline'] }}</p>
              @endif
            </div>
          @else
            <div class="col-6 col-lg">
              <div class="fw-bold text-dark mb-2" style="{{ $st['title'] }}">{{ $block['title'] }}</div>
              <ul class="list-unstyled mb-0">
                @foreach ($block['links'] as $link)
                  <li style="{{ $st['item'] }}"><a href="{{ $link['url'] }}" style="{{ $st['link'] }}">{{ $link['label'] }}</a></li>
                @endforeach
              </ul>
            </div>
          @endif
        @endforeach
      </div>
    @endif

    <p class="text-muted mb-0" style="font-size:14px;{{ $st['text'] }}">{{ \App\Models\Setting::get('footer_text') ?: '© ' . date('Y') . ' ' . $siteName . '. Semua hak dilindungi.' }}</p>
  </div>
</footer>
