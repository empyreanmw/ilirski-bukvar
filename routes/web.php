<?php
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

require __DIR__.'/actions.php';
require __DIR__.'/pages.php';
require __DIR__.'/settings.php';
