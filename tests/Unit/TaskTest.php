<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Task;
use App\Models\TodoList;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_task_belongs_to_a_todo_list()
    {
        $list = TodoList::factory()->create();
        $task = Task::factory()->create([
            'todo_list_id' => $list->id,
        ]);

        $this->assertInstanceOf(TodoList::class, $task->todo_list);
    }
}
