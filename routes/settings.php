<?php

use App\Http\Controllers\Settings\DownloadController;
use App\Http\Controllers\Settings\GeneralController;
use App\Http\Controllers\Settings\PasswordController;
use App\Http\Controllers\Settings\ProfileController;
use App\Http\Controllers\Settings\UpdateDownloadControllerSettings;
use App\Http\Controllers\Settings\UpdateGeneralSettingsController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware('auth')->group(function () {
    Route::redirect('settings', '/settings/general');

    Route::get('settings/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('settings/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('settings/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('settings/password', [PasswordController::class, 'edit'])->name('password.edit');
    Route::put('settings/password', [PasswordController::class, 'update'])->name('password.update');

    Route::get('settings/appearance', function () {
        return Inertia::render('settings/Appearance');
    })->name('appearance');
});

Route::get('settings/general', GeneralController::class)->name('settings.general');
Route::put('settings/general', UpdateGeneralSettingsController::class )->name('settings.update-general');
Route::get('settings/downloads', DownloadController::class)->name('settings.downloads');
Route::put('settings/downloads', UpdateDownloadControllerSettings::class )->name('settings.update-downloads');
