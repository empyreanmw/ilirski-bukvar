<?php

use App\Http\Controllers\Pages\ContentIndexController;
use App\Http\Controllers\Pages\DownloadManagerIndexController;
use App\Http\Controllers\Pages\FavoritesIndexController;
use App\Http\Controllers\Pages\PodcastsIndexController;
use App\Http\Controllers\Pages\HealthIndexController;
use App\Http\Controllers\Pages\HistoryIndexController;
use App\Http\Controllers\Pages\MoviesIndexController;
use App\Http\Controllers\Pages\TheologyIndexController;
use App\Http\Middleware\AppMode;
use App\Http\Middleware\ContentChecker;
use App\Http\Middleware\HandleMissingDownloadPath;

//Pages
Route::middleware([AppMode::class, HandleMissingDownloadPath::class, ContentChecker::class])->group(function () {
    Route::get('history', HistoryIndexController::class)->name('history');
    Route::get('theology', TheologyIndexController::class)->name('theology');
    Route::get('health', HealthIndexController::class)->name('health');
    Route::get('podcasts', PodcastsIndexController::class)->name('podcasts');
    Route::get('movies', MoviesIndexController::class)->name('movies');
    Route::get('favorites', FavoritesIndexController::class)->name('favorites-index');
    Route::get('download-manager', DownloadManagerIndexController::class)->name('download-manager');
    Route::get('content/{series}', ContentIndexController::class)->name('content-index');
});
