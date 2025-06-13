<?php

namespace App\Providers;

use App\Enums\QueueJobRequestStatus;
use App\Listeners\DownloadFinishedListener;
use App\Models\JobRequest;
use App\Observers\JobRequestObserver;
use App\Services\Downloader\FileDownloader;
use App\Services\Downloader\Interfaces\DownloaderInterface;
use App\Services\Downloader\YoutubeDownloader;
use App\Services\ExtensionFinderService;
use App\Services\JobService;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(FileDownloader::class, function ($app) {
            return new FileDownloader(
                $app->make(ExtensionFinderService::class)
            );
        });
        $this->app->bind(YoutubeDownloader::class, function ($app) {
            return new YoutubeDownloader(
                $app->make(ExtensionFinderService::class)
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Queue::before(function (JobProcessing $event): void {
            app(JobService::class)->updateQueueJobRequestStatus(QueueJobRequestStatus::IN_PROGRESS, $event->job);
        });
        Queue::after(function (JobProcessed $event): void {
            app(JobService::class)->updateQueueJobRequestStatus(QueueJobRequestStatus::DONE, $event->job);
        });
        Queue::failing(function (JobFailed $event) {
            app(JobService::class)->setQueueRequestToFail($event->job);
        });

        JobRequest::observe(JobRequestObserver::class);
    }
}
