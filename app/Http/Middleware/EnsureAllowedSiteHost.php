<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAllowedSiteHost
{
    public function handle(Request $request, Closure $next): Response
    {
        $publicHost = trim((string) config('lumora.public_site_host', ''));

        if ($publicHost === '') {
            $publicHost = (string) (parse_url((string) config('app.url'), PHP_URL_HOST) ?: '');
        }

        $allowedHosts = array_values(array_filter([
            $publicHost,
            trim((string) config('lumora.client_portal_host', '')),
        ]));

        $allowedHosts = array_map(
            static fn (string $host): string => strtolower(rtrim($host, '.')),
            $allowedHosts
        );

        $requestHost = strtolower(rtrim($request->getHost(), '.'));

        abort_unless(in_array($requestHost, $allowedHosts, true), 404);

        return $next($request);
    }
}
