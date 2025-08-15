<?php

declare(strict_types=1);

namespace Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    #[DataProvider('registrationDataProvider')]
    public function test_default_register(array $payload, int $expectedStatus): void
    {
        $response = $this->postJson(route('register'), $payload);
        $response->assertStatus($expectedStatus);

        if ($expectedStatus === 201) {
            $response->assertJson(['status' => 'success']);
        }
    }

    public static function registrationDataProvider(): array
    {
        return [
            [['username' => 'user1', 'password' => 'secret123'], 201],
            [['username' => 'user2', 'password' => ''], 422],
            [['username' => '', 'password' => 'secret123'], 422],
            [['username' => '', 'password' => ''], 422],
        ];
    }

    public function test_register_existing_user(): void
    {
        User::factory()->create(['username' => 'user1', 'password' => bcrypt('secret123')]);
        $payload = ['username' => 'user1', 'password' => 'secret123'];
        $response = $this->postJson(route('register'), $payload);

        $response->assertStatus(422);
    }
}
