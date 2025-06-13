<?php

namespace App\Http\Requests\Actions;

use App\Enums\ContentEntity;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class ShowInFolderRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id' => ['int', 'required'],
            'contentEntity' => ['required', new Enum(ContentEntity::class)],
        ];
    }
}
