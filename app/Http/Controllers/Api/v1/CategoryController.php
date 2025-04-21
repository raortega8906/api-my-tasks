<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreCategoryRequest;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{

    public function index(): JsonResponse
    {
        $category = Category::all();

        if($category->isEmpty()){
            return response()->json([
                'message' => 'No categories found',
                'status' => 200
            ], 200);
        }

        $data = [
            'message' => 'Categories retrieved successfully',
            'status' => 200,
            'data' => $category
        ];

        return response()->json($data, 200);
    }

    public function store(StoreCategoryRequest $request)
    {
        try {

            $validated = $request->validated();

            $category = Category::create($validated);

            $data = [
                'message' => 'Category created successfully',
                'status' => 201,
                'data' => $category
            ];

            return response()->json($category, 201);

        }
        catch (\Exception $e) {

            return response()->json([
                'message' => 'Failed to create category',
                'status' => 500,
                'error' => $e->getMessage()
            ], 500);

        }
    }

}
