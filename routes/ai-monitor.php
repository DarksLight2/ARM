<?php

use DarksLight2\AiRequestsMonitoring\Controllers\ShowController;
use DarksLight2\AiRequestsMonitoring\Middleware\AccessMiddleware;
use DarksLight2\AiRequestsMonitoring\Controllers\DashboardController;

Route::prefix('ai-monitor')
    ->middleware(AccessMiddleware::class)
    ->name('ai-monitor.')->group(function () {
        Route::get('dashboard', DashboardController::class)->name('dashboard');
        Route::get('show/{id}', ShowController::class)->name('show');
    });
