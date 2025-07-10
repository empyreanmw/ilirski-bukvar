<?php

namespace App\Services\Downloader;

use App\Events\DownloadOutput;
use App\Models\AppSettings;
use App\Models\Content;
use App\Dtos\DownloadOutput as DownloadOutputDto;
use App\Services\Slugify;
use Exception;
use File;
use Illuminate\Support\Facades\Process;
use SplFileInfo;

class YoutubeDownloader extends Downloader
{
    private const YT_DLP_EXE_NAME = 'yt-dlp.exe';
    private const DEFAULT_EXTENSION = 'mp4';
    private const FFMPEG_PATH = 'ffmpeg\bin\ffmpeg.exe';
    private const YT_DLP_UPDATE_COMMAND = '-U';

    /**
     * @throws Exception
     */
    public function handleBufferOutput($buffer): void
    {
        if ($this->hasError($buffer)) {
            throw new Exception(sprintf('Error downloading video. Reason: %s', $buffer));
        }
        if (!str_contains($buffer, '[download]')) {
            return;
        }

        $buffer = $this->extractProgressInfo($buffer);

        if ($buffer === null) {
            return;
        }

        broadcast(new DownloadOutput(
            new DownloadOutputDto(
                downloaded: $buffer[0]['downloaded'],
                percent: $buffer[0]['percent'],
                total: $buffer[0]['total'],
                content: $this->getContent()->serializeData()
            )
        ));

        if ($buffer[0]['percent'] === 100) {
            $this->downloadFinished($buffer[0]['total']);
            $this->moveThumbnails();
        }
    }

    public function getCommand(Content $content): array
    {
        $title = Slugify::run($content->title);

        return [
            $this->getExecutablePath(),
            '--ffmpeg-location', base_path(self::FFMPEG_PATH),
            '--write-thumbnail',                    // download the thumbnail
            '--convert-thumbnails', 'jpg',           // convert thumbnail to jpg
            '--paths', $this->getOutputPath(),       // path for videos
            '-o', "$title.%(ext)s",                  // output template for both video and thumbnail
            '-f', "best[height<={$this->getVideoQuality()}]",
            '--',
            $content->download_url
        ];
    }

    protected function getVideoQuality()
    {
        $appSettings = AppSettings::first();

        return $appSettings->video_quality;
    }

    public function getExecutablePath(): string
    {
        return base_path(self::YT_DLP_EXE_NAME);
    }

    public function extractProgressInfo(string $buffer): ?array
    {
        $lines = preg_split('/\r\n|\r|\n/', $buffer);
        $lines = array_filter(array_map('trim', $lines));
        $lastDownloadLine = end($lines);

        // Try to extract percent and total
        if (preg_match('/\[download\]\s+([\d.]+)% of\s+([\d.]+)MiB/i', $lastDownloadLine, $match)) {
            $percent = (float) $match[1];
            $totalMiB = (float) $match[2];
            $downloadedMiB = round(($percent / 100) * $totalMiB, 2);

            return [[
                'percent' => (int) $percent,
                'total' => (int) round($totalMiB),
                'downloaded' => (int) round($downloadedMiB),
            ]];
        }

        return null;
    }

    private function hasError(string $output): bool
    {
        // Normalize line endings and split
        $lines = preg_split('/\r\n|\r|\n/', $output);

        foreach ($lines as $line) {
            if (stripos($line, 'error:') !== false) {
                return true;
            }
        }

        return false;
    }

    public function getFilePath(): string
    {
        return sprintf('%s%s.%s',$this->getOutputPath(), Slugify::run($this->getContent()->title), self::DEFAULT_EXTENSION);
    }

    public function getThumbnailPath(bool $relativePath = false): string
    {
        $path = sprintf('\thumbnails\\series\\%s\\', $this->getContent()->parent->slug);

        if ($relativePath === true) {
            return $path;
        }

        return public_path($path);
    }

    /**
     * @throws Exception
     */
    private function moveThumbnails(): void
    {
        if (!File::isDirectory($this->getOutputPath())) {
            throw new \Exception("Source directory does not exist");
        }

        if (!File::isDirectory($this->getThumbnailPath())) {
            File::makeDirectory($this->getThumbnailPath(), 0755, true);
        }

        $files = File::files($this->getOutputPath());

        foreach ($files as $file) {
            if ($file->getExtension() === 'jpg') {
                File::move($file->getRealPath(), $this->getThumbnailPath() . $file->getFilename());
                $this->addThumbnailUrlToContent($file);
            }
        }
    }

    private function addThumbnailUrlToContent(SplFileInfo $file): void
    {
        $this->getContent()->thumbnail_url = $this->getThumbnailPath(true) . $file->getFilename();
        $this->getContent()->save();
    }

    protected function prepareDownload(): void
    {
        // try to update yt-dlp
        Process::run(
            sprintf('%s %s', $this->getExecutablePath(), self::YT_DLP_UPDATE_COMMAND)
        );
    }
}
