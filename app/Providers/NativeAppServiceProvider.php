<?php

namespace App\Providers;

use App\Models\AppSettings;
use Illuminate\Support\Facades\Artisan;
use Native\Laravel\Facades\Menu;
use Native\Laravel\Facades\Window;
use Native\Laravel\Contracts\ProvidesPhpIni;

class NativeAppServiceProvider implements ProvidesPhpIni
{
    /**
     * Executed once the native application has been booted.
     * Use this method to open windows, register global shortcuts, etc.
     */
    public function boot(): void
    {
        Menu::create(
            Menu::file(),
            Menu::view(),
            Menu::window()
        );

        Window::open()
            ->width(1200)
            ->height(800);
    }

    /**
     * Return an array of php.ini directives to be set.
     */
    public function phpIni(): array
    {
        return [
        ];
    }
}
