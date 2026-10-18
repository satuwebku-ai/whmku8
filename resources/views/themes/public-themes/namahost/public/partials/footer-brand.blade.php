{{-- Logo/nama di footer NamaHost, mengikuti mode branding header. Terima: $ft, $st, $siteName. --}}
@php $b = $ft['brand']; @endphp
@if ($b['display'] === 'logo_only')
  <img src="{{ route('branding.file', $b['logo']) }}" alt="{{ $siteName }}" style="height:{{ $st['logo'] }}px;width:auto;max-width:240px;object-fit:contain">
@elseif ($b['display'] === 'logo_and_text')
  <span class="d-inline-flex align-items-center gap-2 text-white fw-bold" style="{{ $st['title'] ?: 'font-size:1.25rem' }}">
    @if ($b['icon'])
      <img src="{{ route('branding.file', $b['icon']) }}" alt="" style="height:{{ $st['icon'] }}px;width:{{ $st['icon'] }}px;object-fit:contain;border-radius:.6rem">
    @else
      <span class="brand-mark"><i class="bi bi-hdd-network"></i></span>
    @endif
    <span>{{ $siteName }}</span>
  </span>
@else
  <span class="text-white fw-bold" style="{{ $st['title'] ?: 'font-size:1.25rem' }}">{{ $siteName }}</span>
@endif
