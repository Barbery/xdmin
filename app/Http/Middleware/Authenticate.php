<?php

namespace App\Http\Middleware;

use App\Exceptions\Error;
use Auth;
use Closure;

class Authenticate
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
        if (Auth::guard($guard)->guest()) {
            if (!$request->ajax()) {
                return redirect('/login');
            }

            throw new Error(401, 'TOKEN_EXPIRED');
        }

        Auth::shouldUse($guard);

        return $next($request);
    }
}
