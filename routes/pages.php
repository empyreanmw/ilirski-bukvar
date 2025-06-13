<?php

use App\Http\Controllers\Pages\ContentIndexController;
use App\Http\Controllers\Pages\DownloadManagerIndexController;
use App\Http\Controllers\Pages\FavoritesIndexController;
use App\Http\Controllers\Pages\FriendsIndexController;
use App\Http\Controllers\Pages\HealthIndexController;
use App\Http\Controllers\Pages\HistoryIndexController;
use App\Http\Controllers\Pages\MoviesIndexController;
use App\Http\Controllers\Pages\TheologyIndexController;

//Pages
Route::middleware('auth')->group(function () {
    Route::get('history', HistoryIndexController::class)->name('history');
    Route::get('theology', TheologyIndexController::class)->name('theology');
    Route::get('health', HealthIndexController::class)->name('health');
    Route::get('friends', FriendsIndexController::class)->name('friends');
    Route::get('movies', MoviesIndexController::class)->name('movies');
    Route::get('favorites', FavoritesIndexController::class)->name('favorites-index');
    Route::get('download-manager', DownloadManagerIndexController::class)->name('download-manager');
    Route::get('content/{series}', ContentIndexController::class)->name('content-index');
});
