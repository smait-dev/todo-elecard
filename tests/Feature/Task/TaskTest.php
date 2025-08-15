<?php

declare(strict_types=1);

namespace Task;

use PHPUnit\Framework\Attributes\DataProvider;
use App\Models\{Task, User};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_create_task_success(): void
    {
        $payload = ['title' => 'programming', 'description' => 'desc', 'due_date' => '10.10.2030', 'status' => 'new'];
        $loggedUser = $this->actingAs($this->user, 'api');

        $response = $loggedUser->postJson(route('tasks.store'), $payload);

        $response->assertStatus(200);
        $response->assertJson(['status' => 'success']);
        $this->assertDatabaseHas('tasks', [
            'title' => $payload['title'],
            'user_id' => $this->user->id,
        ]);
    }

    public function test_cannot_access_task_of_another_user(): void
    {
        /** @var User $otherUser */
        $otherUser = User::factory()->create();
        $otherUserTask = Task::factory()->create(['user_id' => $otherUser->id]);

        foreach (['update', 'show', 'destroy'] as $route) {
            $this->actingAs($this->user, 'api')
                ->putJson(route("tasks.$route", $otherUserTask))
                ->assertStatus(403);
        }
    }

    #[DataProvider('invalidCreateTaskDataProvider')]
    public function test_create_task_validation_errors(array $payload): void
    {
        $this->actingAs($this->user, 'api')
            ->postJson(route('tasks.store'), $payload)
            ->assertStatus(422);
    }

    public static function invalidCreateTaskDataProvider(): array
    {
        return [
            'miss title' => [
                ['description' => 'desc', 'due_date' => '10.10.2030', 'status' => 'new'],
            ],
            'empty title' => [
                ['description' => 'desc', 'due_date' => '10.10.2030', 'status' => 'new'],
            ],
            'miss status' => [
                ['title' => 'задача', 'description' => 'desc', 'due_date' => '10.10.2030'],
            ],
        ];
    }

    #[DataProvider('invalidUpdateTaskDataProvider')]
    public function test_update_task_validation_errors(array $payload): void
    {
        $task = Task::factory()->create(['user_id' => $this->user->id]);

        $this->actingAs($this->user, 'api')
            ->putJson(route('tasks.update', $task), $payload)
            ->assertStatus(422);
    }

    public static function invalidUpdateTaskDataProvider(): array
    {
        return [
            'empty title' => [
                ['title' => ''],
            ],
            'title too long' => [
                ['title' => str_repeat('a', 256)],
            ],
            'title not string' => [
                ['title' => 12345],
            ],
            'description not string' => [
                ['description' => 12345],
            ],
            'invalid due_date format' => [
                ['due_date' => 'not-a-date'],
            ],
            'due_date in past' => [
                ['due_date' => '2020-01-01'],
            ],
            'invalid status' => [
                ['status' => 'invalid_status'],
            ],
            'empty status' => [
                ['status' => ''],
            ],
        ];
    }

    public function test_update_show_destroy_not_found(): void
    {
        foreach (['update', 'show', 'destroy'] as $route) {
            $this->actingAs($this->user, 'api')
                ->putJson(route("tasks.$route", 999999), ['title' => 'test'])
                ->assertStatus(404);
        }
    }
}
