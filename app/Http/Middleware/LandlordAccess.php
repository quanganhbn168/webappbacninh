<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LandlordAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || !auth()->user()->hasRole('super_admin')) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized landlord access.'], 403);
            }
            return redirect()->route('admin.login')->with('error', 'Bạn không có quyền truy cập khu vực này.');
        }

        return $next($request);
    }
}
