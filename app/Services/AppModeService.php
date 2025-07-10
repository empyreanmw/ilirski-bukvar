<?php
namespace App\Services;

use App\Enums\AppMode as AppModeEnum;
use App\Enums\AppModeSwitch;
use App\Models\AppSettings;
use Exception;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;

class AppModeService
{
    public function setMode(AppModeSwitch $appModeSwitch, ?string $mode = null): void
    {
        $appSettings = AppSettings::first();

        if ($mode !== null) {
            $appSettings->mode = $mode;
            $appSettings->save();
        }

        Inertia::share([
            'appMode' => [
                'mode' => $appSettings->mode,
                'switch' => $appModeSwitch
            ]
        ]);
    }

    public function checkConnection(): void
    {
        try {
            Http::timeout(10)->get('https://www.google.com/generate_204');
        } catch (Exception) {
            $this->setMode(AppModeSwitch::AUTOMATIC, AppModeEnum::OFFLINE->value);

            return;
        }

        $this->setMode(AppModeSwitch::MANUAL);
    }
}
