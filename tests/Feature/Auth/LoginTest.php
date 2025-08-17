<?php

declare(strict_types=1);

namespace Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    #[DataProvider('loginDataProvider')]
    public function test_login(array $payload, int $expectedStatus): void
    {
        if ($expectedStatus === 200) {
            User::factory()->create([
                'username' => $payload['username'],
                'password' => bcrypt($payload['password']),
            ]);
        }

        $response = $this->postJson(route('login'), $payload);

        $response->assertStatus($expectedStatus);
        if ($expectedStatus === 200) {
            $response->assertJsonStructure([
                'access_token',
                'token_type',
                'expires_in'
            ]);
        } elseif ($expectedStatus === 401) {
            $response->assertJson([
                'status' => 'error',
                'message' => 'Unauthorized.'
            ]);
        } elseif ($expectedStatus === 422) {
            $response->assertJsonStructure([
                'message',
                'errors'
            ]);
        }
    }

    public static function loginDataProvider(): array
    {
        return [
            [['username' => 'user1', 'password' => 'secret123'], 200],
            [['username' => 'user1', 'password' => 'wrongpass'], 401],
            [['username' => 'nouser', 'password' => 'secret123'], 401],
            [['username' => '', 'password' => ''], 422],
        ];
    }

    // тест роута api/me
    public function test_me(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'api')->getJson(route('me'));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'data' => [
                'username',
                'registered'
            ]
        ]);
    }
}
