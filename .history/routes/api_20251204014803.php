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

// Authentication Routes (Everyone can access)
Route::post('/register', AuthController::class . 'register');
Route::post('/login', AuthController::class . 'login');

// Protected Routes (Require Authentication)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', AuthController::class . 'logout');
    Route::get()

// Event CRUD Routes
Route::apiResource('events', EventController::class);
