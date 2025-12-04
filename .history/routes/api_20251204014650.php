<?php

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
Route::post

// Event CRUD Routes
Route::apiResource('events', EventController::class);
