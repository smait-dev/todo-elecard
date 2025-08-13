<?php

declare(strict_types=1);

namespace App\Services\Task;

use App\Models\Task;
use App\Repository\Task\TaskRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class TaskService implements TaskServiceInterface
{
    protected int $maxLimit;
    protected TaskRepositoryInterface $repository;

    public function __construct(TaskRepositoryInterface $repository)
    {
        $this->maxLimit = (int)env('DB_MAX_ROWS');
        $this->repository = $repository;
    }

    public function getTasks(array $params): Collection
    {
        $limit = $params['limit'] ?? $this->maxLimit;
        $offset = $params['offset'] ?? 0;

        return $this->repository->getTasks(Auth::id(), $limit, $offset);
    }

    public function createTask(array $data): Task
    {
        $data['user_id'] = Auth::id();
        return $this->repository->createTask($data);
    }

    public function updateTask(Task $task, array $data): Task
    {
        return $this->repository->updateTask($task, $data);
    }

    public function deleteTask(Task $task): void
    {
        $this->repository->deleteTask($task);
    }
}
