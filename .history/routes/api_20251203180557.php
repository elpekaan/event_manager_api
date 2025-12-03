<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// Route::get('/health', function () {
//     return response()->json([
//         'status' => 'ok',
//         'message' => 'API is healthy',
//         'tinestamp' => now()
//     ], 200);
// });

Route::get('/healt')
