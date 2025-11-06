<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:products,slug'],
            'description' => ['nullable', 'string'],
            'short_description' => ['nullable', 'string'],
            'sku' => ['required', 'string', 'unique:products,sku'],
            'type' => ['required', 'in:simple,configurable,bundle,digital'],
            'status' => ['required', 'in:draft,active,inactive,out_of_stock'],
            'price' => ['required', 'numeric', 'min:0'],
            'weight' => ['nullable', 'numeric', 'min:0'],
            'category_ids' => ['nullable', 'array'],
            'category_ids.*' => ['exists:categories,id'],
        ];
    }
}
