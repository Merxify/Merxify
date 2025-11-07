<?php

namespace App\Http\Resources\V1;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Order */
class OrderResource extends JsonResource
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
            'user_id' => $this->user_id,
            'order_number' => $this->order_number,
            'status' => $this->status,
            'currency' => $this->currency,
            'total_amount' => $this->total_amount,
            'billing_address' => $this->billing_address,
            'shipping_address' => $this->shipping_address,
            'email' => $this->email,
            'phone' => $this->phone,
            'notes' => $this->notes,

            // Status timestamps
            'shipped_at' => $this->shipped_at,
            'delivered_at' => $this->delivered_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            // Computed values
            'can_cancel' => in_array($this->status, ['pending', 'processing']),

            // Relationships
            'items' => OrderItemResource::collection($this->whenLoaded('items')),
        ];
    }
}
