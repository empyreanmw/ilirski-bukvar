<?php
namespace App\Http\Controllers\Settings;

use App\Enums\VideoQuality;
use App\Http\Controllers\Controller;
use App\Models\AppSettings;
use App\Models\Category;
use Inertia\Inertia;

class DownloadController extends Controller
{

    public function __invoke()
    {
        $appSettings = AppSettings::first();

        return Inertia::render('settings/Downloads', [
            'appSettings' => $appSettings,
            'videoQualities' => VideoQuality::cases(),
            'categories' => Category::all()
        ]);
    }
}
