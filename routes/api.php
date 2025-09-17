<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;

Route::prefix('/auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:register');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:login');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('/tasks', TaskController::class);
    Route::get('/user', [UserController::class, 'show']);
    Route::post('/user', [UserController::class, 'show']);
    Route::delete('/user', [UserController::class, 'destroy']);
    Route::post('/upload/profile', [UserController::class, 'uploadProfile'])
        ->middleware('throttle:upload_profile');
});
