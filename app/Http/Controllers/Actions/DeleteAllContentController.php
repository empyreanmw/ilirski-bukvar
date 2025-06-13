<?php

namespace App\Http\Controllers\Actions;

use App\Enums\ContentType;
use App\Http\Controllers\Controller;
use App\Models\AppSettings;
use App\Models\Content;
use App\Models\Job;
use App\Models\JobRequest;
use App\Services\File\FileService;
use Illuminate\Support\Facades\File;

class DeleteAllContentController extends Controller
{
    /**
     * @throws \Throwable
     */
    public function __invoke(
        FileService $pathFinderService,
    )
    {
        $path = AppSettings::first()->download_path;

        if (File::exists($path) && File::isDirectory($path)) {
            File::deleteDirectory(sprintf('%s/%s', $path, ContentType::VIDEO->value));
            File::deleteDirectory(sprintf('%s/%s', $path, ContentType::BOOK->value));

            // set all content downloaded status to false
            Content::query()
                ->update(['downloaded' => false, 'local_url' => null]);
            // Delete any JobRequest
            JobRequest::query()
                ->delete();
            // Delete any Jobs
            Job::query()
                ->delete();

            return response()->json(['status' => 'deleted']);
        }

        return response(status: 200);
    }
}
