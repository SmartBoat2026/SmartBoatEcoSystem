<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureNotStaff
{
    /** Only main admin (not panel staff) may access staff management. */
    public function handle(Request $request, Closure $next): Response
    {
        if (session('admin_is_staff')) {
            abort(403);
        }

        return $next($request);
    }
}
