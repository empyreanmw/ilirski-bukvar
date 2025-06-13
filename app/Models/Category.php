<?php

namespace App\Models;

use App\Traits\ContentOnDemand;
use Illuminate\Database\Eloquent\Model;
use App\Enums\Category as CategoryEnum;

class Category extends Model
{
    use ContentOnDemand;

    public function series()
    {
        return $this->hasMany(Series::class)
            ->with(
                [
                    'author',
                    'content'
                ]
            );
    }
}
