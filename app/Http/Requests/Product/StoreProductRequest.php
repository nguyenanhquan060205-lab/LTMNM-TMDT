<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $productImage = config('uploads.types.product_image');

        return [
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:200'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'images.*' => [
                'nullable',
                'image',
                'mimetypes:'.implode(',', $productImage['mimes']),
                'extensions:'.implode(',', $productImage['extensions']),
                'max:'.$productImage['max'],
            ],
        ];
    }
}
