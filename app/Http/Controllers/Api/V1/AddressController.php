<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\StoreAddressRequest;
use App\Http\Requests\Api\V1\UpdateAddressRequest;
use App\Http\Resources\V1\AddressResource;
use App\Models\Address;
use App\Policies\AddressPolicy;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AddressController extends ApiController
{
    protected string $address = AddressPolicy::class;

    /**
     * Display a listing of the resource.
     */
    public function index(): AnonymousResourceCollection
    {
        return AddressResource::collection(Address::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAddressRequest $request): AddressResource|JsonResponse
    {
        if ($this->isAble('create', Address::class)) {
            return new AddressResource(Address::create($request->validated()));
        }

        return $this->notAuthorized('You are not authorized to create this resource.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Address $address): AddressResource
    {
        return new AddressResource($address);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAddressRequest $request, Address $address): AddressResource|JsonResponse
    {
        if ($this->isAble('update', Address::class)) {
            $address->update($request->validated());

            return new AddressResource($address);
        }

        return $this->notAuthorized('You are not authorized to update this resource.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Address $address): JsonResponse
    {
        if ($this->isAble('delete', Address::class)) {
            $address->delete();

            return $this->ok('Address deleted successfully.');
        }

        return $this->notAuthorized('You are not authorized to delete this resource.');
    }
}
