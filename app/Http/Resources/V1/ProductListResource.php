<?php

namespace App\Http\Resources\V1;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Product */
class ProductListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'short_description' => $this->short_description,
            'sku' => $this->sku,
            'type' => $this->type,
            'status' => $this->status,
            'price' => $this->price,

            // Category names only
            'categories' => $this->when(
                $this->relationLoaded('categories'),
                fn () => $this->categories->pluck('name')
            ),
        ];
    }
}
