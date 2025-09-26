<?php

namespace App\Http\Requests\Settings;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDownloadSettingsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'video_quality' => ['integer', 'nullable'],
         //   'download_path' => ['string', 'required']
        ];
    }

    public function messages(): array
    {
        return [
            'download_path.required' => __('settings.downloads.validation.download_path'),
            'download_path.string' => __('settings.downloads.validation.download_path')
        ];
    }
}
