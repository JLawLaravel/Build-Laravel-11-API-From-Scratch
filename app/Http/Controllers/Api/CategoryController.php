<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryIndexResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\CategoryShowResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // return Category::all();
        $category = Category::all();
        return CategoryIndexResource::collection($category);
    }

    public function list() {
        return CategoryIndexResource::collection(Category::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        $category = Category::create($request->all());

        if ($request->hasFile('photo')) { 
            $file = $request->file('photo');
            $name = 'categories/' . Str::uuid() . '.' . $file->extension();
            $file->storePubliclyAs('public', $name);
            $data['photo'] = $name;
        } 

        return new CategoryIndexResource($category);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        Category::find($category->id);

        // return $category;
        // return new CategoryShowResource($category);
        return CategoryShowResource::make($category);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $category->update($request->all());

        return CategoryIndexResource::make($category);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();

        // return response(null, Response::HTTP_NO_CONTENT);
        return response()->noContent();
    }
}
