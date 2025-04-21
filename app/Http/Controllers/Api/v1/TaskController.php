<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreTaskRequest;
use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Http\JsonResponse;

class TaskController extends Controller
{
    public function index(): JsonResponse
    {
        $tasks = Task::all();

        if ($tasks->isEmpty()) {
            return response()->json([
                'message' => 'No tasks found',
                'status' => 200
            ]);
        }


        $data = [
            'message' => 'Tasks retrieved successfully',
            'status' => 200,
            'data' => $tasks
        ];

        return response()->json($data);
    }

    public function store(StoreTaskRequest $request): JsonResponse
    {
        try {

            $validated = $request->validated();
            $validated['status'] = 'pending';

            $task = Task::create($validated);

            $data = [
                'message' => 'Task created successfully',
                'status' => 201,
                'data' => $task
            ];

            return response()->json($data);

        } 
        catch (\Exception $e) {

            return response()->json([
                'message' => 'Failed to create task',
                'status' => 500,
                'error' => $e->getMessage()
            ]);

        }
    }

}
