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
         return $this->normalizeWindowsPath((string) $content->local_url, true);
    }

    protected function findSeriesPath(Series $series): string
    {
        $appSettings = AppSettings::first();

        return sprintf('%s\%s\%s', $appSettings->download_path, ContentType::VIDEO->value, $series->slug);
    }

    private function normalizeWindowsPath(string $p, bool $preferBackslashes = true): string
    {
        // strip wrapping quotes & whitespace & control chars (incl. \x0B)
        $p = trim($p, " \t\n\r\0\x0B\"'");

        // unify to forward slashes first
        $p = str_replace('\\', '/', $p);

        // collapse multiple slashes; keep single after drive ("C:/")
        $p = preg_replace('#^([A-Za-z]:)/*#', '$1/', $p); // C:////foo -> C:/foo
        $p = preg_replace('#/{2,}#', '/', $p);           // // -> /

        // optional: convert back to backslashes for Windows shell
        if ($preferBackslashes) {
            $p = str_replace('/', '\\', $p);
        }

        return $p;
    }
}
