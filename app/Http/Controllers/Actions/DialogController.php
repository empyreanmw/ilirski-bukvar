<?php

namespace App\Http\Controllers\Actions;

use App\Http\Controllers\Controller;
use App\Http\Requests\Actions\DialogRequest;
use App\Services\File\FileService;

class DialogController extends Controller
{
    /**
     * @throws \Throwable
     */
    public function __invoke(DialogRequest $request, FileService $fileService)
    {
        $request->validated();

        return response()->json([
            'path' => $fileService->openDialog($request->file_type, $request->default_path),
        ]);
    }
}
