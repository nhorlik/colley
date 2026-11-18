<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\MuscleGroupController;
use App\Http\Controllers\Api\V1\ExerciseController;
use App\Http\Controllers\Api\V1\PlanController;

Route::prefix('v1')->group(function () {
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);

    Route::get('/muscle-groups', [MuscleGroupController::class, 'index']);
    Route::get('/exercises', [ExerciseController::class, 'index']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::get('/me', [UserController::class, 'show']);
        Route::get('/plans/current', [PlanController::class, 'current']);
        Route::post('/plans/generate', [PlanController::class, 'generate']);
    });
});
