@php
    use App\Models\Setting;

    $siteName = Setting::get('site_name', config('app.name', 'Lumora Hosting'));
    $siteLogo = Setting::get('site_logo');
    $tagline = Setting::get('site_tagline', 'Hosting and domains, made refreshingly simple.');
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $siteName }} — Hosting &amp; Domains</title>
    <meta name="description" content="{{ $tagline }}">
    <link rel="stylesheet" href="{{ asset('assets/css/vendor/bootstrap-5.3.8.min.css') }}">
    <style>
        :root {
            color-scheme: light;
            --ink: #112b3b;
            --ink-deep: #082230;
            --ink-soft: #456071;
            --orange: #ed7138;
            --orange-dark: #d95e27;
            --paper: #fff;
            --mist: #f3f6f8;
            --line: #dce5ea;
            --muted: #70828d;
            --shadow: 0 16px 42px rgba(15, 43, 59, .07);
        }
        * { box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body {
            margin: 0;
            background: var(--paper);
            color: var(--ink);
            font-family: "Aptos", "Segoe UI", system-ui, -apple-system, sans-serif;
            -webkit-font-smoothing: antialiased;
        }
        a { color: inherit; }
        .store-shell { min-height: 100vh; display: flex; flex-direction: column; }
        .topline { height: 4px; background: var(--orange); }
        .site-header { background: var(--ink-deep); color: #fff; }
        .header-inner {
            max-width: 1180px; min-height: 76px; padding: 0 28px; margin: 0 auto;
            display: flex; align-items: center; justify-content: space-between; gap: 28px;
        }
        .brand {
            display: inline-flex; align-items: center; gap: 12px; min-width: 0;
            text-decoration: none; color: #fff;
        }
        .brand img { display: block; width: auto; max-width: 190px; max-height: 42px; object-fit: contain; }
        .brand-mark {
            width: 35px; height: 35px; display: grid; place-items: center; flex: 0 0 auto;
            border-radius: 10px; background: var(--orange); color: white;
        }
        .brand-mark svg { width: 20px; height: 20px; }
        .brand-name { color: white; font-size: 1.04rem; font-weight: 760; letter-spacing: -.035em; white-space: nowrap; }
        .main-nav { display: flex; align-items: center; gap: 30px; margin-left: auto; }
        .main-nav a {
            color: #c4d1d8; font-size: .88rem; text-decoration: none; transition: color .18s ease;
        }
        .main-nav a:hover, .main-nav a[aria-current="page"] { color: #fff; }
        .header-actions { display: flex; align-items: center; gap: 10px; }
        .header-actions a {
            display: inline-flex; align-items: center; justify-content: center; gap: 8px;
            min-height: 38px; border-radius: 7px; padding: 0 14px; font-size: .85rem;
            font-weight: 650; text-decoration: none; transition: transform .18s ease, background .18s ease;
        }
        .header-actions a:hover { transform: translateY(-1px); }
        .login-link { border: 1px solid rgba(255,255,255,.22); color: #fff; }
        .login-link:hover { background: rgba(255,255,255,.08); color: #fff; }
        .cart-link { background: var(--orange); color: white; }
        .cart-link:hover { background: var(--orange-dark); color: white; }
        .header-actions svg { width: 16px; height: 16px; }
        .mobile-nav { display: none; }

        .hero {
            position: relative; overflow: hidden; padding: 71px 24px 65px; text-align: center;
            background:
                radial-gradient(ellipse at 50% 0%, rgba(230,239,244,.7), transparent 54%),
                #fff;
        }
        .hero:after {
            content: ""; position: absolute; left: 50%; bottom: -108px; width: 760px; height: 190px;
            border: 1px solid rgba(17,43,59,.045); border-radius: 50%; transform: translateX(-50%);
            pointer-events: none;
        }
        .hero-content { position: relative; z-index: 1; width: min(100%, 760px); margin: 0 auto; }
        .eyebrow {
            display: inline-flex; align-items: center; gap: 8px; margin-bottom: 14px;
            color: var(--orange-dark); font-size: .72rem; font-weight: 800; letter-spacing: .13em;
            text-transform: uppercase;
        }
        .eyebrow:before { content: ""; width: 19px; height: 2px; background: currentColor; }
        .hero h1 {
            margin: 0; color: var(--ink); font-size: clamp(2rem, 4.1vw, 3.25rem);
            line-height: 1.08; font-weight: 760; letter-spacing: -.055em;
        }
        .hero-copy { max-width: 570px; margin: 15px auto 27px; color: var(--muted); font-size: .98rem; line-height: 1.7; }
        .domain-form {
            display: flex; max-width: 660px; margin: 0 auto; padding: 5px; gap: 5px;
            border: 1px solid #d8e2e8; border-radius: 10px; background: #fff;
            box-shadow: 0 9px 28px rgba(20, 51, 68, .08);
        }
        .domain-input-wrap { display: flex; align-items: center; gap: 11px; flex: 1; min-width: 0; padding: 0 14px; }
        .domain-input-wrap svg { width: 18px; height: 18px; color: #8a9ba5; flex: 0 0 auto; }
        .domain-form input {
            width: 100%; min-width: 0; height: 47px; border: 0; outline: 0; color: var(--ink);
            background: transparent; font: inherit; font-size: .94rem;
        }
        .domain-form input::placeholder { color: #91a0a8; }
        .domain-form button {
            display: inline-flex; align-items: center; justify-content: center; gap: 9px;
            min-height: 47px; border: 0; border-radius: 7px; padding: 0 22px;
            background: var(--orange); color: #fff; font: inherit; font-size: .88rem;
            font-weight: 720; white-space: nowrap; cursor: pointer; transition: background .18s ease;
        }
        .domain-form button:hover { background: var(--orange-dark); }
        .domain-form button svg { width: 15px; height: 15px; }
        .hero-note { margin: 13px 0 0; color: #7b8b94; font-size: .76rem; }

        .catalog-section { flex: 1; padding: 58px 24px 76px; background: var(--mist); }
        .section-inner { max-width: 1050px; margin: 0 auto; }
        .section-heading { margin: 0 auto 29px; text-align: center; }
        .section-heading h2 {
            margin: 0; color: var(--ink); font-size: clamp(1.55rem, 2.4vw, 2rem);
            font-weight: 740; letter-spacing: -.04em;
        }
        .section-heading p { margin: 9px 0 0; color: var(--muted); font-size: .88rem; line-height: 1.65; }
        .category-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 20px; }
        .category-grid > .category-card:last-child:nth-child(3n + 1) { grid-column: 2; }
        .category-card {
            display: flex; flex-direction: column; overflow: hidden; min-height: 222px;
            border: 1px solid #dfe7eb; border-radius: 10px; background: #fff;
            box-shadow: 0 3px 10px rgba(15,43,59,.025);
            transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
        }
        .category-card:hover { transform: translateY(-3px); border-color: #cad8df; box-shadow: var(--shadow); }
        .category-top {
            display: flex; align-items: center; gap: 12px; min-height: 66px; padding: 14px 18px;
            background: var(--ink); color: #fff;
        }
        .category-icon {
            display: grid; place-items: center; width: 34px; height: 34px; flex: 0 0 auto;
            border: 1px solid rgba(255,255,255,.2); border-radius: 9px;
            background: rgba(255,255,255,.08); color: #f39466;
        }
        .category-icon svg { width: 18px; height: 18px; }
        .category-top h3 { margin: 0; color: #fff; font-size: .97rem; font-weight: 700; letter-spacing: -.02em; }
        .category-body { display: flex; flex: 1; flex-direction: column; align-items: flex-start; padding: 16px 18px 18px; }
        .category-description {
            display: -webkit-box; overflow: hidden; margin: 0; color: #687b86; font-size: .82rem;
            line-height: 1.6; -webkit-box-orient: vertical; -webkit-line-clamp: 3;
        }
        .category-meta { margin-top: 10px; color: #8998a0; font-size: .72rem; }
        .category-action {
            display: inline-flex; align-items: center; gap: 8px; margin-top: auto; padding-top: 15px;
            color: var(--orange-dark); font-size: .8rem; font-weight: 750; text-decoration: none;
        }
        .category-action svg { width: 14px; height: 14px; transition: transform .18s ease; }
        .category-action:hover { color: #b94b1e; }
        .category-action:hover svg { transform: translateX(3px); }
        .empty-state {
            display: grid; justify-items: center; max-width: 650px; margin: 0 auto; padding: 38px 24px;
            border: 1px dashed #c8d5dc; border-radius: 12px; background: rgba(255,255,255,.7); text-align: center;
        }
        .empty-icon { display: grid; place-items: center; width: 46px; height: 46px; margin-bottom: 13px; border-radius: 13px; background: #e8eff2; color: var(--ink); }
        .empty-icon svg { width: 22px; height: 22px; }
        .empty-state h3 { margin: 0 0 7px; font-size: 1rem; font-weight: 720; }
        .empty-state p { max-width: 410px; margin: 0 0 18px; color: var(--muted); font-size: .84rem; line-height: 1.6; }
        .empty-state a { display: inline-flex; align-items: center; min-height: 39px; padding: 0 15px; border-radius: 7px; background: var(--orange); color: white; font-size: .82rem; font-weight: 700; text-decoration: none; }
        .empty-state a:hover { background: var(--orange-dark); color: white; }

        .trust-strip { padding: 22px 24px; background: #eaf0f3; }
        .trust-inner { display: flex; justify-content: center; align-items: center; flex-wrap: wrap; gap: 22px 45px; max-width: 1050px; margin: 0 auto; }
        .trust-item { display: inline-flex; align-items: center; gap: 9px; color: #4e6572; font-size: .78rem; font-weight: 620; }
        .trust-item svg { width: 17px; height: 17px; color: var(--orange-dark); }
        .site-footer { padding: 27px 24px; background: var(--ink-deep); color: #adbec7; }
        .footer-inner { display: flex; align-items: center; justify-content: space-between; gap: 22px; max-width: 1180px; margin: 0 auto; }
        .footer-brand { display: flex; align-items: center; gap: 10px; color: #fff; font-size: .91rem; font-weight: 720; }
        .footer-brand .brand-mark { width: 29px; height: 29px; border-radius: 8px; }
        .footer-brand .brand-mark svg { width: 16px; height: 16px; }
        .footer-copy { margin: 0; font-size: .74rem; line-height: 1.6; text-align: right; }
        :focus-visible { outline: 3px solid rgba(237,113,56,.55); outline-offset: 3px; }
        @media (max-width: 760px) {
            .header-inner { min-height: 68px; padding: 0 20px; gap: 14px; }
            .main-nav { display: none; }
            .mobile-nav { display: flex; gap: 24px; overflow-x: auto; padding: 0 20px 14px; scrollbar-width: none; }
            .mobile-nav::-webkit-scrollbar { display: none; }
            .mobile-nav a { flex: 0 0 auto; color: #c4d1d8; font-size: .8rem; text-decoration: none; }
            .mobile-nav a:hover { color: #fff; }
            .header-actions { margin-left: auto; }
            .header-actions a { min-height: 36px; padding: 0 11px; }
            .hero { padding: 55px 20px 51px; }
            .catalog-section { padding: 46px 20px 56px; }
            .category-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 14px; }
            .category-card { min-height: 225px; }
        }
        @media (max-width: 520px) {
            .header-inner { padding: 0 16px; }
            .brand { gap: 9px; }
            .brand-name { font-size: .94rem; }
            .brand-mark { width: 32px; height: 32px; }
            .login-link { display: none !important; }
            .header-actions a { min-height: 35px; }
            .mobile-nav { padding-left: 16px; padding-right: 16px; }
            .hero { padding: 43px 17px 41px; }
            .hero-copy { font-size: .89rem; margin-top: 12px; }
            .domain-form { flex-direction: column; padding: 6px; gap: 3px; }
            .domain-input-wrap { min-height: 47px; }
            .domain-form button { width: 100%; min-height: 45px; }
            .catalog-section { padding: 39px 16px 45px; }
            .section-heading { margin-bottom: 22px; }
            .category-grid { grid-template-columns: 1fr; gap: 12px; }
            .category-card { min-height: 0; }
            .category-body { min-height: 137px; }
            .trust-inner { justify-content: flex-start; gap: 13px 24px; }
            .trust-item { font-size: .74rem; }
            .footer-inner { align-items: flex-start; flex-direction: column; gap: 13px; }
            .footer-copy { text-align: left; }
        }
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { scroll-behavior: auto !important; transition-duration: .01ms !important; }
        }
    </style>
</head>
<body>
<div class="store-shell">
    <div class="topline" aria-hidden="true"></div>
    <header class="site-header">
        <div class="header-inner">
            <a class="brand" href="{{ route('home') }}" aria-label="{{ $siteName }} home">
                @if ($siteLogo)
                    <img src="{{ route('branding.file', $siteLogo) }}" alt="{{ $siteName }}">
                @else
                    <span class="brand-mark" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13.2 2.8 5.5 13h5.2l-.6 8.2L18.5 11h-5.2l-.1-8.2Z"/></svg>
                    </span>
                    <span class="brand-name">{{ $siteName }}</span>
                @endif
            </a>
            <nav class="main-nav" aria-label="Main navigation">
                @foreach ($storefrontNavLinks ?? [] as $link)
                    <a href="{{ $link['url'] }}">{{ $link['label'] }}</a>
                @endforeach
            </nav>
            <div class="header-actions">
                <a class="login-link" href="{{ route('client.login') }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><path d="m10 17 5-5-5-5M15 12H3"/></svg>
                    Client login
                </a>
                <a class="cart-link" href="{{ route('cart.index') }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 3h2l2.2 11.2a2 2 0 0 0 2 1.6h8.9a2 2 0 0 0 1.9-1.5L22 8H6"/><circle cx="10" cy="20" r="1"/><circle cx="18" cy="20" r="1"/></svg>
                    Cart
                </a>
            </div>
        </div>
        <nav class="mobile-nav" aria-label="Mobile navigation">
            @foreach ($storefrontNavLinks ?? [] as $link)
                <a href="{{ $link['url'] }}">{{ $link['label'] }}</a>
            @endforeach
            <a href="{{ route('client.login') }}">Client login</a>
        </nav>
    </header>

    <main>
        <section class="hero" aria-labelledby="hero-title">
            <div class="hero-content">
                <span class="eyebrow">Your online address starts here</span>
                <h1 id="hero-title">Secure your domain name</h1>
                <p class="hero-copy">Your domain name is your online business’s most valuable asset. Find the right name and give your next project a home.</p>
                <form class="domain-form" action="{{ route('domain.search') }}" method="GET" role="search">
                    <label class="domain-input-wrap" for="domain-search">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m16.2 16.2 4.3 4.3"/></svg>
                        <input id="domain-search" type="text" name="domain" value="{{ request('domain') }}" placeholder="Domain name or keyword" autocomplete="url" required>
                    </label>
                    <button type="submit">
                        Search
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                    </button>
                </form>
                <p class="hero-note">Start with a name that feels like you. We’ll help with the rest.</p>
            </div>
        </section>

        <section class="catalog-section" id="categories" aria-labelledby="categories-title">
            <div class="section-inner">
                <div class="section-heading">
                    <span class="eyebrow">The right place to start</span>
                    <h2 id="categories-title">Browse our services</h2>
                    <p>Choose a category to explore the hosting options available for your next step.</p>
                </div>

                @if (isset($categories) && $categories->isNotEmpty())
                    <div class="category-grid">
                        @foreach ($categories as $category)
                            <article class="category-card">
                                <div class="category-top">
                                    <span class="category-icon" aria-hidden="true">
                                        @if (($category->type ?? '') === 'vps')
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="7" rx="2"/><rect x="3" y="14" width="18" height="7" rx="2"/><path d="M7 6.5h.01M7 17.5h.01M11 6.5h6M11 17.5h6"/></svg>
                                        @else
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7.5 12 3l8 4.5v9L12 21l-8-4.5v-9Z"/><path d="m4.5 7.8 7.5 4.4 7.5-4.4M12 12.2V21M8 5.3l8 4.6"/></svg>
                                        @endif
                                    </span>
                                    <h3>{{ $category->name }}</h3>
                                </div>
                                <div class="category-body">
                                    <p class="category-description">{{ $category->description ?: 'Explore the available services in this category.' }}</p>
                                    @if (isset($category->products_count))
                                        <span class="category-meta">{{ $category->products_count }} {{ \Illuminate\Support\Pluralizer::plural('service', (int) $category->products_count) }} available</span>
                                    @endif
                                    <a class="category-action" href="{{ $category->publicUrl() }}">
                                        Browse services
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                                    </a>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <span class="empty-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="M3 9h18M8 14h.01M12 14h4"/></svg>
                        </span>
                        <h3>Services are being prepared</h3>
                        <p>There are no hosting categories to browse right now. Check back soon, or search for the domain you have in mind.</p>
                        <a href="{{ route('catalog.index') }}">Visit the store</a>
                    </div>
                @endif
            </div>
        </section>

        <section class="trust-strip" aria-label="Store benefits">
            <div class="trust-inner">
                <span class="trust-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 22s8-4 8-11V5l-8-3-8 3v6c0 7 8 11 8 11Z"/><path d="m9 12 2 2 4-4"/></svg>
                    Secure checkout
                </span>
                <span class="trust-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
                    Clear, simple ordering
                </span>
                <span class="trust-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 12a8 8 0 0 1 14.9-4M20 12a8 8 0 0 1-14.9 4"/><path d="M19 3v5h-5M5 21v-5h5"/></svg>
                    Manage services in your account
                </span>
            </div>
        </section>
    </main>

    <footer class="site-footer">
        <div class="footer-inner">
            <a class="brand footer-brand" href="{{ route('home') }}">
                <span class="brand-mark" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13.2 2.8 5.5 13h5.2l-.6 8.2L18.5 11h-5.2l-.1-8.2Z"/></svg>
                </span>
                <span>{{ $siteName }}</span>
            </a>
            <p class="footer-copy">© {{ date('Y') }} {{ $siteName }}. All rights reserved.</p>
        </div>
    </footer>
</div>
</body>
</html>
