{{-- Footer tema NamaHost. Isi, urutan, ukuran, dan tata letak diatur dari
     Admin > CMS > Footer (App\Support\FooterConfig). --}}
@php
  $ft = \App\Support\FooterConfig::resolve($footerPages ?? collect());
  $st = $ft['style'];
@endphp
<footer id="publicFooter" @if ($st['footer']) style="{{ $st['footer'] }}" @endif>
  <div class="container">
    @if ($ft['layout'] === 'inline')
      @foreach ($ft['blocks'] as $block)
        @if ($block['key'] === 'brand')
          <div class="mb-3">
            @include('public.partials.footer-brand')
            @if ($ft['tagline'])
              <p class="mb-0 mt-1" style="{{ $st['link'] }}">{{ $ft['tagline'] }}</p>
            @endif
          </div>
        @endif
      @endforeach
      <nav class="d-flex flex-wrap gap-3" aria-label="Footer">
        @foreach ($ft['blocks'] as $block)
          @foreach ($block['links'] as $link)
            <a href="{{ $link['url'] }}" style="{{ $st['link'] }}">{{ $link['label'] }}</a>
          @endforeach
        @endforeach
      </nav>
    @else
      <div class="row {{ $st['gap'] }}">
        @foreach ($ft['blocks'] as $block)
          @if ($block['key'] === 'brand')
            <div class="col-12 col-md-5">
              <div class="mb-2">@include('public.partials.footer-brand')</div>
              @if ($ft['tagline'])
                <p class="mb-0" style="{{ $st['link'] }}">{{ $ft['tagline'] }}</p>
              @endif
            </div>
          @else
            <div class="col-6 col-md">
              <div class="ft-title" style="{{ $st['title'] }}">{{ $block['title'] }}</div>
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

    <hr class="my-4">
    <small style="{{ $st['text'] }}">{{ \App\Models\Setting::get('footer_text') ?: '© ' . date('Y') . ' ' . $siteName . '. Seluruh hak dilindungi.' }}</small>
  </div>
</footer>
