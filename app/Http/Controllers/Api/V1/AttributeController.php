<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\StoreAttributeRequest;
use App\Http\Requests\Api\V1\UpdateAttributeRequest;
use App\Http\Resources\V1\AttributeResource;
use App\Models\Attribute;
use App\Policies\AttributePolicy;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AttributeController extends ApiController
{
    protected string $attribute = AttributePolicy::class;

    /**
     * Display a listing of the resource.
     */
    public function index(): AnonymousResourceCollection
    {
        return AttributeResource::collection(Attribute::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAttributeRequest $request): AttributeResource|JsonResponse
    {
        if ($this->isAble('create', Attribute::class)) {
            return new AttributeResource(Attribute::create($request->validated()));
        }

        return $this->notAuthorized('You are not authorized to create this resource.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Attribute $attribute): AttributeResource
    {
        return new AttributeResource($attribute);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAttributeRequest $request, Attribute $attribute): AttributeResource|JsonResponse
    {
        if ($this->isAble('update', Attribute::class)) {
            $attribute->update($request->validated());

            return new AttributeResource($attribute);
        }

        return $this->notAuthorized('You are not authorized to update this resource.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attribute $attribute): JsonResponse
    {
        if ($this->isAble('delete', Attribute::class)) {
            $attribute->delete();

            return $this->ok('Attribute deleted successfully.');
        }

        return $this->notAuthorized('You are not authorized to delete this resource.');
    }
}
