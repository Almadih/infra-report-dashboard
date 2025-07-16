<?php

use App\Http\Controllers\AnonymousAuthController;
use App\Http\Controllers\DamageTypeController;
use App\Http\Controllers\FCMTokenController;
use App\Http\Controllers\GetNotificationsController;
use App\Http\Controllers\ReadNotificationsController;
use App\Http\Controllers\SeverityController;
use App\Http\Controllers\Users\GetProfileController;
use App\Http\Controllers\Users\MyReportsController;
use App\Http\Controllers\Users\ReadOneNotificationController;
use App\Http\Controllers\Users\ReportController;
use App\Http\Controllers\Users\UpdateProfileController;
use Illuminate\Support\Facades\Route; // Or your Guzzle version adapter

Route::post('/auth', AnonymousAuthController::class)->name('anonymous.auth');
Route::middleware('auth:sanctum')->group(function () {
    Route::resource('reports', ReportController::class)->only(['index', 'store', 'show'])->names('api.reports');
    Route::get('/my-reports', MyReportsController::class)->name('my-reports');
    Route::resource('damage-types', DamageTypeController::class)->only('index');
    Route::resource('severities', SeverityController::class)->only('index');
    Route::post('/fcm-token', FCMTokenController::class)->name('fcm-token');
    Route::prefix('profile')->group(function () {
        Route::get('/', GetProfileController::class)->name('profile.show');
        Route::put('/', UpdateProfileController::class)->name('profile.update');
    });
    Route::group(['prefix' => 'notifications'], function () {
        Route::get('/', GetNotificationsController::class)->name('notifications.index');
        Route::get('/read-all', ReadNotificationsController::class)->name('notifications.read-all');
        Route::post('/read-one/{notification}', ReadOneNotificationController::class)->name('notifications.read-one');
    });
});
