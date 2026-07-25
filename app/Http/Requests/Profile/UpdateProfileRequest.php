<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $avatar = config('uploads.types.avatar');

        return [
            'full_name' => ['required', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
            'avatar' => [
                'nullable',
                'image',
                'mimetypes:'.implode(',', $avatar['mimes']),
                'extensions:'.implode(',', $avatar['extensions']),
                'max:'.$avatar['max'],
            ],
        ];
    }
}
