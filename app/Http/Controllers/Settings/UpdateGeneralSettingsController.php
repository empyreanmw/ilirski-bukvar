<?php
namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\UpdateGeneralSettingsRequest;
use App\Models\AppSettings;
use Inertia\Inertia;

class UpdateGeneralSettingsController extends Controller
{
    public function __invoke(UpdateGeneralSettingsRequest $request)
    {
        $request->validated();
        $appSettings = AppSettings::first();

        $appSettings->content_per_page = $request->content_per_page;
        $appSettings->video_player_path = $request->video_player_path;
        $appSettings->book_reader_path = $request->book_reader_path;

        $appSettings->save();

        return Inertia::render('settings/General', [
            'appSettings' => $appSettings,
        ]);
    }
}
