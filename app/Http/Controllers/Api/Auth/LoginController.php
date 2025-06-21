<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\Users;
use App\Models\User_apps;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoginController extends Controller
{
    /**
     * Login user and create token
     */
    public function login(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
            'app_name' => 'required',
            'app_baseurl' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Check login with email or username
        $user = Users::where(function($query) use ($request) {
            $query->where('email', $request->email)
                  ->orWhere('username', $request->email);
        })
        ->where('active', 1)
        ->whereNull('deleted_at')
        ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }

        $credentials = [
            'email' => $user->email,
            'password' => $request->password
        ];

        // Create JWT token
        $token = auth()->guard('api')->attempt($credentials);

        $cookie = cookie(
            'token',                    // nama cookie
            $token,                     // nilai cookie
            60 * 24,                    // durasi (menit) - 1 hari
            '/',                        // path
            'localhost',                       // domain
            false,                       // secure (gunakan true untuk HTTPS)
            true,                       // httpOnly
            false,                      // raw
            'Strict'                    // SameSite
        );

        // Return response with both tokens
        return response()->json([
            'success' => true,
            'user' => $user->only(['name', 'email', 'group_id', 'is_branch']),
            'permissions' => $user->getPermissionArray()
        ], 200)->withCookie($cookie);
    }

    /**
     * Logout user and invalidate tokens
     */
    public function logout(Request $request)
    {
        try {
            // Ambil token secara manual
            $token = $request->cookie('token');

            if (!$token) {
                return response()->json([
                    'success' => false,
                    'message' => 'No token provided'
                ], 401);
            }

            // Set token
            JWTAuth::setToken($token);

            // Coba dapatkan user (optional)
            try {
                $user = JWTAuth::toUser();

                // Update user app jika perlu
                if ($user) {
                    User_apps::where('user_id', $user->id)
                        ->update(['token_key_expire' => now()->subDay()->timestamp]);
                }
            } catch (\Exception $e) {
                // Ignore error, proceed with logout anyway
            }

            // Invalidate token
            JWTAuth::invalidate();

            return response()->json([
                'success' => true,
                'message' => 'Successfully logged out'
            ])->withCookie(cookie()->forget('token'));
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get authenticated user
     */
    public function check()
    {
        $user = auth()->guard('api')->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        return response()->json([
            'success' => true,
            'user' => $user->only(['name', 'email', 'id', 'company_id', 'is_branch', 'group_id']),
            'permissions' => $user->getPermissionArray()
        ], 200);
    }

    /**
     * Refresh JWT token
     */
    public function refresh()
    {
        try {
            $token = JWTAuth::refresh(JWTAuth::getToken());

            return response()->json([
                'success' => true,
                'token' => $token
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Could not refresh token'
            ], 401);
        }
    }
}
