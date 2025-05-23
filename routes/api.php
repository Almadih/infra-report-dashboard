<?php

use App\Http\Controllers\AnonymousAuthController;
use App\Http\Controllers\DamageTypeController;
use App\Http\Controllers\SeverityController;
use App\Http\Controllers\Users\MyReportsController;
use App\Http\Controllers\Users\ReportController;
use Illuminate\Support\Facades\Route; // Or your Guzzle version adapter

Route::post('/auth', AnonymousAuthController::class)->name('anonymous.auth');
Route::middleware('auth:sanctum')->group(function () {
    Route::resource('reports', ReportController::class)->only(['index', 'store', 'show'])->names('api.reports');
    Route::get('/my-reports', MyReportsController::class)->name('my-reports');
    Route::resource('damage-types', DamageTypeController::class)->only('index');
    Route::resource('severities', SeverityController::class)->only('index');

});
