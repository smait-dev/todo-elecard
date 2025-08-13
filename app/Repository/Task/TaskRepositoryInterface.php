<?php

namespace App\Repository\Task;

use App\Models\Task;
use Illuminate\Support\Collection;

interface TaskRepositoryInterface
{
    public function getTasks(int $userId, int $limit, int $offset): Collection;

    public function createTask(array $data): Task;

    public function updateTask(Task $task, array $data): Task;

    public function deleteTask(Task $task): void;
}
