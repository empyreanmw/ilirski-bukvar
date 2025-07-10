<?php

namespace App\Http\Controllers\Pages;

use App\Enums\ContentEntity;
use App\Enums\ContentType;
use App\Http\Controllers\Controller;
use App\Models\AppSettings;
use App\Models\Series;
use App\Repositories\ContentRepository;
use Inertia\Inertia;
use Inertia\Response;

class FavoritesIndexController extends Controller
{
    /**
     * @throws \Throwable
     */
    public function __invoke(Series $series, ContentRepository $contentRepository): Response
    {
        $appMode = AppSettings::first()->mode;
        $activeTab = request()->tab ?? ContentEntity::SERIES->value;

        return Inertia::render('Favorites', [
            'books' => $contentRepository->getFavoriteContent(ContentType::BOOK),
            'videos' => $contentRepository->getFavoriteContent(ContentType::VIDEO),
            'series' => $contentRepository->getFavoriteSeries($appMode),
            'tab' => ucfirst($activeTab),
        ]);
    }
}
