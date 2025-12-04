<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Health Chechk Route
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'message' => 'Api is healthy',
        'timestamp' => now()
    ]);
});

// Authentication Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected Routes (Require Authentication)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
});

// Public Event Routes (No Authentication Required)
Route::get('/events', [EventController::class, 'index']);
Route::get('/events/{event}', [EventController::class, 'show']);

// Protected Event Routes (Require Authentication)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/events', [EventController::class, 'store']);
    Route::put('/events/{event}', [EventController::class, 'update']);
    Route::delete('/events/{event}', [EventController::class, 'destroy']);
});
