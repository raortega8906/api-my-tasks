<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::all();

        if ($tasks->isEmpty()) {
            return response()->json([
                'message' => 'No tasks found',
                'status' => 200
            ]);
        }

        return response()->json([
            'message' => 'Tasks retrieved successfully',
            'status' => 200,
            'data' => $tasks
        ]);
    }

    
}
