<?php

namespace App\Console\Commands;

use App\Enums\PlayerType;
use App\Models\Content;
use App\Services\ExtensionFinderService;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;

class FileDownloader extends Command
{
    const BASE_URL = 'https://drive.usercontent.google.com/download?id=';
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:file-downloader {url} {path} {name} {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    protected string $url;

    /**
     * Execute the console command.
     * @throws \Exception
     * @throws GuzzleException
     */
    public function handle(ExtensionFinderService $extensionFinderService): void
    {
        $client = new Client();
        $this->url = $this->argument('url');
        $path = $this->argument('path');
        $name = $this->argument('name');
        $content = Content::find($this->argument('id'));

        if ($this->isConfirmationNeeded($content)) {
            $this->confirmDownload();
        }

        if (!file_exists($this->argument('path'))) {
            mkdir($this->argument('path'), 0755, true);
        }

        $response = $client->request('GET', $this->url, ['verify' => false]);
        $filePath = $this->buildFileName(
            $path,
            $name,
            $extensionFinderService->getExtensionFromResponse($response, $content)
        );
        $client->request('GET', $this->url, [
            'verify' => false,
            'sink' => $filePath,
            'progress' => function ($total, $downloaded) use ($name, $filePath) {
                if ($total === 0) {
                    $this->info(sprintf('[download] Destination: %s', $filePath));
                }
                if ($total > 0) {
                    $percent = round(($downloaded / $total) * 100);
                    $this->info(
                        "percent: $percent,
                         total: $total,
                         downloaded: $downloaded,
                         name: $name"
                    );
                }
            },
        ]);
    }

    protected function buildFileName(string $path, string $name, string $extension): string
    {
        return sprintf('%s/%s.%s', $path, $name, $extension);
    }

    protected function isConfirmationNeeded(Content $content): bool
    {
        return $content->player_type === PlayerType::GOOGLE_DRIVE;
    }

    protected function confirmDownload(): void
    {
        $client = new Client([
            'cookies' => true,
            'headers' => [
                'User-Agent' => 'Mozilla/5.0',
            ],
        ]);

        $response = $client->get($this->url);
        $body = $response->getBody()->getContents();

        preg_match_all('/<input[^>]+name="(id|confirm|uuid|at)"[^>]+value="([^"]*)"/', $body, $matches, PREG_SET_ORDER);
        foreach ($matches as $match) {
            $tokens[$match[1]] = $match[2];
        }

        $this->url = sprintf('%s%s&export=download&confirm=%s&uuid=%s', self::BASE_URL, $tokens['id'], $tokens['confirm'], $tokens['uuid']);

        // sometimes there is at field that also needs to be appended to URL
        if(isset($tokens['at'])) {
            $this->url .= '&at=' . $tokens['at'];
        }
    }
}
