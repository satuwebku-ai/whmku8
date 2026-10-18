{{-- Logo/nama di footer, mengikuti mode branding header. Terima: $ft, $st, $siteName. --}}
@php $b = $ft['brand']; @endphp
@if ($b['display'] === 'logo_only')
  <img src="{{ route('branding.file', $b['logo']) }}" alt="{{ $siteName }}" style="height:{{ $st['logo'] }}px;width:auto;max-width:260px;object-fit:contain">
@elseif ($b['display'] === 'logo_and_text')
  <span class="d-inline-flex align-items-center gap-2">
    @if ($b['icon'])
      <img src="{{ route('branding.file', $b['icon']) }}" alt="" style="height:{{ $st['icon'] }}px;width:{{ $st['icon'] }}px;object-fit:contain">
    @else
      <span class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width:{{ $st['icon'] }}px;height:{{ $st['icon'] }}px;background:{{ \App\Models\Setting::get('theme_color', '#6366F1') }}">
        <svg viewBox="0 0 24 24" width="{{ (int) round($st['icon'] / 2) }}" height="{{ (int) round($st['icon'] / 2) }}" fill="none" stroke="#fff" stroke-width="2.2"><path d="M13 2 3 14h7l-1 8 11-12h-7l1-8z"/></svg>
      </span>
    @endif
    <span class="fw-bold text-dark" style="{{ $st['title'] ?: 'font-size:1.05rem' }};letter-spacing:-.01em">{{ $siteName }}</span>
  </span>
@else
  <span class="fw-bold text-dark" style="{{ $st['title'] ?: 'font-size:1.05rem' }};letter-spacing:-.01em">{{ $siteName }}</span>
@endif
