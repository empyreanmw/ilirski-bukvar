<?php

namespace App\Repositories;

use App\Enums\AppMode;
use App\Enums\Category;
use App\Enums\ContentType;
use App\Models\Category as CategoryModel;
use App\Models\Content;
use App\Models\Series;
use Illuminate\Database\Query\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ContentRepository
{
    /**
     * @throws \Throwable
     */
    public function getContentForCategory(Category $category, ContentType $contentType, AppMode $appMode)
    {
        $category = $this->findCategory($category);

        return match ($appMode) {
            AppMode::OFFLINE => $category->offlineContent([$contentType]),
            AppMode::ONLINE => $category->onlineContent([$contentType])
        };
    }

    public function getContentWithSeries(Category $category, ContentType $contentType, AppMode $appMode): LengthAwarePaginator
    {
        $category = $this->findCategory($category);

        $series = Series::query()
            ->where('category_id', $category->id)
            ->whereHas('content', function ($builder) use ($contentType, $appMode) {
                $builder->where('type', $contentType);
            })
            ->with('author')
            ->paginate(10);

        $series->getCollection()->transform(function (Series $item) use ($appMode, $category, $contentType) {
                $item->category = $category->name;
                return $item;
        });

        return $series;
    }

    protected function findCategory(Category $category): ?CategoryModel
    {
        return CategoryModel::query()
            ->where('name', $category)
            ->first();
    }

    public function getSeriesContent(array $contentType, AppMode $appMode, Series $series)
    {
        return match ($appMode) {
            AppMode::OFFLINE => $series->offlineContent($contentType),
            AppMode::ONLINE => $series->onlineContent($contentType),
        };
    }

    public function getFavoriteContent(ContentType $contentType): LengthAwarePaginator
    {
        return Content::query()
            ->modeAwareContent($contentType)
            ->where('is_favorite', true)->paginate(10);
    }

    public function getFavoriteSeries(AppMode $appMode): LengthAwarePaginator
    {
        $query = Series::query()
            ->where('is_favorite', true)
            ->with('author');

        if ($appMode === AppMode::OFFLINE) {
            $query = $query->whereHas('content', function ($builder){
                $builder->where('downloaded', true);
            });
        }

        return $query->paginate(10);
    }

    public function getNonDownloadedContent(array $categories, string $type = null): Collection
    {
        $content = Content::where(function ($query) use ($categories) {
            // Content where parent is directly a category
            $query->where('parent_type', CategoryModel::class)
                ->whereIn('parent_id', $categories);
        })
            ->orWhere(function ($query) use ($categories) {
                // Content where parent is a series that belongs to a category
                $query->where('parent_type', Series::class)
                    ->whereIn('parent_id', function ($subQuery) use ($categories) {
                        $subQuery->select('id')
                            ->from('series')
                            ->whereIn('category_id', $categories);
                    });
            })
            ->with(['author', 'parent']);

        if ($type !== null) {
            return $content->where('type', $type)->get();
        }

        return $content->get();
    }
}
