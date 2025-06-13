<?php
namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class ExistsInSeriesOrContents implements Rule
{
    public function passes($attribute, $value): bool
    {
        return DB::table('series')->where('id', $value)->exists()
        || DB::table('contents')->where('id', $value)->exists();
    }

    public function message(): string
    {
        return 'The selected :attribute must exist in series or contents.';
    }
}


