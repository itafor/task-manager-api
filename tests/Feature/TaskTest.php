<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class TaskTest extends TestCase
{

    protected $user;
    protected $task;

    protected function setUp(): void
    {
        parent::setUp();


        $this->user = User::first();
        $this->task = Task::first();
    }

    /** @test */
    public function it_can_register_a_user()
    {
        $password = 'password';
        $payload = [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' =>  $password,
            'password_confirmation' =>  $password,
        ];

        $response = $this->postJson('/api/auth/register', $payload);
        logger($response->json());
        // $this->user_id = $response->json()['data']['user']['id'];
        $response->assertStatus(200);
    }

    /** @test
     * Test that a user can create a task.
     */
    public function it_can_create_a_task()
    {
        $user = $this->user;

        $payload = [
            'title' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'status' => 'pending',
            'user_id' => $user?->id,
        ];

        $response = $this->actingAs($user, 'user')->postJson('/api/task/create', $payload);
        logger($response->json());

        // $this->task_id = $response->json()['data']['id'];

        $response->assertStatus(200);

        $this->assertDatabaseHas('tasks', ['user_id' => $user->id]);
    }

    /** @test
     * Test that a user can create a task.
     */
    public function it_can_display_task_details()
    {
        $user = $this->user;
        $taskId = $this->task->id;

        $response = $this->actingAs($user, 'user')->getJson("/api/task/details/$taskId");
        logger($response->json());

        $response->assertStatus(200);

        $this->assertDatabaseHas('tasks', ['user_id' => $user->id]);
    }
}
