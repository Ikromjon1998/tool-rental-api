<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\StoreRequest;
use App\Http\Requests\Category\UpdateRequest;
use App\Http\Resources\Category\CategoryCollection;
use App\Http\Resources\Category\CategoryResource;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\JsonResponse;
use Spatie\QueryBuilder\QueryBuilder;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class CategoryController extends Controller
{
    /**
     * Default number of items per page for pagination.
     * This can be overridden by the "per_page" query parameter in the request.
     */
    const DEFAULT_PER_PAGE = 2; // Default number of items per page for pagination

    /**
     * CategoryController constructor.
     */
    public function __construct(private readonly CategoryService $categoryService) {}

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
        $perPage = request()->get('per_page', self::DEFAULT_PER_PAGE);

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
    public function store(StoreRequest $request): CategoryResource
    {
        $category = $this->categoryService->create($request->validated());

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
    public function update(UpdateRequest $request, Category $category): CategoryResource
    {
        $category = $this->categoryService->update($category, $request->validated());

        return new CategoryResource($category);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category): JsonResponse
    {
        $this->categoryService->delete($category);

        return response()->json([
            'message' => 'Category deleted successfully.',
        ], ResponseAlias::HTTP_NO_CONTENT);
    }
}
