<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\LoginRequest;
use App\Http\Requests\Api\V1\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Http\Responses\ApiResponse;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\JWTGuard;

class AuthController extends Controller
{
    /**
     * @param RegisterRequest&object{username: string, password: string} $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        User::query()->create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
        ]);

        return ApiResponse::success(status: 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->only('username', 'password');

        /** @var bool|string $token */
        $token = auth('api')->attempt($credentials);
        if (!$token) {
            return ApiResponse::error('Unauthorized.', 401);
        }

        /** @var JWTGuard $auth */
        $auth = auth('api');
        return ApiResponse::success([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $auth->factory()->getTTL() * 60
        ]);
    }

    public function me(): JsonResponse
    {
        return ApiResponse::success(new UserResource(auth()->user()));
    }
}
