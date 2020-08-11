<?php

namespace App\Http\Middleware;

use Closure;

class RootControl
{

    public function handle($request, Closure $next)
    {
        if ($request->user()->isRoot())
            return $next($request);

        abort(403);
    }
}
