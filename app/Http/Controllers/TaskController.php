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
     * 
     * @param Request $request
     * @return JsonResponse
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
     * 
     * @param StoreTaskRequest $request
     * @return JsonResponse
     */
    public function store(StoreTaskRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;

        $task = Task::create($data);

        return response()->json([
            'success' => $task ? true : false,
            'message' => $task ? 'Task created successfully.' : 'Task creation faild.',
            'task_id'    => $task?->id,
        ], $task ? 201 : 500);
    }

    /**
     * Display the specified task.
     * 
     * @param Request $request
     * @param Task $task
     * @return JsonResponse
     */
    public function show(Request $request, Task $task): JsonResponse
    {
        $this->authorizeTask($request, $task);

        return response()->json([
            'success' => true,
            'task'    => $task,
        ]);
    }

    /**
     * Update the specified task.
     * 
     * @param UpdateTaskRequest $request
     * @param Task $task
     * @return JsonResponse
     */
    public function update(UpdateTaskRequest $request, Task $task): JsonResponse
    {
        $this->authorizeTask($request, $task);

        $task->update($request->validated());

        return response()->json([
            'success' => true,
            'task'    => $task,
        ]);
    }

    /**
     * Remove the specified task.
     * 
     * @param Request $request
     * @param Task $task
     * @return JsonResponse
     */
    public function destroy(Request $request, Task $task): JsonResponse
    {
        $this->authorizeTask($request, $task);

        $task->delete();

        return response()->json([
            'success' => true,
            'message' => 'Task deleted successfully.',
        ]);
    }

    /**
     * Ensure the task belongs to the authenticated user.
     * 
     * @param Request $request
     * @param Task $task
     * @return JsonResponse
     */
    protected function authorizeTask(Request $request, Task $task): void
    {
        if ($task->user_id !== $request->user()->id) {
            abort(403, 'Unauthorized action.');
        }
    }
}
