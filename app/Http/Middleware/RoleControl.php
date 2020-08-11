<?php

namespace App\Http\Middleware;

use App\User;
use Closure;

class RoleControl
{

    public function handle($request, Closure $next, $role)
    {
        if ($request->user()->hasRole($role))
            return $next($request);

        abort(403);
    }
}
