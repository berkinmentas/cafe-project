<?php

namespace App\Http\Responses;
use Illuminate\Http\JsonResponse;

class ApiResponse
{
    /**
     * Create a new class instance.
     */
    public static function success($data = null, $message = 'Success', $status = 200): JsonResponse
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data
        ], $status);
    }

    public static function error($message = 'An error occurred', $status = 400, $errors = null): JsonResponse
    {
        return response()->json([
            'status' => false,
            'message' => $message,
            'errors' => $errors
        ], $status);
    }

}
