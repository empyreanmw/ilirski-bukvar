<?php

namespace App\Models;

use App\Enums\AppMode;
use Illuminate\Database\Eloquent\Model;

class AppSettings extends Model
{
    public $fillable = ['mode'];
    public $casts = [
        'mode' => AppMode::class
    ];
}
