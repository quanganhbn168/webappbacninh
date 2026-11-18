<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * The sepayvn package only checks the webhook token when one is configured; with an
 * empty SEPAY_WEBHOOK_TOKEN anyone could post a fake "money received" transfer.
 * Refuse the webhook until the token is set. Registered globally in bootstrap/app.php
 * because the route belongs to the package.
 */
class RequireSePayToken
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->is('api/sepay/webhook') && blank(config('sepay.webhook_token'))) {
            abort(503, 'SePay webhook is disabled: set SEPAY_WEBHOOK_TOKEN.');
        }

        return $next($request);
    }
}
