<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\Api\PostinganController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
// Public routes
Route::post('/login', [AuthController::class, 'generateToken']);

Route::middleware(['auth:sanctum'])->group(function () {
    // // Protected User API routes
    Route::get('/postingan', [PostinganController::class, 'index']);
    // Logout route
    Route::post('/auth/logout', [AuthController::class, 'logout']);
});
