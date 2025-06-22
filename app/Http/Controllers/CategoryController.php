<?php

namespace App\Http\Controllers;

use App\Http\Resources\Category\CategoryCollection;
use App\Http\Resources\Category\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\QueryBuilder\QueryBuilder;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        /**
         * Using Spatie QueryBuilder to handle filtering, sorting, and pagination.
         * Allows flexible querying of categories with filtering by name/description.
         * The page size can be customized via the "per_page" query parameter.
         */
        $perPage = request()->get('per_page', 2); // Default is 2 if not provided

        $categories = QueryBuilder::for(Category::class)
            ->allowedFilters(['name', 'description'])
            ->allowedSorts(['name', 'sort_order'])
            ->allowedIncludes(['instruments'])
            ->paginate($perPage)
            ->appends(request()->query()); // Ensures pagination links retain query params

        return new CategoryCollection($categories);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        $category = Category::create($request->only(['name', 'description']));

        return new CategoryResource($category);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return new CategoryResource($category);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        $category->update($request->only(['name', 'description']));

        return new CategoryResource($category);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category): Response
    {
        $category->delete();

        return response('', ResponseAlias::HTTP_NO_CONTENT);
    }
}
