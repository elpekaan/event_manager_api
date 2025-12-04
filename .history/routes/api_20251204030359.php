<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VenueController;

// Health Chechk Route
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'message' => 'Api is healthy',
        'timestamp' => now()
    ]);
});

// Public Auth Routes (No Authentication Required)
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

// Public Venue Routes (No Authentication Required)
Route::get('/venues', [VenueController::class, 'index']);
Route::get('/venues/{venue}', [VenueController::class, 'show']);

// Protected Venue Routes (Require Authentication)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/venues', [VenueController::class, 'store']);
    Route::put('/venues/{venue}', [VenueController::class, 'update']);
