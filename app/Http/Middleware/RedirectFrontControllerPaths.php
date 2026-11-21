<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Laravel also answers /index.php/anh-cover with the page itself, so Google indexed some
 * pages twice. Sends any GET to /index.php or /index.php/... to the clean URL with a 301.
 */
class RedirectFrontControllerPaths
{
    public function handle(Request $request, Closure $next): Response
    {
        $uri = $request->getRequestUri();

        if ($request->isMethodSafe() && preg_match('#^/index\.php(?=/|\?|$)(/*)(.*)$#i', $uri, $match)) {
            // Built by hand: on these requests the URL generator's root already ends in /index.php.
            return new RedirectResponse($request->getSchemeAndHttpHost().'/'.ltrim($match[2], '/'), 301);
        }

        return $next($request);
    }
}
