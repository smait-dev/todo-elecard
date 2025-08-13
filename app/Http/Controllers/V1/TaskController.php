<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Requests\Api\V1\Task\IndexTaskRequest;
use App\Requests\Api\V1\Task\StoreTaskRequest;
use App\Requests\Api\V1\Task\UpdateTaskRequest;
use App\Services\Task\TaskServiceInterface;
use Illuminate\Http\JsonResponse;

class TaskController extends Controller
{
    protected TaskServiceInterface $taskService;

    public function __construct(TaskServiceInterface $taskService)
    {
        $this->taskService = $taskService;
    }

    public function index(IndexTaskRequest $request): JsonResponse
    {
        $requestData = $request->validated();
        $tasks = $this->taskService->getTasks($requestData);
        return response()->json($tasks);
    }

    public function store(StoreTaskRequest $request): JsonResponse
    {
        $requestData = $request->validated();
        try {
            $task = $this->taskService->createTask($requestData);
            return response()->json(['message' => 'success', 'task' => $task]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'task saving error'], 500);
        }
    }

    public function update(UpdateTaskRequest $request, Task $task): JsonResponse
    {
        $requestData = $request->validated();
        try {
            $task = $this->taskService->updateTask($task, $requestData);
            return response()->json(['message' => 'success', 'task' => $task]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'update error'], 500);
        }
    }

    public function show(Task $task): Task
    {
        return $task;
    }

    public function destroy(Task $task): JsonResponse
    {
        try {
            $this->taskService->deleteTask($task);
            return response()->json(['message' => 'success']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'delete error'], 500);
        }
    }
}
