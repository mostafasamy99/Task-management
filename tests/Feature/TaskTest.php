<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskTest extends TestCase
{
    //use RefreshDatabase;

    public function test_create_task()
    {
        $user = User::factory()->create();

        // Act as the authenticated user
        $this->actingAs($user, 'sanctum');

        $data = [
            'title' => 'Test Task',
            'description' => 'This is a test task',
            'status' => 'pending',
            'due_date' => now()->addDay()->toDateString(),
            'user_email' => $user->email,
        ];

        $response = $this->postJson('/api/tasks', $data);

        $response->assertStatus(201);

        $this->assertDatabaseHas('tasks', [
            'title' => 'Test Task',
            'description' => 'This is a test task',
            'status' => 'pending',
            'due_date' => now()->addDay()->toDateString(),
            'user_id' => $user->id,
        ]);
    }

    public function test_get_tasks()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $task = Task::factory()->create(['user_id' => $user->id]);

        $this->assertDatabaseHas('tasks', [
            'title' => $task->title,
        ]);

        $response = $this->getJson('/api/tasks');

        $response->assertStatus(200);

        $response->assertJsonFragment([
            'title' => $task->title,
        ]);
    }

    public function test_update_task()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user);

        $updatedData = [
            'title' => 'Updated Task',
            'description' => 'Updated description',
            'status' => 'completed',
            'due_date' => now()->addDay()->toDateString(),
            'user_email' => $user->email,
        ];

        $response = $this->putJson("/api/tasks/{$task->id}", $updatedData);

        $response->assertStatus(200);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Updated Task',
            'status' => 'completed',
        ]);
    }

    public function test_delete_task()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $task = Task::factory()->create(['user_id' => $user->id]);

        $response = $this->deleteJson("/api/tasks/{$task->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('tasks', [
            'id' => $task->id,
        ]);
    }


}
