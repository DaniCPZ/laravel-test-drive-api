<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Task;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskCompletedTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_task_status_can_be_changed()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $task = Task::factory()->create([]);

        $this->patchJson(route('tasks.update', $task->id), [
            'title'  => $task->title,
            'status' => Task::STARTED,
        ]);

        $this->assertDatabaseHas('tasks', [
            'status' => Task::STARTED,
        ]);
    }
}
