<?php
// FILE: app/Http/Middleware/MemberAuth.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class MemberAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!session()->has('member_logged_in')) {
            return redirect('/login')->with('status', 'Please login to continue.');
        }
        return $next($request);
    }
}
