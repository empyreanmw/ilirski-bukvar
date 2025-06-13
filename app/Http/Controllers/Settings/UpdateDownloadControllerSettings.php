<?php
namespace App\Http\Controllers\Settings;

use App\Enums\VideoQuality;
use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\UpdateDownloadSettingsRequest;
use App\Models\AppSettings;
use Inertia\Inertia;

class UpdateDownloadControllerSettings extends Controller
{
    public function __invoke(UpdateDownloadSettingsRequest $request)
    {
        $request->validated();
        $appSettings = AppSettings::first();

        $appSettings->download_path = $request->download_path;
        $appSettings->video_quality = $request->video_quality;
        $appSettings->save();

        return Inertia::render('settings/Downloads', [
            'appSettings' => $appSettings,
            'videoQualities' => VideoQuality::cases(),
        ]);
    }
}


