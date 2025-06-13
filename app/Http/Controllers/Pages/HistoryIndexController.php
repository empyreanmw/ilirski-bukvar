<?php

namespace App\Http\Controllers\Pages;

use App\Enums\Category;
use App\Enums\ContentType;
use App\Http\Controllers\Controller;
use App\Models\AppSettings;
use App\Models\Category as CategoryModel;
use App\Repositories\ContentRepository;
use Inertia\Inertia;
use Inertia\Response;

class HistoryIndexController extends Controller
{
    /**
     * @throws \Throwable
     */
    public function __invoke(ContentRepository $contentRepository): Response
    {
        $appMode = AppSettings::first()->mode;
        $activeTab = request()->tab ?? ContentType::VIDEO->value;

        return Inertia::render('History', [
            'bookSeries' => $contentRepository->getContentForCategory(Category::HISTORY, ContentType::BOOK, $appMode),
            'videoSeries' => $contentRepository->getContentWithSeries(Category::HISTORY, ContentType::VIDEO, $appMode),
            'tab' => ucfirst($activeTab),
            'activeItem' => (int)request()->active_item,
            'category' => CategoryModel::query()->where('name', Category::HISTORY->value)->first(),
        ]);
    }
}
