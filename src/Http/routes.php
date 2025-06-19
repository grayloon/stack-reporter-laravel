<?php

use GrayLoon\StackReporter\Http\Controllers\StackReporterSyncController;
use Illuminate\Support\Facades\Route;

Route::post('/api/v1/stack-reporter', StackReporterSyncController::class)
    ->name('stackreporter');
