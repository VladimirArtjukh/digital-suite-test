<?php

namespace Tests\Feature;

use Modules\API\V1\Tasks\Models\Tasks;
use Modules\API\V1\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_task()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user, 'sanctum')->postJson('/api/tasks', [
            'title' => 'Test Task',
            'description' => 'Test Description',
            'status' => 'pending',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('tasks', ['title' => 'Test Task']);
    }

    public function test_user_can_only_see_own_tasks()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        Tasks::factory()->create(['user_id' => $user1->id]);
        Tasks::factory()->create(['user_id' => $user2->id]);

        $response = $this->actingAs($user1, 'sanctum')->getJson('/api/tasks');
        $response->assertStatus(200)->assertJsonCount(1);
    }
}
