<?php
namespace App\Services\File;

use App\Models\AppSettings;
use App\Models\Content;
use App\Models\Series;
use Exception;

readonly class FileService
{
    public function __construct(
        private OpenFileService   $openFileService,
        private PathFinderService $pathFinderService,
        private DialogService     $dialogService
    ){}
    /**
     * @throws Exception
     */
    public function findPath(string $entityType, Series|Content $entity): string
    {
        return $this->pathFinderService->findPath($entityType, $entity);
    }

    public function openDialog(string $fileType, ?string $defaultPath)
    {
        return $this->dialogService->open($fileType, $defaultPath);
    }

    public function openFile(AppSettings $appSettings, Content $content): void
    {
        $this->openFileService->open($appSettings, $content);
    }
}
