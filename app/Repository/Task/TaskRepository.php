<?php

declare(strict_types=1);

namespace App\Repository\Task;

use App\Models\Task;
use Illuminate\Support\Collection;

class TaskRepository implements TaskRepositoryInterface
{
    public function getTasks(int $userId, int $limit, int $offset): Collection
    {
        return Task::query()
            ->select(['id', 'title', 'description', 'due_date', 'status', 'created_at'])
            ->where('user_id', $userId)
            ->limit($limit)
            ->offset($offset)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function createTask(array $data): Task
    {
        return Task::query()->create($data);
    }

    public function updateTask(Task $task, array $data): Task
    {
        $task->update($data);
        return $task;
    }

    public function deleteTask(Task $task): void
    {
        $task->delete();
    }
}
