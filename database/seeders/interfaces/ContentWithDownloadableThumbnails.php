<?php

namespace Database\Seeders\interfaces;

interface ContentWithDownloadableThumbnails
{
    public function getFileName(array $contentData): string;
    public function downloadThumbnail(string $url, string $filename): ?string;
}
