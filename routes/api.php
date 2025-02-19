<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\Api\PostinganController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/login', [AuthController::class, 'generateToken']);
// Protected routes
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/postingan', [PostinganController::class, 'index']);
    Route::post('/logout', [AuthController::class, 'logout']);
});
