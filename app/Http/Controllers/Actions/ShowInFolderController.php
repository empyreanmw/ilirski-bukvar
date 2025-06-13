<?php

namespace App\Http\Controllers\Actions;

use App\Factories\ContentEntityFactory;
use App\Http\Controllers\Controller;
use App\Http\Requests\Actions\ShowInFolderRequest;
use App\Services\File\FileService;
use Native\Laravel\Facades\Shell;

class ShowInFolderController extends Controller
{
    /**
     * @throws \Throwable
     */
    public function __invoke(ShowInFolderRequest $request, ContentEntityFactory $entityFactory, FileService $pathFinderService)
    {
        $request->validated();
        $content = $entityFactory->create($request->contentEntity, $request->id);

        Shell::showInFolder(
            $pathFinderService->findPath($request->contentEntity, $content)
        );

        return response(status: 200);
    }
}
