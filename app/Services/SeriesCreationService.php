<?php
namespace App\Services;

use App\Models\Series;
use App\Services\ContentSeeders\SeriesCreation;
use Illuminate\Support\Facades\File;
use Throwable;

class SeriesCreationService
{
    use SeriesCreation;

    public function deleteSeries(array $content)
    {
        $series = Series::where('slug', $content['slug'])->first();

        if (File::exists(public_path($series->thumbnail))) {
            try {
                File::delete(public_path($series->thumbnail));
            } catch (Throwable $e) {
                report($e);
            }
        }

        if ($series) {
            $series->delete();
        }
    }
}