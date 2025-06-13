<?php
namespace App\Factories;

use App\Enums\ContentEntity;
use App\Models\Content;
use App\Models\Series;

class ContentEntityFactory
{
    public function create(string $entity, int $id): Content|Series
    {
       return match ($entity) {
           ContentEntity::CONTENT->value => Content::find($id),
           ContentEntity::SERIES->value => Series::find($id),
       };
    }
}
