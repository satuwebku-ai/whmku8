<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin and client portals
|--------------------------------------------------------------------------
| Portal routes remain in separate modules and are served only from their
| configured subdomains. Legacy /admin and /client URLs redirect there.
*/
Route::domain(config('portal_domains.admin'))
    ->name('admin.')
    ->group(base_path('routes/admin.php'));

Route::domain(config('portal_domains.admin'))->group(function () {
    Route::post('csp-report', \App\Http\Controllers\CspReportController::class)
        ->middleware('throttle:60,1');
    Route::any('admin/{path?}', function (Request $request, ?string $path = null) {
        $target = 'https://'.config('portal_domains.admin').'/'.ltrim((string) $path, '/');
        if ($request->getQueryString()) {
            $target .= '?'.$request->getQueryString();
        }

        return redirect()->away($target, 308);
    })->where('path', '.*');
    Route::any('{path?}', static fn () => abort(404))->where('path', '.*');
});

Route::domain(config('portal_domains.client'))
    ->name('client.')
    ->group(base_path('routes/client.php'));

Route::domain(config('portal_domains.client'))->group(function () {
    Route::post('csp-report', \App\Http\Controllers\CspReportController::class)
        ->middleware('throttle:60,1');

    // Toko (katalog, domain, lisensi, keranjang) di bawah /store. Harus
    // didaftarkan sebelum catch-all 404 di bawah.
    Route::prefix('store')->group(base_path('routes/store.php'));

    Route::any('client/{path?}', function (Request $request, ?string $path = null) {
        $target = 'https://'.config('portal_domains.client').'/'.ltrim((string) $path, '/');
        if ($request->getQueryString()) {
            $target .= '?'.$request->getQueryString();
        }

        return redirect()->away($target, 308);
    })->where('path', '.*');
    Route::any('{path?}', static fn () => abort(404))->where('path', '.*');
});

/*
|--------------------------------------------------------------------------
| Public website: CMS and public content
|--------------------------------------------------------------------------
| The public host only serves content (home, blog, pages, announcements,
| knowledge base, promo, chat widget, webhooks). The store lives on the
| client host under /store (routes/store.php). Old public store addresses
| redirect there so bookmarks, search results and external links keep working.
| The CMS slug catch-all in routes/public.php must remain last.
*/
Route::domain(config('portal_domains.public'))->group(function () {
    Route::any('admin/{path?}', function (Request $request, ?string $path = null) {
        if ($path === 'login') {
            return redirect()->route('home');
        }

        $target = 'https://'.config('portal_domains.admin').'/'.ltrim((string) $path, '/');
        if ($request->getQueryString()) {
            $target .= '?'.$request->getQueryString();
        }

        return redirect()->away($target, 308);
    })->where('path', '.*');

    Route::any('client/{path?}', function (Request $request, ?string $path = null) {
        $target = 'https://'.config('portal_domains.client').'/'.ltrim((string) $path, '/');
        if ($request->getQueryString()) {
            $target .= '?'.$request->getQueryString();
        }

        return redirect()->away($target, 308);
    })->where('path', '.*');

    Route::post('csp-report', \App\Http\Controllers\CspReportController::class)
        ->middleware('throttle:60,1')
        ->name('csp-report');

    // Alamat toko lama di domain publik -> alamat baru di portal client.
    // Hanya GET; query string ikut dibawa. Harus sebelum catch-all CMS.
    $toStore = fn (string $route) => function (Request $request) use ($route) {
        $url = route($route, $request->route()->parameters());
        if ($request->getQueryString()) {
            $url .= '?'.$request->getQueryString();
        }

        return redirect()->away($url, 301);
    };
    $sections = \App\Models\ProductType::sectionPattern();

    Route::get('hosting', $toStore('catalog.index'));
    Route::get('vps', $toStore('catalog.vps'));
    Route::get('lisensi', $toStore('license.index'));
    Route::get('lisensi/{slug}', $toStore('license.show'));
    Route::get('cek-domain', $toStore('domain.search'));
    Route::get('transfer-domain', $toStore('domains.transfer'));
    Route::get('domain-premium', $toStore('domain-premium.index'));
    Route::get('keranjang', $toStore('cart.index'));
    Route::get('{section}/{category}', $toStore('catalog.category'))->where('section', $sections);
    Route::get('{section}/{category}/{product}', $toStore('catalog.product'))->where('section', $sections);

    require base_path('routes/public.php');
});
