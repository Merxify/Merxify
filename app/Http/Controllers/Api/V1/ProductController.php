<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\StoreProductRequest;
use App\Http\Requests\Api\V1\UpdateProductRequest;
use App\Http\Resources\V1\ProductResource;
use App\Models\Product;
use App\Policies\ProductPolicy;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductController extends ApiController
{
    protected string $product = ProductPolicy::class;

    /**
     * Display a listing of the resource.
     */
    public function index(): AnonymousResourceCollection
    {
        return ProductResource::collection(Product::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request): ProductResource|JsonResponse
    {
        if ($this->isAble('create', Product::class)) {
            return new ProductResource(Product::create($request->validated()));
        }

        return $this->notAuthorized('You are not authorized to create this resource.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product): ProductResource
    {
        return new ProductResource($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product): ProductResource|JsonResponse
    {
        if ($this->isAble('update', Product::class)) {
            $product->update($request->validated());

            return new ProductResource($product);
        }

        return $this->notAuthorized('You are not authorized to update this resource.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product): JsonResponse
    {
        if ($this->isAble('delete', Product::class)) {
            $product->delete();

            return $this->ok('Product deleted successfully.');
        }

        return $this->notAuthorized('You are not authorized to delete this resource.');
    }
}
