<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\StoreOrderRequest;
use App\Http\Resources\V1\OrderListResource;
use App\Http\Resources\V1\OrderResource;
use App\Models\Cart;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class OrderController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(): AnonymousResourceCollection
    {
        return OrderListResource::collection(Order::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request): JsonResponse
    {
        /** @var Cart $cart */
        $cart = Cart::where('user_id', $request->user()->id)->first();

        $order = Order::create([
            'user_id' => $request->user()->id,
            'status' => 'pending',
            'currency' => $cart->currency,
            'total_amount' => $cart->total,
            'billing_address' => $request->billing_address,
            'shipping_address' => $request->shipping_address,
            'email' => $request->user()->email,
            'phone' => $request->user()->phone,
            'notes' => $request->notes,
        ]);

        foreach ($cart->items as $item) {
            $order->items()->create([
                'product_id' => $item->product_id,
                'variant_id' => $item->variant_id,
                'product_name' => $item->product->name,
                'product_sku' => $item->variant->sku ?? $item->product->sku,
                'quantity' => $item->quantity,
                'price' => $item->price,
                'total' => $item->total,
                'product_options' => $item->product_options,
            ]);
        }

        $cart->clear();

        return $this->success(
            new OrderResource($order->load(['items'])),
            'Order created successfully',
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order): OrderResource
    {
        return new OrderResource($order);
    }

    public function updateStatus(Order $order, Request $request): JsonResponse
    {
        $order->update(['status' => $request->status]);

        return $this->success(
            new OrderResource($order),
            'Order cancelled successfully'
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function cancel(Order $order): JsonResponse
    {
        if (! in_array($order->status, ['pending', 'processing'])) {
            return $this->error('Order cannot be cancelled', null, 400);
        }

        $order->update(['status' => 'cancelled']);

        return $this->success(
            new OrderResource($order),
            'Order cancelled successfully'
        );
    }
}
