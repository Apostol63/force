<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Task;
use App\Enums\TaskStatus;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskTests extends TestCase
{
    use RefreshDatabase;

    public function test_create_task()
    {
        $user = User::factory()->create();

        $task = Task::factory()->raw();

        $response = $this
            ->actingAs($user, 'sanctum')
            ->postJson('/api/tasks', $task);

        $response->assertStatus(Response::HTTP_OK);

        $this->assertDatabaseHas('tasks', [
            'title' => $task['title'],
            'description' => $task['description'],
            'status' => $task['status'],
            'user_id' => $user->id,
        ]);
    }

    public function test_update_task()
    {
        $user = User::factory()->create();

        $task = Task::factory()->create([
            'user_id' => $user->id,
            'status' => TaskStatus::NEW,
        ]);

        $newData = [
            'title' => $task->title . 'test1',
            'description' => $task->description . 'descr1',
            'status' => TaskStatus::IN_PROGRESS,
        ];

        $response = $this
            ->actingAs($user, 'sanctum')
            ->putJson("/api/tasks/{$task->id}", $newData);

        $response->assertStatus(Response::HTTP_OK);

        $response->assertJson([
            'data' => [
                'title' => $task->title . 'test1',
                'description' => $task->description . 'descr1',
                'status' => TaskStatus::IN_PROGRESS->value,
            ]
        ]);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => $task->title . 'test1',
            'description' => $task->description . 'descr1',
            'status' => TaskStatus::IN_PROGRESS,
            'user_id' => $user->id,
        ]);
    }

    public function test_update_task_wrong_user()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $task = Task::factory()->create([
           'user_id' => $user2->id,
        ]);

        $newData = [
            'title' => $task->title . '232',
            'description' => $task->description . '323232',
            'status' => TaskStatus::COMPLETED,
        ];

        $response = $this
            ->actingAs($user1, 'sanctum')
            ->putJson("/api/tasks/{$task->id}", $newData);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function test_update_wrong_task(): void
    {
        $user = User::factory()->create();

        $newData = [
            'title' => 'Задача рандом',
            'description' => 'Описание рандом',
            'status' => TaskStatus::COMPLETED->value,
        ];

        $wrongUuidTask = '99999999-9999-9999-9999-999999999999';

        $response = $this
            ->actingAs($user, 'sanctum')
            ->putJson("/api/tasks/{$wrongUuidTask}", $newData);

        $response->assertStatus(Response::HTTP_NOT_FOUND);
        $response->assertJson([
            'message' => 'Такой задачи не существует',
        ]);
    }

    public function test_update_task_wrong_data(): void
    {
        $user = User::factory()->create();

        $task = Task::factory()->create([
            'user_id' => $user->id,
        ]);

        $newData = [
            'status' => 'рандом',
        ];

        $response = $this
            ->actingAs($user, 'sanctum')
            ->putJson("/api/tasks/{$task->id}", $newData);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

}
