<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (! auth()->check()) {
            abort(403);
        }

        // Super admin passes all role checks
        if (auth()->user()->isSuperAdmin()) {
            return $next($request);
        }

        if (! in_array(auth()->user()->role->name ?? '', $roles)) {
            abort(403);
        }

        return $next($request);
    }
}
