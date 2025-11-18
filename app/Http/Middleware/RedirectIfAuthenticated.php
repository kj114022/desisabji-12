<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        Log::info("RedirectIfAuthenticated middleware is checking authentication for guard: $guard");
        if (Auth::guard($guard)->check()) {
            Log::info("User is authenticated, redirecting to home");
            return redirect('/');
        }

        return $next($request);
    }
}
