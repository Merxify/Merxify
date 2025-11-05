<?php

namespace App\Http\Resources\V1;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Product */
class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'product',
            'id' => $this->id,
            'attributes' => [
                'name' => $this->name,
                'slug' => $this->slug,
                'description' => $this->description,
                'short_description' => $this->short_description,
                'meta_title' => $this->meta_title,
                'meta_description' => $this->meta_description,
                'meta_keywords' => $this->meta_keywords,
                'is_active' => $this->is_active,
                'sku' => $this->sku,
                'price' => $this->price,
                'weight' => $this->weight,
                'quantity' => $this->quantity,
                'dimensions' => $this->dimensions,
                'category' => $this->category->name,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
            ],
            'relationships' => [
                'category' => [
                    'data' => [
                        'type' => 'category',
                        'id' => $this->category->id,
                        'attributes' => [
                            'name' => $this->category->name,
                        ],
                    ],
                    'links' => [
                        'self' => route('categories.show', $this->category->name),
                    ],
                ],
            ],
            'links' => [
                'self' => route('categories.show', ['category' => $this->id]),
            ],
        ];
    }
}
