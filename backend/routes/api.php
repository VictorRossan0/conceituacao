<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\UserProfileController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::apiResource('/users', UserController::class);
    Route::apiResource('/profiles', ProfileController::class);

    Route::get('/users/{user}/profiles', [UserProfileController::class, 'index']);
    Route::post('/users/{user}/profiles', [UserProfileController::class, 'attach']);
    Route::delete('/users/{user}/profiles/{profile}', [UserProfileController::class, 'detach']);
});

