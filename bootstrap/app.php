<?php

use App\Http\Middleware\EnsureAdmin;
use App\Http\Middleware\EnsureAuthenticated;
use App\Http\Middleware\EnsureSuperAdmin;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->prependToGroup('web', \App\Http\Middleware\TenantSession::class);
        $middleware->redirectGuestsTo(fn () => route('login'));
        $middleware->alias([
            'admin' => EnsureAdmin::class,
            'kasir.auth' => EnsureAuthenticated::class,
            'superadmin' => EnsureSuperAdmin::class,
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
