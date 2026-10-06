<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LegacyPortalRedirectController extends Controller
{
    public function admin(Request $request, ?string $path = null): RedirectResponse
    {
        $homeUrl = rtrim((string) config('app.url'), '/').'/';

        return redirect()->away($homeUrl, 301);
    }

    public function client(Request $request, ?string $path = null): RedirectResponse
    {
        return $this->redirectToPortal(
            (string) config('portals.client_host'),
            $request,
            $path,
        );
    }

    private function redirectToPortal(string $host, Request $request, ?string $path): RedirectResponse
    {
        $segments = array_filter(explode('/', trim((string) $path, '/')), static fn (string $part): bool => $part !== '');
        $targetPath = $segments === []
            ? '/'
            : '/'.implode('/', array_map('rawurlencode', $segments));

        $scheme = $request->isSecure() ? 'https' : 'http';
        $target = $scheme.'://'.$host.$targetPath;

        if ($query = $request->getQueryString()) {
            $target .= '?'.$query;
        }

        return redirect()->away($target, 301);
    }
}
