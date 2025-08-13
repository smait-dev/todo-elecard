<?php

namespace App\DTO;

use App\Models\Task;
use DateTime;

class TaskDto
{
    public function __construct(
        public ?string   $title,
        public ?string   $description,
        public ?DateTime $dueDate,
        public ?string   $status,
    )
    {
    }

    public static function fromModel(Task $task): self
    {
        return new self(
            title: $task->title,
            description: $task->description,
            dueDate: $task->due_date ? new DateTime($task['due_date']) : null,
            status: $task->status,
        );
    }

    public static function fromArray(array $task): self
    {
        return new self(
            title: $task['title'],
            description: $task['description'] ?? null,
            dueDate: isset($task['due_date']) ? new DateTime($task['due_date']) : null,
            status: $task['status'],
        );
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'due_date' => $this->dueDate,
            'status' => $this->status,
        ];
    }
}
