<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a paginated listing of the tasks.
     */
    public function index(Request $request): JsonResponse
    {
        $tasks = $request->user()->tasks()->latest()->paginate(10);

        return response()->json([
            'success' => true,
            'tasks'   => $tasks,
        ]);
    }

    /**
     * Store a newly created task.
     */
    public function store(StoreTaskRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id;

        $task = Task::create($data);

        return response()->json([
            'success' => $task ? true : false,
            'message' => $task ? 'Task created successfully.' : 'Task creation faild.',
            'task_id'    => $task?->id,
        ], $task ? 201 : 500);
    }

    /**
     * Display the specified task.
     */
    public function show(Task $task): JsonResponse
    {
        $this->authorizeTask($task);

        return response()->json([
            'success' => true,
            'task'    => $task,
        ]);
    }

    /**
     * Update the specified task.
     */
    public function update(UpdateTaskRequest $request, Task $task): JsonResponse
    {
        $this->authorizeTask($task);

        $task->update($request->validated());

        return response()->json([
            'success' => true,
            'task'    => $task,
        ]);
    }

    /**
     * Remove the specified task.
     */
    public function destroy(Request $request, Task $task): JsonResponse
    {
        $this->authorizeTask($task);

        $task->delete();

        return response()->json([
            'success' => true,
            'message' => 'Task deleted successfully.',
        ]);
    }

    /**
     * Ensure the task belongs to the authenticated user.
     */
    protected function authorizeTask(Task $task): void
    {
        if ($task->user_id !== auth()->id) {
            abort(403, 'Unauthorized action.');
        }
    }
}
