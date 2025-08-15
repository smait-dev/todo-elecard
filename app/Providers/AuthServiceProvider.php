<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Task;
use App\Policies\TaskPolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected array $policies = [
        Task::class => TaskPolicy::class,
    ];

    public function boot(): void
    {

    }
}
