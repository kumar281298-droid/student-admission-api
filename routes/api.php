<?php

use App\Http\Controllers\AISummaryController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CollegeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/colleges', [CollegeController::class, 'index']);
Route::get('/colleges/{id}', [CollegeController::class, 'show']);
Route::get('/colleges/{id}/courses', [CollegeController::class, 'courses']);

/*
|--------------------------------------------------------------------------
| Authenticated API Routes (Sanctum)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Application Management & Workflow
    Route::get('/applications', [ApplicationController::class, 'index']);
    Route::post('/applications', [ApplicationController::class, 'store']);
    Route::get('/applications/{id}', [ApplicationController::class, 'show']);
    Route::post('/applications/{id}/approve', [ApplicationController::class, 'approve']);
    Route::post('/applications/{id}/reject', [ApplicationController::class, 'reject']);

    // AI Summary Service Feature
    Route::post('/applications/{id}/ai-summary', [AISummaryController::class, 'generate']);
});
