<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Task\IndexTaskRequest;
use App\Http\Requests\Api\V1\Task\StoreTaskRequest;
use App\Http\Requests\Api\V1\Task\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Http\Responses\ApiResponse;
use App\Models\Task;
use App\Services\Task\TaskServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    use AuthorizesRequests;

    protected TaskServiceInterface $taskService;

    public function __construct(TaskServiceInterface $taskService)
    {
        $this->taskService = $taskService;
    }

    public function index(IndexTaskRequest $request): JsonResponse
    {
        try {
            $tasks = $this->taskService->getTasks($request->validated());
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return ApiResponse::error('Failed to retrieve tasks.');
        }
        return ApiResponse::success(TaskResource::collection($tasks));
    }

    public function store(StoreTaskRequest $request): JsonResponse
    {
        try {
            $task = $this->taskService->createTask($request->validated());
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return ApiResponse::error('Failed to save tasks.');
        }
        return ApiResponse::success(['id' => $task->id]);
    }

    public function update(UpdateTaskRequest $request, Task $task): JsonResponse
    {
        $this->authorize('update', $task);
        try {
            $this->taskService->updateTask($task, $request->validated());
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return ApiResponse::error('Failed to update tasks.');
        }
        return ApiResponse::success();
    }

    public function show(Task $task): JsonResponse
    {
        $this->authorize('view', $task);
        return ApiResponse::success(new TaskResource($task));
    }

    public function destroy(Task $task): JsonResponse
    {
        $this->authorize('destroy', $task);
        try {
            $this->taskService->deleteTask($task);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return ApiResponse::error('Failed to delete tasks.');
        }
        return ApiResponse::success();
    }
}
