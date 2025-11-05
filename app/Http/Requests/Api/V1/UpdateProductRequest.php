<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, list<string>>
     */
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string'],
            'slug' => ['sometimes', 'string'],
            'description' => ['nullable', 'string'],
            'short_description' => ['nullable', 'string'],
            'meta_title' => ['sometimes', 'string'],
            'meta_description' => ['sometimes', 'string'],
            'meta_keywords' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
            'sku' => ['sometimes', 'string'],
            'price' => ['sometimes', 'numeric'],
            'weight' => ['sometimes', 'numeric'],
            'quantity' => ['sometimes', 'numeric'],
            'dimensions' => ['sometimes', 'array'],
            'category_id' => ['sometimes', 'integer', 'exists:categories,id'],
        ];
    }
}
