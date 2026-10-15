<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CampusUnitMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->role === 'campus-unit') {
            return $next($request);
        }

        return response()->json([
            'status' => 'Unauthorized',
            'message' => 'You are not authorized to access this resource.',
        ], 403);
    }
}
