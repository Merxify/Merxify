<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Policies\ProductPolicy;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CartController extends ApiController
{
    protected string $product = ProductPolicy::class;

    /**
     * Get user's cart.
     */
    public function index(Request $request): JsonResponse
    {
        $cart = $this->getOrCreateCart($request);

        $cart->load('items.variant');

        return $this->success([
            'cart' => $cart,
            'total' => $cart->total,
            'item_count' => $cart->item_count,
        ], 'Cart retrieved successfully');
    }

    /**
     * Add item to cart
     */
    public function addItem(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'variant_id' => 'nullable|exists:product_variants,id',
            'quantity' => 'required|integer|min:1',
            'options' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return $this->error('Validation failed', $validator->errors());
        }

        /** @var Product $product */
        $product = Product::find($request->product_id);
        /** @var ?ProductVariant $variant */
        $variant = $request->variant_id ? ProductVariant::find($request->variant_id) : null;

        $cart = $this->getOrCreateCart($request);

        $cartItem = $cart->addItem(
            $product,
            $request->quantity,
            $variant,
            $request->options ?? [],
        );

        $cart->load('items.variant');

        return $this->success([
            'cart' => $cart,
            'item' => $cartItem,
        ], 'Item added to cart successfully');
    }

    /**
     * Update cart item quantity
     */
    public function updateItem(Request $request, int $itemId): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return $this->error('Validation failed', $validator->errors(), 422);
        }

        $cart = $this->getOrCreateCart($request);
        /** @var ?CartItem $item */
        $item = $cart->items()->find($itemId);
        if (! $item) {
            return $this->error('Cart item not found', null, 404);
        }
        $item->update(['quantity' => $request->quantity]);

        $cart->load(['items.variant']);

        return $this->success($cart, 'Cart updated successfully');
    }

    /**
     * Remove item from cart
     */
    public function removeItem(Request $request, int $itemId): JsonResponse
    {
        $cart = $this->getOrCreateCart($request);
        /** @var ?CartItem $item */
        $item = $cart->items()->find($itemId);

        if (! $item) {
            return $this->error('Cart item not found', null, 404);
        }

        $item->delete();

        $cart->load(['items.product.images', 'items.variant']);

        return $this->success($cart, 'Item removed from cart successfully');
    }

    /**
     * Clear cart
     */
    public function clear(Request $request): JsonResponse
    {
        $cart = $this->getOrCreateCart($request);
        $cart->clear();

        return $this->success(null, 'Cart cleared successfully');
    }

    /**
     * Get or create cart for user/session
     */
    private function getOrCreateCart(Request $request): Cart
    {
        if ($request->user()) {
            $cart = Cart::where('user_id', $request->user()->id)->first();
        } else {
            $sessionId = $request->session()->getId();
            $cart = Cart::where('session_id', $sessionId)->first();
        }

        if (! $cart) {
            $cart = Cart::create([
                'user_id' => $request->user()?->id,
                'session_id' => $request->user() ? null : $request->session()->getId(),
                'currency' => 'USD',
                'expires_at' => now()->addDays(30),
            ]);
        }

        return $cart;
    }
}
