<?php

namespace App\Models;

use App\Traits\ContentOnDemand;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class Series extends Model
{
    use ContentOnDemand;
    public $guarded = [];

    public function content()
    {
        return $this->hasMany(Content::class, 'parent_id')
            ->with('author');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function downloadableContent(): HasMany
    {
        return $this->hasMany(Content::class, 'parent_id')
            ->where('downloaded', false);
    }
}
