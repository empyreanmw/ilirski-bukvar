<?php

use App\Http\Controllers\Actions\AppModeController;
use App\Http\Controllers\Actions\CancelAllDownloadController;
use App\Http\Controllers\Actions\CancelDownloadController;
use App\Http\Controllers\Actions\ContentDownloadController;
use App\Http\Controllers\Actions\GetActiveDownloadsController;
use App\Http\Controllers\Actions\OpenFileController;
use App\Http\Controllers\Actions\SearchController;
use App\Http\Controllers\Actions\DeleteAllContentController;
use App\Http\Controllers\Actions\DialogController;
use App\Http\Controllers\Actions\DownloadAllController;
use App\Http\Controllers\Actions\FavoriteController;
use App\Http\Controllers\Actions\SeriesDownloadController;
use App\Http\Controllers\Actions\ShowInFolderController;

//Actions
Route::post('content/download', ContentDownloadController::class)->name('download-content');
Route::post('content/favorite', FavoriteController::class)->name('favorite-content');
Route::post('series/download', SeriesDownloadController::class)->name('download-series');
Route::post('open-file', OpenFileController::class)->name('open-file');
Route::post('app-mode', AppModeController::class)->name('app-mode');
Route::post('show-in-folder', ShowInFolderController::class)->name('show-in-folder');
Route::post('dialog', DialogController::class)->name('dialog');
Route::post('search', SearchController::class)->name('search');
Route::get('cancel-download/{job_request}', CancelDownloadController::class)->name('cancel-download');
Route::post('cancel-all-downloads', CancelAllDownloadController::class)->name('cancel-all-downloads');
Route::post('download-all', DownloadAllController::class)->name('download-all');
Route::post('delete-all', DeleteAllContentController::class)->name('delete-all');
Route::get('content/downloads/active', GetActiveDownloadsController::class)->name('get-active-downloads');
