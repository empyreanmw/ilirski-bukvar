<?php
namespace App\Services;

use App\Enums\AppMode as AppModeEnum;
use App\Models\AppSettings;
use Exception;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;

class AppModeService
{
    /**
     * @throws \Exception
     */
    public function setMode(?string $mode = null): void
    {
        $appSettings = AppSettings::first();

        try {
            Http::timeout(10)->get('https://www.google.com');
        } catch (Exception) {
            Inertia::share([
                'appMode' => [
                    'mode' => $appSettings->mode,
                    'switch' => 'automatic'
                ]
            ]);
            $appSettings->update(['mode' => AppModeEnum::OFFLINE->value]);

            return;
        }

        if ($mode !== null) {
            $appSettings->mode = $mode;
            $appSettings->save();
        }

        Inertia::share([
            'appMode' => [
                'mode' => $appSettings->mode,
                'switch' => 'manual'
            ]
        ]);
    }
}
