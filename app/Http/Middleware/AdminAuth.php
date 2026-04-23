<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (!session()->has('admin_logged_in')) {
            return redirect()->route('login')
                             ->with('status', 'Please login to continue.');
        }

        return $next($request);
    }
}
