<?php

namespace App\Dtos;

class DownloadOutput
{
    public function __construct(
        public float $downloaded,
        public int $percent,
        public float $total,
        public array $content,
    ) {}

    public function getDownloaded(): float
    {
        return $this->downloaded;
    }

    public function getPercent(): int
    {
        return $this->percent;
    }

    public function getTotal(): float
    {
        return $this->total;
    }

    public function getTitle(): string
    {
        return $this->content->title;
    }

    public function getId(): int
    {
        return $this->content->id;
    }
}
