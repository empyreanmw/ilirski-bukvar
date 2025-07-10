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

class PodcastsIndexController extends Controller
{
    /**
     * @throws \Throwable
     */
    public function __invoke(Series $series, ContentRepository $contentRepository): Response
    {
        $appMode = AppSettings::first()->mode;
        $activeTab = request()->tab ?? ContentType::VIDEO->value;

        return Inertia::render('Podcasts', [
            'videoSeries' => $contentRepository->getContentWithSeries(Category::PODCASTS, ContentType::VIDEO, $appMode),
            'tab' => ucfirst($activeTab),
        ]);
    }
}
