<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreTaskRequest;
use App\Http\Requests\Api\V1\UpdateTaskRequest;
use App\Models\Category;
use App\Models\Task;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Tag(
 *     name="Tasks",
 *     description="Gestión de tareas dentro de categorías"
 * )
 */
class TaskController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/categories/{category}/tasks",
     *     tags={"Tasks"},
     *     summary="Obtener tareas de una categoría",
     *     description="Retorna todas las tareas que pertenecen a una categoría específica",
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
     *         description="Tareas obtenidas exitosamente",
     *         @OA\JsonContent(
     *             oneOf={
     *                 @OA\Schema(
     *                     @OA\Property(property="message", type="string", example="Tasks retrieved successfully"),
     *                     @OA\Property(property="status", type="integer", example=200),
     *                     @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Task"))
     *                 ),
     *                 @OA\Schema(
     *                     @OA\Property(property="message", type="string", example="No tasks found"),
     *                     @OA\Property(property="status", type="integer", example=200)
     *                 )
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Categoría no encontrada"
     *     )
     * )
     */
    public function index(Category $category): JsonResponse
    {
        $tasks = $category->tasks;

        if ($tasks->isEmpty()) {
            return response()->json([
                'message' => 'No tasks found',
                'status' => 200
            ], 200);
        }

        $data = [
            'message' => 'Tasks retrieved successfully',
            'status' => 200,
            'data' => $tasks
        ];

        return response()->json($data, 200);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/categories/{category}/tasks",
     *     tags={"Tasks"},
     *     summary="Crear nueva tarea",
     *     description="Crea una nueva tarea dentro de una categoría específica",
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
     *             required={"title"},
     *             @OA\Property(property="title", type="string", example="Completar reporte mensual"),
     *             @OA\Property(property="description", type="string", example="Finalizar el reporte de ventas del mes"),
     *             @OA\Property(property="due_date", type="string", format="date", example="2024-12-31"),
     *             @OA\Property(property="priority", type="string", enum={"low", "medium", "high"}, example="high")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Tarea creada exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Task created successfully"),
     *             @OA\Property(property="status", type="integer", example=201),
     *             @OA\Property(property="data", ref="#/components/schemas/Task")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Categoría no encontrada"
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
     *             @OA\Property(property="message", type="string", example="Failed to create task"),
     *             @OA\Property(property="status", type="integer", example=500),
     *             @OA\Property(property="error", type="string")
     *         )
     *     )
     * )
     */
    public function store(StoreTaskRequest $request, Category $category): JsonResponse
    {
        try {

            $validated = $request->validated();
            $validated['status'] = 'pending';
            $validated['category_id'] = $category->id;

            $task = Task::create($validated);

            $data = [
                'message' => 'Task created successfully',
                'status' => 201,
                'data' => $task
            ];

            return response()->json($data, 201);

        } 
        catch (\Exception $e) {

            return response()->json([
                'message' => 'Failed to create task',
                'status' => 500,
                'error' => $e->getMessage()
            ], 500);

        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/categories/{category}/tasks/{task}",
     *     tags={"Tasks"},
     *     summary="Obtener una tarea específica",
     *     description="Retorna los detalles de una tarea específica dentro de una categoría",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="category",
     *         in="path",
     *         required=true,
     *         description="ID de la categoría",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="task",
     *         in="path",
     *         required=true,
     *         description="ID de la tarea",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tarea obtenida exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Task retrieved successfully"),
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="data", ref="#/components/schemas/Task")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tarea o categoría no encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Task in category not found"),
     *             @OA\Property(property="status", type="integer", example=404)
     *         )
     *     )
     * )
     */
    public function show(Task $task, Category $category): JsonResponse
    {
        if (!$task || !$category) {

            return response()->json([
                'message' => 'Task in category not found',
                'status' => 404
            ], 404);

        }
        elseif ($category->id != $task->category_id) {

            return response()->json([
                'message' => 'Task in category not found',
                'status' => 404
            ], 404);

        }
        else {

            $data = [
                'message' => 'Task retrieved successfully',
                'status' => 200,
                'data' => $task
            ];
    
            return response()->json($data, 200);

        }        
    }

    /**
     * @OA\Put(
     *     path="/api/v1/categories/{category}/tasks/{task}",
     *     tags={"Tasks"},
     *     summary="Actualizar una tarea",
     *     description="Actualiza los datos de una tarea específica dentro de una categoría",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="category",
     *         in="path",
     *         required=true,
     *         description="ID de la categoría",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="task",
     *         in="path",
     *         required=true,
     *         description="ID de la tarea",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string", example="Reporte mensual actualizado"),
     *             @OA\Property(property="description", type="string", example="Descripción actualizada"),
     *             @OA\Property(property="status", type="string", enum={"pending", "in_progress", "completed"}, example="in_progress"),
     *             @OA\Property(property="due_date", type="string", format="date", example="2024-12-31"),
     *             @OA\Property(property="priority", type="string", enum={"low", "medium", "high"}, example="medium")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tarea actualizada exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Task updated successfully"),
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="data", ref="#/components/schemas/Task")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tarea o categoría no encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Task in category not found"),
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
     *             @OA\Property(property="message", type="string", example="Failed to update task"),
     *             @OA\Property(property="status", type="integer", example=500),
     *             @OA\Property(property="error", type="string")
     *         )
     *     )
     * )
     */
    public function update(UpdateTaskRequest $request, Task $task, Category $category): JsonResponse
    {
        try {

            if (!$task || !$category) {

                return response()->json([
                    'message' => 'Task in category not found',
                    'status' => 404
                ], 404);
    
            }
            elseif ($category->id != $task->category_id) {
    
                return response()->json([
                    'message' => 'Task in category not found',
                    'status' => 404
                ], 404);
    
            }
            else {
                
                $validated = $request->validated();

                $task->update($validated);
    
                $data = [
                    'message' => 'Task updated successfully',
                    'status' => 200,
                    'data' => $task
                ];
    
                return response()->json($data, 200);

            }

        }
        catch (\Exception $e) {

            return response()->json([
                'message' => 'Failed to update task',
                'status' => 500,
                'error' => $e->getMessage()
            ], 500);

        }
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/categories/{category}/tasks/{task}",
     *     tags={"Tasks"},
     *     summary="Eliminar una tarea",
     *     description="Elimina una tarea específica de una categoría",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="category",
     *         in="path",
     *         required=true,
     *         description="ID de la categoría",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="task",
     *         in="path",
     *         required=true,
     *         description="ID de la tarea",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tarea eliminada exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Task destroy successfully"),
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="data", ref="#/components/schemas/Task")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tarea o categoría no encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Task in category not found"),
     *             @OA\Property(property="status", type="integer", example=404)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Failed to update task"),
     *             @OA\Property(property="status", type="integer", example=500),
     *             @OA\Property(property="error", type="string")
     *         )
     *     )
     * )
     */
    public function destroy(Task $task, Category $category): JsonResponse
    {
        try {

            if (!$task || !$category) {

                return response()->json([
                    'message' => 'Task in category not found',
                    'status' => 404
                ], 404);
    
            }
            elseif ($category->id != $task->category_id) {
    
                return response()->json([
                    'message' => 'Task in category not found',
                    'status' => 404
                ], 404);
    
            }
            else {

                $task->delete();

                $data = [
                    'message' => 'Task destroy successfully',
                    'status' => 200,
                    'data' => $task
                ];

                return response()->json($data, 200);

            }

        }
        catch (\Exception $e) {

            return response()->json([
                'message' => 'Failed to update task',
                'status' => 500,
                'error' => $e->getMessage()
            ], 500);

        }
    }

}
