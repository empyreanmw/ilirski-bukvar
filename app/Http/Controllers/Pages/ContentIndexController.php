<?php

namespace App\Http\Controllers\Pages;

use App\Enums\ContentType;
use App\Http\Controllers\Controller;
use App\Models\AppSettings;
use App\Models\Content;
use App\Models\Series;
use App\Repositories\ContentRepository;
use App\Repositories\SearchRepository;
use Inertia\Inertia;
use Inertia\Response;

class ContentIndexController extends Controller
{
    /**
     * @throws \Throwable
     */
    public function __invoke(Series $series, ContentRepository $contentRepository, SearchRepository $searchRepository): Response
    {
        $appMode = AppSettings::first()->mode;

        return Inertia::render('ContentList', [
            'content' => $contentRepository->getSeriesContent([ContentType::MOVIE, ContentType::VIDEO, ContentType::CARTOON], $appMode, $series),
            'category' => $series->category->name,
            'activeItem' => (int)request()->active_item,
            'series' => $series,
            'lastWatchedUrl' => $this->getLastWatchedUrl($contentRepository, $searchRepository, $series)
        ]);
    }

    protected function getLastWatchedUrl(ContentRepository $contentRepository, SearchRepository $searchRepository, Series $series) 
    {
        return $searchRepository->getLastWatchedContentLink(
            $series->lastWatched(),
            Content::query()->modeAwareContent(ContentType::VIDEO)->where('parent_id', $series->id)->get()
        );
    }
}
