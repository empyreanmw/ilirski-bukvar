<?php

namespace App\Http\Requests\Settings;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateGeneralSettingsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'content_per_page' => ['integer', 'required', 'min:1', 'max:100'],
            'video_player_path' => ['string', 'nullable'],
            'book_reader_path' => ['string', 'nullable']
        ];
    }
}
