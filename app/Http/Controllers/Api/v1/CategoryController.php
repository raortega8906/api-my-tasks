<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreCategoryRequest;
use App\Http\Requests\Api\V1\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Tag(
 *     name="Categories",
 *     description="Gestión de categorías de tareas"
 * )
 */
class CategoryController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/v1/categories",
     *     tags={"Categories"},
     *     summary="Obtener categorías del usuario",
     *     description="Retorna todas las categorías que pertenecen al usuario autenticado",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Categorías obtenidas exitosamente",
     *         @OA\JsonContent(
     *             oneOf={
     *                 @OA\Schema(
     *                     @OA\Property(property="message", type="string", example="Categories retrieved successfully"),
     *                     @OA\Property(property="status", type="integer", example=200),
     *                     @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Category"))
     *                 ),
     *                 @OA\Schema(
     *                     @OA\Property(property="message", type="string", example="No categories found"),
     *                     @OA\Property(property="status", type="integer", example=200)
     *                 )
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthorized"),
     *             @OA\Property(property="status", type="integer", example=401)
     *         )
     *     )
     * )
     */
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

    /**
     * @OA\Post(
     *     path="/api/v1/categories",
     *     tags={"Categories"},
     *     summary="Crear nueva categoría",
     *     description="Crea una nueva categoría para el usuario autenticado",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="Trabajo"),
     *             @OA\Property(property="description", type="string", example="Tareas relacionadas con el trabajo")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Categoría creada exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Category created successfully"),
     *             @OA\Property(property="status", type="integer", example=201),
     *             @OA\Property(property="data", ref="#/components/schemas/Category")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthorized"),
     *             @OA\Property(property="status", type="integer", example=401)
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Errores de validación",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Failed to create category"),
     *             @OA\Property(property="status", type="integer", example=500),
     *             @OA\Property(property="error", type="string")
     *         )
     *     )
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/api/v1/categories/{category}",
     *     tags={"Categories"},
     *     summary="Obtener una categoría específica",
     *     description="Retorna los detalles de una categoría específica del usuario autenticado",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="category",
     *         in="path",
     *         required=true,
     *         description="ID de la categoría",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Categoría obtenida exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Category retrieved successfully"),
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="data", ref="#/components/schemas/Category")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthorized"),
     *             @OA\Property(property="status", type="integer", example=401)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Categoría no encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Category not found"),
     *             @OA\Property(property="status", type="integer", example=404)
     *         )
     *     )
     * )
     */
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

    /**
     * @OA\Put(
     *     path="/api/v1/categories/{category}",
     *     tags={"Categories"},
     *     summary="Actualizar una categoría",
     *     description="Actualiza los datos de una categoría específica del usuario autenticado",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="category",
     *         in="path",
     *         required=true,
     *         description="ID de la categoría",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Trabajo Actualizado"),
     *             @OA\Property(property="description", type="string", example="Descripción actualizada")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Categoría actualizada exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Category updated successfully"),
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="data", ref="#/components/schemas/Category")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthorized"),
     *             @OA\Property(property="status", type="integer", example=401)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Categoría no encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Category not found"),
     *             @OA\Property(property="status", type="integer", example=404)
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Errores de validación",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Failed to update category"),
     *             @OA\Property(property="status", type="integer", example=500),
     *             @OA\Property(property="error", type="string")
     *         )
     *     )
     * )
     */
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

    /**
     * @OA\Delete(
     *     path="/api/v1/categories/{category}",
     *     tags={"Categories"},
     *     summary="Eliminar una categoría",
     *     description="Elimina una categoría específica del usuario autenticado",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="category",
     *         in="path",
     *         required=true,
     *         description="ID de la categoría",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Categoría eliminada exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Category destroy successfully"),
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="data", ref="#/components/schemas/Category")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthorized"),
     *             @OA\Property(property="status", type="integer", example=401)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Categoría no encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Category not found"),
     *             @OA\Property(property="status", type="integer", example=404)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Failed to update category"),
     *             @OA\Property(property="status", type="integer", example=500),
     *             @OA\Property(property="error", type="string")
     *         )
     *     )
     * )
     */
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