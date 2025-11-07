<?php

namespace App\Http\Resources\V1;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Order */
class OrderListResource extends JsonResource
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
            'order_number' => $this->order_number,
            'status' => $this->status,
            'total_amount' => $this->total_amount,
            'currency' => $this->currency,
            'items_count' => $this->when(
                $this->relationLoaded('items'),
                fn () => $this->items->count(),
            ),
            'created_at' => $this->created_at,
            'shipped_at' => $this->shipped_at,
            'delivered_at' => $this->delivered_at,
        ];
    }
}
