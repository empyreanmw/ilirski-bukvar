<?php

namespace App\Traits;

use App\Models\Content;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Pagination\LengthAwarePaginator;

trait ContentOnDemand
{
    public function getContentByType(array $contentType): HasMany
    {
        return $this->hasMany(Content::class, 'parent_id')
            ->whereIn('type', $contentType)
            ->where('parent_type', self::class)
            ->with(['author', 'parent']);
    }

    public function offlineContent(array $contentType): LengthAwarePaginator
    {
        return $this->getContentByType($contentType)
            ->where('downloaded', true)
            ->smartPaginate($contentType[0]);
    }

    public function onlineContent(array $contentType): LengthAwarePaginator
    {
        return $this->getContentByType($contentType)
            ->smartPaginate($contentType[0]);
    }
}
