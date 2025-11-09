<?php

namespace Database\Seeders;

use App\Enums\TaskStatus;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // \App\Models\Task::factory()->count(20)->create();

        // Ensure there are users in the DB before assigning tasks
        if (User::count() === 0) {
            $this->command->warn('No users found! Run UserSeeder first.');
            return;
        }

        $statuses = [TaskStatus::PENDING, TaskStatus::INPROGRESS, TaskStatus::COMPLETED];


        // Create 20 fake tasks
        for ($i = 1; $i <= 20; $i++) {
            Task::create([
                'user_id' => User::inRandomOrder()->first()->id,
                'title' => fake()->sentence(4),
                'description' => fake()->paragraph(),
                'status' => fake()->randomElement($statuses),
            ]);
        }
    }
}
