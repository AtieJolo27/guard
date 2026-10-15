<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Vite;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next)
    {
        abort_if((int) $request->header('Content-Length', 0) > 131072, 413, 'Request is too large.');
        Vite::useCspNonce();
        $response = $next($request);
        $dev = app()->environment('local') ? ' http://localhost:* http://127.0.0.1:*' : '';
        $connect = app()->environment('local') ? ' ws://localhost:* ws://127.0.0.1:*' : '';
        $response->headers->set('Content-Security-Policy', "default-src 'self'; script-src 'self' 'nonce-".Vite::cspNonce()."'".$dev."; style-src 'self' 'unsafe-inline'".$dev."; img-src 'self' data:; font-src 'self' data:".$dev."; connect-src 'self'".$dev.$connect."; object-src 'none'; base-uri 'self'; frame-ancestors 'none'; form-action 'self'");
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('Referrer-Policy', 'same-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');
        $response->headers->set('Cache-Control', 'no-store, private');
        if (app()->isProduction() && $request->isSecure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000');
        }

        return $response;
    }
}
