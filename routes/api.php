<?php

use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

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
