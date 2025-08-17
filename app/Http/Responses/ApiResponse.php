<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;

class ApiResponse
{
    public static function success(mixed $data = null, int $status = 200): JsonResponse
    {
        return response()->json(['status' => 'success', 'data' => $data], $status);
    }

    public static function error(string $message, int $status = 500): JsonResponse
    {
        return response()->json(['status' => 'error', 'message' => $message], $status);
    }
}
