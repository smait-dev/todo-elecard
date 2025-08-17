<?php

declare(strict_types=1);

use App\Http\Controllers\V1\AuthController;
use App\Http\Controllers\V1\TaskController;
use App\Http\Responses\ApiResponse;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('register', [AuthController::class, 'register'])->name('register');
    Route::post('login', [AuthController::class, 'login'])->name('login');

    Route::middleware('auth:api')->group(function () {
        Route::get('me', [AuthController::class, 'me'])->name('me');

        Route::apiResource('tasks', TaskController::class)
            ->missing(function () {
                return ApiResponse::error('Task not found.', 404);
            });

    });
});
