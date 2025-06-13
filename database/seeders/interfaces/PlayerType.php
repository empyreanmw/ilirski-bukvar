<?php

namespace Database\Seeders\interfaces;
use App\Enums\PlayerType as PlayerTypeEnum;

interface PlayerType
{
    public function determinePlayerType(string $url): PlayerTypeEnum;
}
