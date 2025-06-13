<?php

namespace App\Http\Controllers\Pages;

use App\Enums\Category;
use App\Enums\ContentType;
use App\Http\Controllers\Controller;
use App\Models\AppSettings;
use App\Models\Series;
use App\Repositories\ContentRepository;
use Inertia\Inertia;
use Inertia\Response;

class MoviesIndexController extends Controller
{
    /**
     * @throws \Throwable
     */
    public function __invoke(Series $series, ContentRepository $contentRepository): Response
    {
        $appMode = AppSettings::first()->mode;
        $activeTab = request()->tab ?? ContentType::MOVIE->value;

        return Inertia::render('Movies', [
            'movieSeries' => $contentRepository->getContentWithSeries(Category::MOVIES, ContentType::MOVIE, $appMode),
            'cartoonSeries' => $contentRepository->getContentWithSeries(Category::MOVIES, ContentType::CARTOON, $appMode),
            'tab' => ucfirst($activeTab),
            'activeItem' => (int)request()->active_item,
        ]);
    }
}
