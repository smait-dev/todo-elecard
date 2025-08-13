<?php

declare(strict_types=1);

namespace App\Services\Task;

use App\Models\Task;
use Illuminate\Support\Collection;

interface TaskServiceInterface
{
    public function getTasks(array $params): Collection;

    public function createTask(array $data): Task;

    public function updateTask(Task $task, array $data): Task;

    public function deleteTask(Task $task): void;
}
