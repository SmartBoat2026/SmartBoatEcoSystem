<?php
// ══════════════════════════════════════════════════════════════════
// FILE: bootstrap/app.php   (Laravel 11 — use this if you have it)
// ══════════════════════════════════════════════════════════════════

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin.auth'  => \App\Http\Middleware\AdminAuth::class,
            'admin.panel' => \App\Http\Middleware\EnsureAdminPanelPermission::class,
            'admin.super' => \App\Http\Middleware\EnsureNotStaff::class,
            'member.auth' => \App\Http\Middleware\MemberAuth::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();


// ══════════════════════════════════════════════════════════════════
// FILE: app/Http/Kernel.php   (Laravel 10 — use this instead)
// ══════════════════════════════════════════════════════════════════
// Inside the $routeMiddleware array, ADD these two lines:
//
//   'admin.auth'  => \App\Http\Middleware\AdminAuth::class,
//   'member.auth' => \App\Http\Middleware\MemberAuth::class,
//
// Example placement:
//
//   protected $routeMiddleware = [
//       'auth'        => \App\Http\Middleware\Authenticate::class,
//       'admin.auth'  => \App\Http\Middleware\AdminAuth::class,   // ← ADD
//       'member.auth' => \App\Http\Middleware\MemberAuth::class,  // ← ADD
//       ...
//   ];
