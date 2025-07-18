<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('api')->group(function () {
    // Public content endpoint
    Route::get('/get-content', [ContentController::class, 'publicIndex']);
    // Session tracking endpoints (protected)
    Route::middleware('auth:sanctum')->group(function () {
        // Content management
        Route::apiResource('contents', ContentController::class)->except(['index']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });
});
