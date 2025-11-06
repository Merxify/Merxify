<?php

namespace App\Http\Resources\V1;

use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin ProductVariant */
class ProductVariantResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'product-variant',
            'id' => $this->id,
            'attributes' => [
                'product_id' => $this->product_id,
                'sku' => $this->sku,
                'price' => $this->price,
                'weight' => $this->weight,
                'is_active' => $this->is_active,
                'attribute_values' => $this->attribute_values,
            ],
            'links' => [
                'self' => route('product-variants.show', ['product_variant' => $this->id]),
            ],
        ];
    }
}
