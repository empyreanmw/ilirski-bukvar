<?php
namespace App\Services\File;

use App\Enums\ContentEntity;
use App\Enums\ContentType;
use App\Models\AppSettings;
use App\Models\Content;
use App\Models\Series;

class PathFinderService
{
    public function findPath(string $entityType, Series|Content $entity): string
    {
        return match ($entityType) {
            ContentEntity::CONTENT->value => $this->findContentPath($entity),
            ContentEntity::SERIES->value => $this->findSeriesPath($entity),
        };
    }

    protected function findContentPath(Content $content): string
    {
        return $content->local_url;
    }

    protected function findSeriesPath(Series $series): string
    {
        $appSettings = AppSettings::first();

        return sprintf('%s\%s\%s', $appSettings->download_path, ContentType::VIDEO->value, $series->slug);
    }
}
