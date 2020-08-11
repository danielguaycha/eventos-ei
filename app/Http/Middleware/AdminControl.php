<?php

namespace App\Http\Middleware;

use Closure;

class AdminControl
{

    public function handle($request, Closure $next)
    {
        if ($request->user()->isAdmin())
            return $next($request);

        abort(403);
    }
}
