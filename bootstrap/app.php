<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            // Admin Routes
            Route::middleware('web')
                ->group(base_path('routes/admin.php'));

            // Global Catch-all (Slug Handler) - MUST BE LAST
            Route::middleware('web')
                ->get('/{slug}', [\App\Http\Controllers\Frontend\SlugController::class, 'show'])
                ->name('slug.handle');
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'landlord' => \App\Http\Middleware\LandlordAccess::class,
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
