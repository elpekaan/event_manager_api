<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\VenueController;
use App\Http\Controllers\UploadController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Health Check Route
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

// Public Event Routes (No Authentication Required)
Route::get('/events', [EventController::class, 'index']);
Route::get('/events/{event}', [EventController::class, 'show']);

// Public Venue Routes (No Authentication Required)
Route::get('/venues', [VenueController::class, 'index']);
Route::get('/venues/{venue}', [VenueController::class, 'show']);

// Protected Routes (Require Authentication)
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    // Events
    Route::post('/events', [EventController::class, 'store']);
    Route::put('/events/{event}', [EventController::class, 'update']);
    Route::delete('/events/{event}', [EventController::class, 'destroy']);

    // Venues
    Route::post('/venues', [VenueController::class, 'store']);
    Route::put('/venues/{venue}', [VenueController::class, 'update']);
    Route::delete('/venues/{venue}', [VenueController::class, 'destroy']);

    // Upload
    Route::post('/upload/image', [UploadController::class, 'uploadImage']);
    Route::post('/upload/gallery', [UploadController::class, 'uploadGallery']);
    Route::delete('/upload/image', [UploadController::class, 'deleteImage']);
});
