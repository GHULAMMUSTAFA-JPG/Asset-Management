<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Maintenance\MaintenanceController;
use App\Http\Controllers\Maintenance\MaintenanceLogController;
use App\Http\Controllers\Maintenance\MaintenanceAlertController;

Route::middleware('auth:sanctum')->group(function () {

    // Maintenance Schedules
    Route::get('/maintenance',                        [MaintenanceController::class, 'index']);
    Route::post('/maintenance',                       [MaintenanceController::class, 'store']);
    Route::get('/maintenance/{id}',                   [MaintenanceController::class, 'show']);
    Route::put('/maintenance/{id}',                   [MaintenanceController::class, 'update']);
    Route::delete('/maintenance/{id}',                [MaintenanceController::class, 'destroy']);
    Route::put('/maintenance/{id}/status',            [MaintenanceController::class, 'updateStatus']);

    // Maintenance Logs
    Route::get('/maintenance/{id}/logs',              [MaintenanceLogController::class, 'index']);
    Route::post('/maintenance/{id}/logs',             [MaintenanceLogController::class, 'store']);

    // Maintenance Alerts
    Route::get('/maintenance/alerts',                 [MaintenanceAlertController::class, 'index']);
    Route::put('/maintenance/alerts/{id}/read',       [MaintenanceAlertController::class, 'markRead']);

});