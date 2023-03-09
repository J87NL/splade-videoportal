<?php

use App\Http\Controllers\ActivitylogsController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\VideosController;
use App\Http\Controllers\ViewcountController;
use Illuminate\Support\Facades\Route;

Route::middleware('splade')->group(function () {
    Route::spladeTable();
    Route::spladeUploads();

    Route::middleware('auth')->group(function () {
        Route::get('/', [VideosController::class, 'index'])->name('dashboard');
        Route::get('/levels', [VideosController::class, 'indexWithLevels'])->name('dashboard-levels');
        Route::get('/video/{video}', [VideosController::class, 'show'])->name('videos.show');
        Route::get('/videos/{video}.mp4', [VideosController::class, 'file'])->name('videos.file');
        Route::post('/viewcount/{video}', [ViewcountController::class, 'store'])->name('viewcount.store');
    });

    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::redirect('/', '/admin/videos');
        Route::resource('/dances', Admin\DanceController::class)->except('show');
        Route::resource('/dancetypes', Admin\DanceypesController::class)->except('show');
        Route::resource('/levels', Admin\LevelController::class)->except('show');
        Route::resource('/settings', Admin\SettingController::class)->except('show');
        Route::resource('/videos', Admin\VideoController::class)->except('show');

        Route::resource('/users', Admin\UserController::class)->except('show');

        Route::get('/activitylogs', ActivitylogsController::class)->name('activitylogs');

        Route::get('/artisan/{command}', Admin\ArtisanController::class);
    });

    require __DIR__.'/auth.php';
});

Route::webhooks('webhook/{signature}');
