<?php

namespace App\Providers;

use App\Enums\ContentType;
use App\Enums\QueueJobRequestStatus;
use App\Listeners\ApplicationBootedListener;
use App\Models\AppSettings;
use App\Models\JobRequest;
use App\Observers\JobRequestObserver;
use App\Services\Downloader\FileDownloader;
use App\Services\Downloader\Interfaces\DownloaderInterface;
use App\Services\Downloader\YoutubeDownloader;
use App\Services\ExtensionFinderService;
use App\Services\JobService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\Paginator;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobProcessing;
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

        Builder::macro('smartPaginate', function (?ContentType $contentType = null) {
            $currentTab = request()->get('tab') ?? null;
            // we should set page 1 for pagination for the tab that is not currently active
            // otherwise when you switch to different page for an active tab
            // that page will also be considered for all other tabs
            // that could cause issue because inactive tab might not even have that many pages
            if ($contentType !== null && $currentTab !== null && $currentTab !== $contentType->value) {
                Paginator::currentPageResolver(function () {return 1;});
            }

            $perPage = AppSettings::first()->content_per_page ??= 10;

            /** @var Builder $this */
            return $this->paginate($perPage);
        });
    }
}
