<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReportsHeatmapController;
use App\Http\Controllers\ReportsMapController;
use App\Models\Image;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('dashboard', DashboardController::class)->name('dashboard');
    Route::get('reports-map', ReportsMapController::class)->name('reports-map');
    Route::get('reports-heatmap', ReportsHeatmapController::class)->name('reports-heatmap');
    Route::resource('reports', ReportController::class)->only(['index', 'show', 'update']);
});

Route::get('/image/{image}', function (Image $image) {
    $mimeType = Storage::disk('private')->mimeType($image->path);
    $file = Storage::disk('private')->get($image->path);

    return response($file, 200)->header('Content-Type', $mimeType);
})->name('images.show');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
