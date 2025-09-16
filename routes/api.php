<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;

Route::prefix('/auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:register');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:login');
});

Route::apiResource('/tasks', TaskController::class)->middleware('auth:sanctum');