<?php
namespace App\Services\ContentSeeders;

use App\Enums\PlayerType;

trait VariablePlayerType
{
    CONST PLAYER_TYPE_NEEDLES = [
        [
            'needle' => 'youtube',
            'type' => PlayerType::YOUTUBE,
        ],
        [
            'needle' => 'google',
            'type' => PlayerType::GOOGLE_DRIVE,
        ],
        [
            'needle' => 'vimeo',
            'type' => PlayerType::GOOGLE_DRIVE,
        ],

    ];
    public function determinePlayerType(string $url): PlayerType
    {
        foreach (self::PLAYER_TYPE_NEEDLES as $player) {
            if (str_contains($url, $player['needle'])) {
                return $player['type'];
            }
        }

        return PlayerType::REGULAR_VIDEO;
    }
}
