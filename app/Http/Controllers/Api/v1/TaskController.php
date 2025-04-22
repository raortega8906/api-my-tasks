<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreTaskRequest;
use App\Http\Requests\Api\V1\UpdateTaskRequest;
use App\Models\Category;
use App\Models\Task;
use Illuminate\Http\JsonResponse;

class TaskController extends Controller
{
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

    public function destroy(Task $task): JsonResponse
    {
        try {

            if (!$task) {
                return response()->json([
                    'message' => 'Task not found',
                    'status' => 404
                ], 404);
            }

            $task->delete();

            $data = [
                'message' => 'Task destroy successfully',
                'status' => 200,
                'data' => $task
            ];

            return response()->json($data, 200);

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
