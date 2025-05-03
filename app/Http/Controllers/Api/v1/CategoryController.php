<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreCategoryRequest;
use App\Http\Requests\Api\V1\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{

    public function index(): JsonResponse
    {
        $user = auth()->user();

        if(!$user){
            return response()->json([
                'message' => 'Unauthorized',
                'status' => 401
            ], 401);
        }

        else {
            $categories = Category::where('user_id', $user->id)->get();

            if($categories->isEmpty()){
                return response()->json([
                    'message' => 'No categories found',
                    'status' => 200
                ], 200);
            }
    
            $data = [
                'message' => 'Categories retrieved successfully',
                'status' => 200,
                'data' => $categories
            ];
    
            return response()->json($data, 200);
        }
    }

    public function store(StoreCategoryRequest $request): JsonResponse
    {
        try {

            $user = auth()->user();

            if(!$user){
                return response()->json([
                    'message' => 'Unauthorized',
                    'status' => 401
                ], 401);
            }

            else {
                $validated = $request->validated();

                $validated['user_id'] = $user->id;

                $category = Category::create($validated);
    
                $data = [
                    'message' => 'Category created successfully',
                    'status' => 201,
                    'data' => $category
                ];
    
                return response()->json($data, 201);
            }
            
        }
        catch (\Exception $e) {

            return response()->json([
                'message' => 'Failed to create category',
                'status' => 500,
                'error' => $e->getMessage()
            ], 500);

        }
    }

    public function show(Category $category): JsonResponse
    {
        $user = auth()->user();

        if(!$user || $category->user_id !== $user->id){
            return response()->json([
                'message' => 'Unauthorized',
                'status' => 401
            ], 401);
        }

        else {
            if (!$category) {
                return response()->json([
                    'message' => 'Category not found',
                    'status' => 404
                ], 404);
            }
    
            $data = [
                'message' => 'Category retrieved successfully',
                'status' => 200,
                'data' => $category
            ];
    
            return response()->json($data, 200);
        }

    }

    public function update(UpdateCategoryRequest $request, Category $category): JsonResponse
    {
        try {

            $user = auth()->user();

            if(!$user || $category->user_id !== $user->id){
                return response()->json([
                    'message' => 'Unauthorized',
                    'status' => 401
                ], 401);
            }

            else {
                if (!$category) {
                    return response()->json([
                        'message' => 'Category not found',
                        'status' => 404
                    ], 404);
                }
    
                $validated = $request->validated();
    
                $category->update($validated);
    
                $data = [
                    'message' => 'Category updated successfully',
                    'status' => 200,
                    'data' => $category
                ];
    
                return response()->json($data, 200);
            }

        }
        catch (\Exception $e) {

            return response()->json([
                'message' => 'Failed to update category',
                'status' => 500,
                'error' => $e->getMessage()
            ], 500);

        }
    }

    public function destroy(Category $category): JsonResponse
    {
        try {

            $user = auth()->user();

            if(!$user || $category->user_id !== $user->id){
                return response()->json([
                    'message' => 'Unauthorized',
                    'status' => 401
                ], 401);
            }

            else {
                if (!$category) {
                    return response()->json([
                        'message' => 'Category not found',
                        'status' => 404
                    ], 404);
                }
    
                $category->delete();
    
                $data = [
                    'message' => 'Category destroy successfully',
                    'status' => 200,
                    'data' => $category
                ];
    
                return response()->json($data, 200);
            }

        }
        catch (\Exception $e) {

            return response()->json([
                'message' => 'Failed to update category',
                'status' => 500,
                'error' => $e->getMessage()
            ], 500);

        }
    }

}
