<?php

use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\TaskController;
use App\Http\Controllers\Api\V1\AuthController;
use Illuminate\Support\Facades\Route;

// Auth
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Auth protected
Route::group(['middleware' => 'auth:api'], function () {

    Route::get('/logout', [AuthController::class, 'logout']);
    Route::get('/refresh', [AuthController::class, 'refreshToken']);
    Route::get('/profile', [AuthController::class, 'profile']);

    Route::prefix('v1')->group(function () {

        // Category
        Route::get('/categories', [CategoryController::class, 'index']);
        Route::post('/categories', [CategoryController::class, 'store']);
        Route::get('/categories/{category}', [CategoryController::class, 'show']);
        Route::put('/categories/{category}', [CategoryController::class, 'update']);
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);
    
        // Task
        Route::get('/tasks/{category}', [TaskController::class, 'index']);
        Route::post('/tasks/{category}', [TaskController::class, 'store']);
        Route::get('/tasks/{task}/{category}', [TaskController::class, 'show']);
        Route::put('/tasks/{task}/{category}', [TaskController::class, 'update']);
        Route::delete('/tasks/{task}/{category}', [TaskController::class, 'destroy']);
    
    });

});
