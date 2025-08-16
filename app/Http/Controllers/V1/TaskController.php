<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Task\IndexTaskRequest;
use App\Http\Requests\Api\V1\Task\StoreTaskRequest;
use App\Http\Requests\Api\V1\Task\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Services\Task\TaskServiceInterface;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TaskController extends Controller
{
    use AuthorizesRequests;

    protected TaskServiceInterface $taskService;

    public function __construct(TaskServiceInterface $taskService)
    {
        $this->taskService = $taskService;
    }

    public function index(IndexTaskRequest $request): JsonResponse|ResourceCollection
    {
        $tasks = $this->taskService->getTasks($request->validated());
        return TaskResource::collection($tasks);
    }

    public function store(StoreTaskRequest $request): JsonResponse
    {
        try {
            $task = $this->taskService->createTask($request->validated());
            return response()->json(['status' => 'success', 'id' => $task->id]);
        } catch (\Exception) {
            return response()->json(['status' => 'error', 'message' => 'Ошибка при добавлении задачи.'], 500);
        }
    }

    public function update(UpdateTaskRequest $request, $id): JsonResponse
    {
        try {
            $task = Task::query()->findOrFail($id);
            $this->authorize('update', $task);
            $this->taskService->updateTask($task, $request->validated());
        } catch (ModelNotFoundException) {
            return response()->json(['status' => 'error', 'message' => 'Задача не найдена.'], 404);
        } catch (AuthorizationException) {
            return response()->json(['status' => 'error', 'message' => 'Доступ запрещен.'], 403);
        } catch (\Exception) {
            return response()->json(['status' => 'error', 'message' => 'Ошибка при обновлении задачи.'], 500);
        }

        return response()->json(['status' => 'success']);
    }

    public function show(int $id): TaskResource|JsonResponse
    {

        try {
            $task = Task::query()->findOrFail($id);
            $this->authorize('view', $task);
            return new TaskResource($task);
        } catch (ModelNotFoundException) {
            return response()->json(['status' => 'error', 'message' => 'Задача не найдена.'], 404);
        } catch (AuthorizationException) {
            return response()->json(['status' => 'error', 'message' => 'Доступ запрещен.'], 403);
        }
    }

    public function destroy(Task $task): JsonResponse
    {
        try {
            $this->authorize('destroy', $task);
            $this->taskService->deleteTask($task);
            return response()->json(['status' => 'success']);
        } catch (ModelNotFoundException) {
            return response()->json(['status' => 'error', 'message' => 'Задача не найдена'], 404);
        } catch (AuthorizationException) {
            return response()->json(['status' => 'error', 'message' => 'Доступ запрещен'], 403);
        } catch (\Exception) {
            return response()->json(['status' => 'error', 'message' => 'Ошибка при удалении задачи'], 500);
        }
    }
}
