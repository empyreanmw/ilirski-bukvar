<?php

namespace App\Services\Downloader;

use App\Events\DownloadFinished;
use App\Models\AppSettings;
use App\Models\Content;
use App\Services\ExtensionFinderService;
use Exception;
use Symfony\Component\Process\Process;

abstract class Downloader
{
    protected Content $content;
    protected ?string $destination = null;

    public function __construct(protected readonly ExtensionFinderService $extensionFinderService) {
    }
    /**
     * @throws Exception
     */

    public function download(Content $content): void
    {
        $this->setContent($content);

        if (!file_exists($this->getOutputPath())) {
            mkdir($this->getOutputPath(), 0755, true);
        }
        $this->prepareDownload();
        $process = new Process($this->getCommand($content));
        $process->setTimeout(600); // Optional: timeout in seconds
        $process->setWorkingDirectory(base_path());
        $process->run(function ($type, $buffer) {
            $buffer = mb_convert_encoding($buffer, 'UTF-8', 'UTF-8');
            $this->handleBufferOutput($buffer);
        });
    }
    public function getOutputPath(): string
    {
        $downloadPath = AppSettings::first()->download_path;
        $path = sprintf('%s\%s\\', $downloadPath, $this->getContent()->type->value);

        if ($this->getContent()->hasSeries()) {
            $path = $path .  $this->getContent()->parent->slug . '\\';
        }

        return $path;
    }

    protected function setContent(Content $content): void
    {
        $this->content = $content;
    }

    protected function getContent(): Content
    {
        return $this->content;
    }

    protected function downloadFinished(float $size): void
    {
        $this->getContent()->downloaded = true;

        DownloadFinished::dispatch(
            $this->getContent()->serializeData(),
            $this->getFilePath(),
            $size
        );
    }

    protected function prepareDownload(): void
    {
    }
    abstract public function handleBufferOutput($buffer);
    abstract public function getCommand(Content $content): array;
    abstract public function getFilePath(): string;
}
