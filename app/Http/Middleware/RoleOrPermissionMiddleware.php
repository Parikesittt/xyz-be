<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Spatie\Permission\Exceptions\UnauthorizedException;

class RoleOrPermissionMiddleware
{
    public function handle(Request $request, Closure $next, $roleOrPermission)
    {
        if (! $request->user()->hasAnyRole(explode('|', $roleOrPermission)) &&
            ! $request->user()->hasAnyPermission(explode('|', $roleOrPermission))) {
            throw UnauthorizedException::forRolesOrPermissions([$roleOrPermission]);
        }

        return $next($request);
    }
}
