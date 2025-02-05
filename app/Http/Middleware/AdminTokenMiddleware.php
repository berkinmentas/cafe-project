<?php

namespace App\Http\Middleware;

use App\Http\Responses\ApiResponse;
use App\Models\Admin;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminTokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $publicRoutes = [
            'api/admin/login',
            'api/admin/register',
            'api/admin/refresh-token'
        ];

        if (in_array($request->path(), $publicRoutes)) {
            return $next($request);
        }

        if (!$request->bearerToken()) {
            return ApiResponse::error('Unauthorized - No token provided', 401);
        }

        $admin = Admin::query()->where('access_token', $request->bearerToken())->first();

        if (!$admin) {
            return ApiResponse::error('Unauthorized - Invalid token', 401);
        }

        if ($admin->token_expires_at && now()->gt($admin->token_expires_at)) {
            return ApiResponse::error('Unauthorized - Token has expired', 401);
        }

        return $next($request);
    }
}
