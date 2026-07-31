<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    /**
     * Display a listing of all categories.
     */
    public function index(): JsonResponse
    {
        $categories = Category::all();

        return response()->json([
            'categories' => $categories,
        ]);
    }

    /**
     * Store a newly created category.
     */
    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $category = Category::create($request->validated());

        return response()->json([
            'message' => 'Category created successfully.',
            'category' => $category,
        ], 201);
    }

    /**
     * Display the specified category.
     */
    public function show(Category $category): JsonResponse
    {
        return response()->json([
            'category' => $category,
        ]);
    }

    /**
     * Update the specified category.
     */
    public function update(UpdateCategoryRequest $request, Category $category): JsonResponse
    {
        $category->update($request->validated());

        return response()->json([
            'message' => 'Category updated successfully.',
            'category' => $category,
        ]);
    }

    /**
     * Remove the specified category.
     */
    public function destroy(Category $category): JsonResponse
    {
        try {
            $category->delete();

            return response()->json([
                'message' => 'Category deleted successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Cannot delete category as it contains associated products.',
            ], 422);
        }
    }

    /**
     * Display products belonging to the specified category.
     */
    public function products(Request $request, Category $category): JsonResponse
    {
        $query = $category->products();

        // If not admin, only show active products
        if (!Auth::user() || !Auth::user()->can('admin')) {
            $query->where('is_active', true);
        }

        $products = $query->get();

        return response()->json([
            'category' => $category,
            'products' => $products,
        ]);
    }
}
