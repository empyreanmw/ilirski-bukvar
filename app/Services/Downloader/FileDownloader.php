<?php

namespace App\Services\Downloader;

use App\Events\DownloadOutput;
use App\Models\Content;
use App\Dtos\DownloadOutput as DownloadOutputDto;

class FileDownloader extends Downloader
{
    private ?int $currentPercent = null;
    private bool $downloadFinished = false;
    public function handleBufferOutput($buffer): void
    {
        foreach ($this->parseProgressBuffer($buffer) as $line) {
            if ($this->currentPercent !== $line['percent']) {
                broadcast(new DownloadOutput(
                    new DownloadOutputDto(
                        round($line['downloaded'] / 1000, 2),
                        $line['percent'],
                        round($line['total'] / 1000,2),
                        $this->getContent()->serializeData()
                    )
                ));
            }
            $this->currentPercent = $line['percent'] ?? 0;

            if ($line['percent'] === 100 && !$this->downloadFinished) {
                $this->downloadFinished = true;
                $this->downloadFinished($line['total'] / 1000);
            }
        }
    }

    public function getCommand(Content $content): array
    {
        return [
            PHP_BINARY,
            'artisan',
            'app:file-downloader',
            $content->download_url,
            $this->getOutputPath(),
            $content->title,
            $content->id
        ];
    }

    public function parseProgressBuffer(string $buffer): array
    {
        // Normalize spacing and line endings
        $buffer = preg_replace('/[ \t]+/', ' ', trim($buffer));

        // Split the buffer into lines
        $lines = preg_split('/\r?\n/', $buffer);

        $entries = [];
        $current = [];

        foreach ($lines as $line) {
            $line = trim($line, ", ");

            if (str_starts_with($line, 'percent:')) {
                $current = []; // Start a new entry
            }

            if (preg_match('/^(\w+):\s*(.+)$/', $line, $matches)) {
                $key = $matches[1];
                $value = trim($matches[2], ',');

                // Cast to int if numeric
                if (is_numeric($value) && $key !== 'name') {
                    $value = (int)$value;
                }

                $current[$key] = in_array($key, ['downloaded', 'total']) ? round((int)$value / 1000) : $value;
            }

            // Once we have 4 keys, store the entry
            if (count($current) === 4) {
                $entries[] = $current;
            }
        }

        // Remove duplicate entries
        return array_values(array_unique($entries, SORT_REGULAR));
    }

    public function getFilePath(): string
    {
        return sprintf(
            '%s%s.%s',
            $this->getOutputPath(),
            $this->getContent()->title,
            $this->extensionFinderService->getExtensionFromContent($this->getContent())
        );
    }
}
