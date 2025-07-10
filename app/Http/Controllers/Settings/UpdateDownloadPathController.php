<?php
namespace App\Http\Controllers\Settings;

use App\Enums\VideoQuality;
use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\UpdateDownloadSettingsRequest;
use App\Models\AppSettings;
use Inertia\Inertia;

class UpdateDownloadPathController extends Controller
{
    private const APP_CONTENT_ROOT_FOLDER = 'IlirskiBukvar';
    public function __invoke(UpdateDownloadSettingsRequest $request)
    {
        $request->validated();
        $appSettings = AppSettings::first();

        $appSettings->download_path = sprintf('%s\%s', $request->download_path, self::APP_CONTENT_ROOT_FOLDER);
        $appSettings->save();

        return back();
    }
}
