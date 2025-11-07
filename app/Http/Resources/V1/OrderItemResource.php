<?php

namespace App\Http\Resources\V1;

use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin OrderItem */
class OrderItemResource extends JsonResource
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
            'order_id' => $this->order_id,
            'product_id' => $this->product_id,
            'variant_id' => $this->variant_id,
            'product_name' => $this->product_name,
            'product_sku' => $this->product_sku,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'total' => $this->total,
            'product_options' => $this->product_options,
            'created_at' => $this->created_at,

            // Relationships (optional - may not exist if product deleted)
            'product' => new ProductListResource($this->whenLoaded('product')),
        ];
    }
}
