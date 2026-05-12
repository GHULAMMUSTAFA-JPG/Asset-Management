<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('/signup',          [AuthController::class, 'signup']);
    Route::post('/login',           [AuthController::class, 'login']);
    Route::post('/verify-email',    [AuthController::class, 'verifyEmail']);
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/reset-password',  [AuthController::class, 'resetPassword']);

    Route::middleware('auth.token')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
    });
});