<?php

namespace App\Providers;

use App\Repository\Task\TaskRepository;
use App\Repository\Task\TaskRepositoryInterface;
use App\Services\Task\TaskService;
use App\Services\Task\TaskServiceInterface;
use Illuminate\Support\ServiceProvider;

class TaskServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(TaskRepositoryInterface::class, TaskRepository::class);

        $this->app->singleton(TaskServiceInterface::class, function ($app) {
            return new TaskService(
                $app->make(TaskRepositoryInterface::class)
            );
        });
    }

    public function boot(): void
    {
        //
    }
}
