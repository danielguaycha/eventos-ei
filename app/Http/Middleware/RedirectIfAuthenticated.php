<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{

    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            if ($request->user()->isAdmin()) {
                return redirect()->intended(route('events.index'));
            }

            return redirect('/');
        }

        return $next($request);
    }
}
