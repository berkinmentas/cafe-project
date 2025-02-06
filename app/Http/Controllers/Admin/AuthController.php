<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Models\Admin;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Exception;
use Carbon\Carbon;

class AuthController extends Controller
{
    private function generateTokens(Admin $admin): array
    {
        $admin->access_token = Str::random(60);
        $admin->refresh_token = Str::random(60);
        $admin->token_expires_at = Carbon::now()->addDay();
        $admin->save();

        return [
            'access_token' => $admin->access_token,
            'refresh_token' => $admin->refresh_token,
            'expires_at' => $admin->token_expires_at
        ];
    }

    public function register(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
                'email' => 'required|email|unique:admins,email',
                'password' => 'required|string|min:8'
            ]);

            if ($validator->fails()) {
                return ApiResponse::error('Validation failed', 422, $validator->errors());
            }

            $admin = Admin::query()->create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $tokens = $this->generateTokens($admin);

            return ApiResponse::success([
                'admin' => $admin->only(['id', 'name', 'email']),
                ...$tokens
            ], 'Registration successfully', 201);

        } catch (Exception $e) {
            return ApiResponse::error('Registration failed', 500, $e->getMessage());
        }
    }

    public function login(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required|string'
            ]);

            if ($validator->fails()) {
                return ApiResponse::error('Validation failed', 422, $validator->errors());
            }

            $admin = Admin::query()->where('email', $request->email)->first();

            if (!$admin || !Hash::check($request->password, $admin->password)) {
                return ApiResponse::error('Invalid credentials', 401);
            }

            $tokens = $this->generateTokens($admin);

            return response()->json([
                'status' => true,
                'message' => 'Login successful',
                'data' => [
                    'admin' => $admin->only(['id', 'name', 'email']),
                    ...$tokens
                ]
            ])
                ->header('Access-Control-Allow-Credentials', 'true')
                ->header('Access-Control-Allow-Origin', $request->header('Origin'));

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Login failed',
                'error' => $e->getMessage()
            ], 401);
        }
    }

    public function refreshToken(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'refresh_token' => 'required|string'
            ]);

            if ($validator->fails()) {
                return ApiResponse::error('Validation failed', 422, $validator->errors());
            }

            $admin = Admin::query()->where('refresh_token', $request->refresh_token)->first();

            if (!$admin) {
                return ApiResponse::error('Invalid refresh token', 401);
            }

            $tokens = $this->generateTokens($admin);

            return ApiResponse::success($tokens, 'Token refreshed successfully');

        } catch (Exception $e) {
            return ApiResponse::error('Token refresh failed', 500, $e->getMessage());
        }
    }

    public function logout(Request $request): JsonResponse
    {
        try {
            $admin = $request->user();
            $admin->access_token = null;
            $admin->refresh_token = null;
            $admin->token_expires_at = null;
            $admin->save();

            return ApiResponse::success(null, 'Logout successful');
        } catch (Exception $e) {
            return ApiResponse::error('Logout failed', 500, $e->getMessage());
        }
    }
}
