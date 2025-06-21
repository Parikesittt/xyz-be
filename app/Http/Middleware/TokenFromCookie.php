<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class TokenFromCookie
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        if (!$request->bearerToken() && $request->cookie('token')) {
            Log::info('Injecting token from cookie');
            Log::info('==> COOKIE TOKEN: ' . $request->cookie('token'));
            Log::info('==> HEADER Authorization BEFORE: ' . $request->header('Authorization'));
            $request->headers->set('Authorization', 'Bearer ' . $request->cookie('token'));
            Log::info('JWT from cookie: ' . $request->cookie('token'));
            Log::info('==> Authorization header SET by middleware');
        } else {
            Log::warning('No token in cookie or already has bearer');
        }

        return $next($request);
    }
}
