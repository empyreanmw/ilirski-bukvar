<?php

namespace App\Listeners;

use App\Events\DownloadFinished;
use App\Models\Content;

class DownloadFinishedListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(DownloadFinished $downloadFinished): void
    {
        // due to the way how laravel does model serialization, i decided to pass content as array here
        // and then find the content model itself
        $content = Content::find($downloadFinished->content['id']);
        $content->update(
            [
                'downloaded' => true,
                'local_url' => $downloadFinished->url,
                'size' => $downloadFinished->size
            ]
        );
    }
}
