<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\StoreProductVariantRequest;
use App\Http\Requests\Api\V1\UpdateProductVariantRequest;
use App\Http\Resources\V1\ProductVariantResource;
use App\Models\ProductVariant;
use App\Policies\ProductVariantPolicy;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductVariantController extends ApiController
{
    protected string $product_variant = ProductVariantPolicy::class;

    /**
     * Display a listing of the resource.
     */
    public function index(): AnonymousResourceCollection
    {
        return ProductVariantResource::collection(ProductVariant::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductVariantRequest $request): ProductVariantResource|JsonResponse
    {
        if ($this->isAble('create', ProductVariant::class)) {
            return new ProductVariantResource(ProductVariant::create($request->validated()));
        }

        return $this->notAuthorized('You are not authorized to create this resource.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductVariant $product_variant): ProductVariantResource
    {
        return new ProductVariantResource($product_variant);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductVariantRequest $request, ProductVariant $product_variant): ProductVariantResource|JsonResponse
    {
        if ($this->isAble('update', ProductVariant::class)) {
            $product_variant->update($request->validated());

            return new ProductVariantResource($product_variant);
        }

        return $this->notAuthorized('You are not authorized to update this resource.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductVariant $product_variant): JsonResponse
    {
        if ($this->isAble('delete', ProductVariant::class)) {
            $product_variant->delete();

            return $this->ok('Product Variant deleted successfully.');
        }

        return $this->notAuthorized('You are not authorized to delete this resource.');
    }
}
