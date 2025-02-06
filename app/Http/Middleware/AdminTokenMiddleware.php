<?php

namespace App\Http\Middleware;

use App\Http\Responses\ApiResponse;
use App\Models\Admin;
use Closure;
use Illuminate\Http\Request;

class AdminTokenMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $publicRoutes = [
            'api/admin/login',
            'api/admin/register',
            'api/admin/refresh-token',
            'admin/login',
            'admin/register'
        ];

        if (!str_starts_with($request->path(), 'api/')) {
            return $next($request);
        }

        if (in_array($request->path(), $publicRoutes)) {
            return $next($request);
        }

        if (!$request->bearerToken()) {
            return ApiResponse::error('Unauthorized - No token provided', 401);
        }

        $admin = Admin::where('access_token', $request->bearerToken())->first();

        if (!$admin) {
            return ApiResponse::error('Unauthorized - Invalid token', 401);
        }

        if ($admin->token_expires_at && now()->gt($admin->token_expires_at)) {
            return ApiResponse::error('Unauthorized - Token expired', 401);
        }

        return $next($request);
    }
}
