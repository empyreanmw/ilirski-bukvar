<?php

namespace App\Http\Controllers\Pages;

use App\Enums\ContentType;
use App\Http\Controllers\Controller;
use App\Models\AppSettings;
use App\Models\Series;
use App\Repositories\ContentRepository;
use Inertia\Inertia;
use Inertia\Response;

class ContentIndexController extends Controller
{
    /**
     * @throws \Throwable
     */
    public function __invoke(Series $series, ContentRepository $contentRepository): Response
    {
        $appMode = AppSettings::first()->mode;

        return Inertia::render('ContentList', [
            'content' => $contentRepository->getSeriesContent([ContentType::MOVIE, ContentType::VIDEO, ContentType::CARTOON], $appMode, $series),
            'category' => $series->category->name,
            'activeItem' => (int)request()->active_item,
            'series' => $series,
        ]);
    }
}
