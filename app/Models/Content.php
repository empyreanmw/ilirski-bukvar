<?php

namespace App\Models;

use App\Enums\AppMode;
use App\Enums\ContentType;
use App\Enums\PlayerType;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Content extends Model
{
    public $casts = [
        'type' => ContentType::class,
        'player_type' => PlayerType::class,
    ];
    public $guarded = [];

    public function series(): BelongsTo
    {
        return $this->belongsTo(Series::class, 'parent_id')
            ->where('parent_type', Series::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class, 'author_id');
    }

    public function parent(): MorphTo
    {
        return $this->morphTo();
    }

    public function hasSeries(): bool
    {
        return $this->parent instanceof Series;
    }

    #[Scope]
    public function offlineContent(Builder $builder, ContentType $contentType): void
    {
        $builder->where('type', $contentType)
            ->where('downloaded', true)
            ->with(['author', 'parent']);
    }

    #[Scope]
    public function onlineContent(Builder $builder, ContentType $contentType): void
    {
        $builder->where('type', $contentType)
            ->with(['author', 'parent']);
    }

    #[Scope]
    public function modeAwareContent(Builder $builder, ContentType $contentType): void
    {
        $appMode = AppSettings::first()->mode;

        match ($appMode) {
            AppMode::OFFLINE => $builder->offlineContent($contentType),
            AppMode::ONLINE => $builder->onlineContent($contentType),
        };
    }

    public function serializeData(): array
    {
        return $this->only([
            'title',
            'id',
            'thumbnail_url',
            'url',
            'type',
            'download_url',
            'downloaded'
        ]);
    }
}
