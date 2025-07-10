<?php
namespace App\Repositories;

use App\Dtos\SearchResult;
use App\Enums\ContentEntity;
use App\Enums\ContentType;
use App\Models\AppSettings;
use App\Models\Category;
use App\Models\Content;
use App\Models\Series;
use Illuminate\Support\Collection;

class SearchRepository
{
    public function search(string $query): Collection
    {
        $content = collect()->merge($this->getSearchQuery($query));
        $searchableContent = $this->getSearchableContent();

        return $content->filter()->collapse()->map(function ($item) use ($searchableContent) {
            return new SearchResult(
                id: $item->id,
                title: $item->name ?? $item->title,
                category: $item instanceof Series? ucfirst($item->category->name): ucfirst($item->parent->name),
                author: $item->author?->name,
                thumbnail: $item->thumbnail ?? ($item->thumbnail_url === null ? $item->parent->thumbnail : $item->thumbnail_url),
                type: $item instanceof Content ? ucfirst($item->type->value) : ucfirst(ContentEntity::SERIES->value),
                href: $this->getLinkForItem($item, $searchableContent),
                parentType: $item->parent_type ?? null
            );
        });
    }

    protected function getLinkForItem(Series|Content $item, $results): string
    {
        if ($item instanceof Content) {
            if ($item->parent instanceof Series) {
                return sprintf(
                    '/content/%s?active_item=%s&page=%s',
                    $item->parent->id,
                    $item->id,
                    $this->findPage($item, $results)
                );
            }

            return sprintf(
                '/%s?tab=%s&active_item=%s&page=%s',
                $item->parent->name,
                $item->type->value,
                $item->id,
                $this->findPage($item, $results)
            );
        }

        return sprintf('/content/%s', $item->id);
    }

    public function findPage(Series|Content $item, $results): ?string
    {
        $perPage = AppSettings::first()->content_per_page;

        $query = match ($item->type) {
            ContentType::VIDEO => $results['videos'],
            ContentType::BOOK => $results['books'],
            default => $results['series'],
        };

        // if parent is series, we need to retrieve only videos from that series
        if ($item->parent instanceof Series) {
            $query = $query->where('parent_id', $item->parent_id);
        }

        // if we are searching for book, make sure we find a page based on the category of that book
        // dont search thorugh all books
        if ($item->parent_type === Category::class) {
            $query = $query->where('parent_id', $item->parent_id);
        }

        $matchingIds = $query->pluck('id')->toArray();

        // Find the position (1-based index) of the item
        $position = array_search($item->id, $matchingIds);

        return ceil(($position + 1) / $perPage); // +1 because array_search is 0-based
    }

    protected function getSearchQuery(string $query): Collection
    {
        $results = collect();

        $results['series'] = Series::query()
            ->where('name', 'LIKE', "%{$query}%")
            ->with(['category', 'author'])
            ->get();
        $results['books'] = Content::query()
            ->where('title', 'LIKE', "%{$query}%")
            ->modeAwareContent(ContentType::BOOK)
            ->with(['parent', 'author'])
            ->get();
        $results['videos'] = Content::query()
            ->where('title', 'LIKE', "%{$query}%")
            ->modeAwareContent(ContentType::VIDEO)
            ->with(['parent', 'author'])
            ->get();

        return $results;
    }

    protected function getSearchableContent(): array
    {
        return [
            'series' => Series::query()->with(['category', 'author'])->get(),
            'books' => Content::query()->modeAwareContent(ContentType::BOOK)->get(),
            'videos' => Content::query()->modeAwareContent(ContentType::VIDEO)->get(),
        ];
    }
}
