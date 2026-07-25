<?php

namespace App\Http\Requests\Message;

use Illuminate\Foundation\Http\FormRequest;

class StoreMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $messageImage = config('uploads.types.message_image');

        return [
            'receiver_id' => ['required', 'exists:users,id'],
            'product_id' => ['nullable', 'exists:products,id'],
            'content' => ['nullable', 'required_without:image_path', 'string'],
            'image_path' => [
                'nullable',
                'required_without:content',
                'image',
                'mimetypes:'.implode(',', $messageImage['mimes']),
                'extensions:'.implode(',', $messageImage['extensions']),
                'max:'.$messageImage['max'],
            ],
        ];
    }
}
