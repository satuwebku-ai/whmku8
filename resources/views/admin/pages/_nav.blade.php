@php
  // Tab CMS dikelompokkan sesuai alur kerja di sidebar "Situs Publik".
  // 'match' memakai pola route supaya tab tetap menyala di halaman
  // tambah/ubah (mis. admin.blog.edit), bukan cuma di halaman daftar.
  $cmsGroups = [
    'Halaman' => [
      ['label' => 'Halaman Depan', 'route' => 'admin.settings.homepage', 'match' => ['admin.settings.homepage*']],
      ['label' => 'Halaman Statis', 'route' => 'admin.pages', 'match' => ['admin.pages', 'admin.page.*']],
    ],
    'Navigasi' => [
      ['label' => 'Menu Utama', 'route' => 'admin.nav-menus', 'match' => ['admin.nav-menus', 'admin.nav-menu.*']],
      ['label' => 'Submenu / Subnav', 'route' => 'admin.nav-submenus', 'match' => ['admin.nav-submenus', 'admin.nav-submenu.*']],
    ],
    'Promosi' => [
      ['label' => 'Banner Promo', 'route' => 'admin.promo-banners.index', 'match' => ['admin.promo-banners.*']],
      ['label' => 'Banner Popup', 'route' => 'admin.popup-banner.edit', 'match' => ['admin.popup-banner.*']],
      ['label' => 'Pengumuman', 'route' => 'admin.announcements', 'match' => ['admin.announcements', 'admin.announcement.*']],
    ],
    'Artikel' => [
      ['label' => 'Blog', 'route' => 'admin.blog.index', 'match' => ['admin.blog.index', 'admin.blog.create', 'admin.blog.edit', 'admin.blog.store', 'admin.blog.update']],
      ['label' => 'Kategori Blog', 'route' => 'admin.blog.categories', 'match' => ['admin.blog.categories*']],
      ['label' => 'Pusat Bantuan', 'route' => 'admin.knowledge-base.index', 'match' => ['admin.knowledge-base.index', 'admin.knowledge-base.create', 'admin.knowledge-base.edit', 'admin.knowledge-base.store', 'admin.knowledge-base.update']],
      ['label' => 'Kategori Bantuan', 'route' => 'admin.knowledge-base.categories', 'match' => ['admin.knowledge-base.categories*']],
    ],
  ];
@endphp

<div class="d-flex align-items-end gap-4 mb-4 border-bottom flex-wrap">
  @foreach ($cmsGroups as $groupLabel => $tabs)
    <div>
      <div class="text-uppercase text-muted fw-semibold px-3" style="font-size:10px;letter-spacing:.12em">{{ $groupLabel }}</div>
      <div class="d-flex align-items-center gap-1">
        @foreach ($tabs as $tab)
          <a href="{{ route($tab['route']) }}"
             class="px-3 py-2 small fw-medium text-decoration-none border-bottom border-2 {{ request()->routeIs(...$tab['match']) ? 'border-primary text-accent' : 'border-transparent text-muted' }}">
            {{ $tab['label'] }}
          </a>
        @endforeach
      </div>
    </div>
  @endforeach
</div>
