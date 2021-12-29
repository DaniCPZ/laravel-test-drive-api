<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Task;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskCompletedTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_task_status_can_be_changed()
    {
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
