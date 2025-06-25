<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReportsHeatmapController;
use App\Http\Controllers\ReportsMapController;
use App\Http\Controllers\StoreReportUpdateController;
use App\Http\Middleware\NotAnonUser;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::middleware(['auth', NotAnonUser::class])->group(function () {
    Route::get('dashboard', DashboardController::class)->name('dashboard');
    Route::get('reports-map', ReportsMapController::class)->name('reports-map');
    Route::get('reports-heatmap', ReportsHeatmapController::class)->name('reports-heatmap');
    Route::resource('reports', ReportController::class)->only(['index', 'show', 'update']);
    Route::post('/reports/{report}/updates', StoreReportUpdateController::class)->name('reports.updates.store');
});

Route::get('/image/{image}', ImageController::class)->middleware('auth:sanctum')->name('images.show');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
