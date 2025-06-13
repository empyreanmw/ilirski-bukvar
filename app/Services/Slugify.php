<?php
namespace App\Services;

use App\Enums\AppMode as AppModeEnum;
use App\Models\AppSettings;
use Exception;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;

class Slugify
{
    public static function run(string $string): string
    {
        // Transliterate: Replace Latin special letters (Č, Ć, Š, Đ, Ž etc.)
        $transliteration = [
            'č' => 'c', 'ć' => 'c', 'đ' => 'dj', 'š' => 's', 'ž' => 'z',
            'Č' => 'c', 'Ć' => 'c', 'Đ' => 'dj', 'Š' => 's', 'Ž' => 'z'
        ];

        // Replace special characters with ASCII equivalents
        $string = strtr($string, $transliteration);

        // Replace spaces and unwanted characters (add # here) with underscores
        $cleaned = preg_replace('/[\s\'":;!?\-\\\\\/|=+*.,#]+/', '_', strtolower($string));

        // Replace multiple underscores with single underscore
        $cleaned = preg_replace('/_+/', '_', $cleaned);

        // Trim leading/trailing underscores
        return trim($cleaned, '_');
    }
}
