<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSvrmsRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        abort_unless($request->session()->get('svrms_role') === $role && filter_var($request->session()->get('svrms_id'), FILTER_VALIDATE_INT) > 0, 403);

        return $next($request);
    }
}
