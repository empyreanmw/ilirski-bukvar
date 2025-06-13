<?php

namespace App\Http\Controllers\Actions;

use App\Factories\ContentEntityFactory;
use App\Http\Controllers\Controller;
use App\Http\Requests\Actions\FavoriteRequest;
use App\Services\Downloader\DownloadService;

class FavoriteController extends Controller
{
    /**
     * @throws \Throwable
     */
    public function __invoke(
        FavoriteRequest $request,
        DownloadService $downloadService,
        ContentEntityFactory $entityFactory
    )
    {
        $request->validated();
        $contentEntity = $entityFactory->create($request->contentEntity, $request->id);

        $contentEntity->update(['is_favorite' => !$contentEntity->is_favorite]);

        return response(status: 200);
    }
}
