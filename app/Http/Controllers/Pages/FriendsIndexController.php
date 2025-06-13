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

class FriendsIndexController extends Controller
{
    /**
     * @throws \Throwable
     */
    public function __invoke(Series $series, ContentRepository $contentRepository): Response
    {
        $appMode = AppSettings::first()->mode;

        return Inertia::render('Friends', [
            'bookSeries' => $contentRepository->getContentForCategory(Category::HEALTH, ContentType::BOOK, $appMode),
            'videoSeries' => $contentRepository->getContentWithSeries(Category::HEALTH, ContentType::VIDEO, $appMode),
        ]);
    }
}
