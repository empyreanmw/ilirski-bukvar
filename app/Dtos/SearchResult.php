<?php

namespace App\Dtos;

class SearchResult
{
    public function __construct(
        public int $id,
        public string $title,
        public string $category,
        public ?string $author,
        public string $thumbnail,
        public string $type,
        public string $href,
        public ?string $parentType
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'category' => $this->category,
            'author' => $this->author,
            'thumbnail' => $this->thumbnail,
            'href' => $this->href,
            'parentType' => $this->parentType,
        ];
    }
}
