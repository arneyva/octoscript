<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\PlatformController;
use App\Http\Controllers\Api\PostinganController;
use App\Http\Controllers\Api\PostsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/login', [AuthController::class, 'generateToken'])->name('login');
// Protected routes
Route::middleware(['auth:sanctum'])->group(function () {
    Route::prefix('/brand')->group(function () {
        Route::get('', [BrandController::class, 'index']);
        Route::post('/store', [BrandController::class, 'store']);
        Route::delete('/{id}', [BrandController::class, 'destroy']);
        Route::put('/{id}', [BrandController::class, 'update']);
    });
    Route::prefix('/platform')->group(function () {
        Route::get('', [PlatformController::class, 'index']);
        Route::post('/store', [PlatformController::class, 'store']);
        Route::delete('/{id}', [PlatformController::class, 'destroy']);
        Route::put('/{id}', [PlatformController::class, 'update']);
    });
    Route::prefix('/posts')->group(function () {
        Route::get('', [PostsController::class, 'index']);
        Route::post('/store', [PostsController::class, 'store']);
        Route::delete('/{id}', [PostsController::class, 'destroy']);
        Route::put('/{id}', [PostsController::class, 'update']);
    });
    Route::post('/logout', [AuthController::class, 'logout']);
});
