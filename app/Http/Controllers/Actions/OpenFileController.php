<?php

namespace App\Http\Controllers\Actions;

use App\Http\Controllers\Controller;
use App\Models\AppSettings;
use App\Models\Content;
use App\Services\File\FileService;
use Illuminate\Http\Request;

class OpenFileController extends Controller
{
    /**
     * @throws \Throwable
     */
    public function __invoke(Request $request, FileService $fileService)
    {
        $appSettings = AppSettings::first();
        $content = Content::find($request->contentId);

        $fileService->openFile($appSettings, $content);
    }
}
