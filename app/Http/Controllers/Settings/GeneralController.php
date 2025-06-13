<?php
namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\AppSettings;
use Inertia\Inertia;

class GeneralController extends Controller
{
    public function __invoke()
    {
        $appSettings = AppSettings::first();

        return Inertia::render('settings/General', [
            'appSettings' => $appSettings,
        ]);
    }
}
