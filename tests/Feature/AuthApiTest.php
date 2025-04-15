<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_user()
    {
        $data = [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->postJson('/api/auth/register', $data);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'token'
        ]);

        $this->assertDatabaseHas(
            'users',
            [
                'email' => 'testuser@example.com',
            ]
        );
    }

    public function test_register_user_wrong_pass_confirm()
    {
        $data = [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password12323',
        ];

        $response = $this->postJson('/api/auth/register', $data);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJson([
            'errors' => [
                'password' => ['Пароль и подтвержденный пароль не совпадают.']
            ]
        ]);
    }

    public function test_register_user_exist_email()
    {
        User::factory()->create(['email' => 'existemail@mail.ru']);

        $data = [
            'name' => 'Test User',
            'email' => 'existemail@mail.ru',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->postJson('/api/auth/register', $data);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJson([
            'errors' => [
                'email' => ['Пользователь с таким email уже существует.']
            ]
        ]);
    }

    public function test_logout_user()
    {
        $user = User::factory()->create();

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/auth/logout');

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'message' => 'Bearer токен был успешно удален',
        ]);

        $this->assertDatabaseMissing('personal_access_tokens', [
            'tokenable_id' => $user->id,
            'tokenable_type' => User::class,
        ]);
    }

    public function test_login_success()
    {
        User::factory()->create([
            'email' => 'test@mail.ru',
            'password' => Hash::make('password'),
        ]);

        $data = [
            'email' => 'test@mail.ru',
            'password' => 'password',
        ];

        $response = $this->postJson('/api/auth/login', $data);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'access_token'
        ]);
    }

    public function test_login_already_auth()
    {
        $user = User::factory()->create([
            'email' => 'test@mail.ru',
            'password' => Hash::make('password'),
        ]);
        $user->createToken('auth_token');

        $data = [
            'email' => 'test@mail.ru',
            'password' => 'password',
        ];

        $response = $this->postJson('/api/auth/login', $data);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'message' => 'Пользователь уже авторизован.'
        ]);
    }
}
