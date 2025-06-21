<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Exception;

class JwtFromCookie
{
    public function handle(Request $request, Closure $next)
    {
        // Ambil token dari cookie
        $token = $request->cookie('token');

        if (!$token) {
            return response()->json(['message' => 'Token not provided'], 401);
        }

        try {
            // Set token secara manual
            JWTAuth::setToken($token)->authenticate();
        } catch (Exception $e) {
            return response()->json(['message' => 'Invalid token'], 401);
        }

        return $next($request);
    }
}
