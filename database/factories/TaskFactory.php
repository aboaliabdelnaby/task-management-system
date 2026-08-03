<?php

namespace Database\Factories;

use App\Domain\Enums\Task\TaskPriority;
use App\Domain\Enums\Task\TaskStatus;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        return [
            'project_id' => Project::factory(),

            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),

            'priority' => $this->faker->randomElement(TaskPriority::cases()),
            'status' => $this->faker->randomElement(TaskStatus::cases()),

            'due_date' => $this->faker->dateTimeBetween('now', '+1 month'),
        ];
    }
}
