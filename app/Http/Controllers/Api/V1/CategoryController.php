<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\StoreCategoryRequest;
use App\Http\Requests\Api\V1\UpdateCategoryRequest;
use App\Http\Resources\V1\CategoryResource;
use App\Models\Category;
use App\Policies\CategoryPolicy;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CategoryController extends ApiController
{
    protected string $category = CategoryPolicy::class;

    /**
     * Display a listing of the resource.
     */
    public function index(): AnonymousResourceCollection
    {
        return CategoryResource::collection(Category::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request): CategoryResource|JsonResponse
    {
        if ($this->isAble('create', Category::class)) {
            return new CategoryResource(Category::create($request->validated()));
        }

        return $this->notAuthorized('You are not authorized to create this resource.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category): CategoryResource
    {
        return new CategoryResource($category);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category): CategoryResource|JsonResponse
    {
        if ($this->isAble('update', Category::class)) {
            $category->update($request->validated());

            return new CategoryResource($category);
        }

        return $this->notAuthorized('You are not authorized to update this resource.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category): JsonResponse
    {
        if ($this->isAble('delete', Category::class)) {
            $category->delete();

            return $this->ok('Category deleted successfully.');
        }

        return $this->notAuthorized('You are not authorized to delete this resource.');
    }
}
