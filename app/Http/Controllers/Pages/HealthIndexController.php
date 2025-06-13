<?php

namespace App\Http\Controllers\Pages;

use App\Enums\Category;
use App\Enums\ContentType;
use App\Http\Controllers\Controller;
use App\Models\AppSettings;
use App\Models\Category as CategoryModel;
use App\Models\Series;
use App\Repositories\ContentRepository;
use Inertia\Inertia;
use Inertia\Response;

class HealthIndexController extends Controller
{
    /**
     * @throws \Throwable
     */
    public function __invoke(Series $series, ContentRepository $contentRepository): Response
    {
        $appMode = AppSettings::first()->mode;
        $activeTab = request()->tab ?? ContentType::VIDEO->value;

        return Inertia::render('Health', [
            'bookSeries' => $contentRepository->getContentForCategory(Category::HEALTH, ContentType::BOOK, $appMode),
            'videoSeries' => $contentRepository->getContentWithSeries(Category::HEALTH, ContentType::VIDEO, $appMode),
            'tab' => ucfirst($activeTab),
            'activeItem' => (int)request()->active_item,
            'category' => CategoryModel::query()->where('name', Category::HISTORY->value)->first(),
        ]);
    }
}
