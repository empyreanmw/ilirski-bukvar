<?php

namespace App\Http\Requests\Actions;

use App\Enums\ContentEntity;
use App\Rules\ExistsInSeriesOrContents;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class FavoriteRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id' => ['required', 'int', new ExistsInSeriesOrContents()],
            'contentEntity' => ['required', new Enum(ContentEntity::class)],
        ];
    }
}
