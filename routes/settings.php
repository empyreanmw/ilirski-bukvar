<?php

use App\Http\Controllers\Settings\DownloadController;
use App\Http\Controllers\Settings\GeneralController;
use App\Http\Controllers\Settings\UpdateDownloadControllerSettings;
use App\Http\Controllers\Settings\UpdateDownloadPathController;
use App\Http\Controllers\Settings\UpdateGeneralSettingsController;
use App\Http\Middleware\AppMode;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware([AppMode::class])->group(function () {
    Route::redirect('settings', '/settings/general');
    Route::get('settings/appearance', function () {
        return Inertia::render('settings/Appearance');
    })->name('appearance');
    Route::get('settings/general', GeneralController::class)->name('settings.general');
    Route::put('settings/general', UpdateGeneralSettingsController::class )->name('settings.update-general');
    Route::get('settings/downloads', DownloadController::class)->name('settings.downloads');
    Route::put('settings/downloads', UpdateDownloadControllerSettings::class )->name('settings.update-downloads');
    Route::put('settings/download-path', UpdateDownloadPathController::class )->name('settings.update-download-path');
});

