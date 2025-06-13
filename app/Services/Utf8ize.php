<?php
namespace App\Services;

use App\Enums\AppMode as AppModeEnum;
use App\Models\AppSettings;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;

class Utf8ize
{
    public static function run($mixed)
    {
        if ($mixed instanceof Model) {
            foreach ($mixed->getAttributes() as $key => $value) {
                if (is_string($value)) {
                    $mixed->$key = mb_convert_encoding($value, 'UTF-8', 'UTF-8');
                }
            }
            return $mixed;
        }

        if (is_array($mixed)) {
            foreach ($mixed as $key => $value) {
                $mixed[$key] = self::run($value);
            }
        } else if (is_string($mixed)) {
            return mb_convert_encoding($mixed, 'UTF-8', 'UTF-8');
        }
        return $mixed;
    }
}
