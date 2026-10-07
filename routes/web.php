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
| Public website: storefront and public content
|--------------------------------------------------------------------------
| The public host owns two focused modules: store routes first, then public
| content routes (whose CMS slug catch-all must remain last).
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

    require base_path('routes/store.php');
    require base_path('routes/public.php');
});
