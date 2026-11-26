<?php

use App\Http\Controllers\Frontend\SlugController;
use App\Http\Middleware\RequireSePayToken;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            // Global Catch-all (Slug Handler) - MUST BE LAST
            Route::middleware('web')
                ->get('/{slug}', [SlugController::class, 'show'])
                ->name('slug.handle');
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => RoleMiddleware::class,
            'permission' => PermissionMiddleware::class,
            'role_or_permission' => RoleOrPermissionMiddleware::class,
        ]);
        // Global, so it also covers the SePay package's own webhook route (and survives route:cache).
        $middleware->append(RequireSePayToken::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
