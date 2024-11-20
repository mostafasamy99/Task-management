<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;
use App\Http\Resources\TaskResource;
use App\Http\Requests\TaskRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $tasks = Task::query();

        if ($request->has('status')) {
            $tasks->where('status', $request->status);
        }

        if ($request->has('due_date_from') && $request->has('due_date_to')) {
            $tasks->whereBetween('due_date', [$request->due_date_from, $request->due_date_to]);
        }

        $perPage = $request->query('per_page', 15);
        $page = $request->query('page', 1);

        $paginatedTasks = $tasks->paginate($perPage, ['*'], 'page', $page);

        return TaskResource::collection($paginatedTasks);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskRequest $request)
    {
        $user = User::where('email', $request->user_email)->firstOrFail();
        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'status' => $request->status ?? 'pending',
            'user_id' => $user->id,
        ]);

        return response()->json([
            'message' => 'Task created successfully',
            'data' => new TaskResource($task),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $task = Task::findOrFail($id);

        return new TaskResource($task);
    }

    /**
    * Retrieve tasks assigned to a specific user.
    */
    public function userTasks(Request $request, $userId)
    {
        $tasks = Task::where('user_id', $userId);

        if ($request->has('status')) {
            $tasks->where('status', $request->status);
        }

        if ($request->has('due_date_from') && $request->has('due_date_to')) {
            $tasks->whereBetween('due_date', [$request->due_date_from, $request->due_date_to]);
        }

        $perPage = $request->query('per_page', 5);
        $paginatedTasks = $tasks->paginate($perPage);

        $tasksWithTaskId = $paginatedTasks->through(function ($task) {
            return [
                'task_id' => $task->id,
                'user_email' => $task->user->email,
                'title' => $task->title,
                'description' => $task->description,
                'status' => $task->status,
                'due_date' => $task->due_date,
                'created_at' => $task->created_at->format('Y-m-d'),
                'updated_at' => $task->updated_at->format('Y-m-d'),
            ];
        });

        return response()->json($tasksWithTaskId);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TaskRequest $request, string $id)
    {
        $task = Task::findOrFail($id);

        $task->update($request->validated());

        return new TaskResource($task);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $task = Task::findOrFail($id);
            $task->delete();

            return response()->json(['message' => 'Task deleted successfully'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Task not found'], 404);
        }
    }
}
