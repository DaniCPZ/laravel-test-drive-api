<?php

namespace Tests\Unit;

use App\Models\Task;
use Tests\TestCase;
use App\Models\TodoList;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TodoListTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_todo_list_can_has_many_tasks()
    {
        $list = TodoList::factory()->create();
        $task = Task::factory()->create([
            'todo_list_id' => $list->id,
        ]);

        $this->assertInstanceOf(Collection::class, $list->tasks);
        $this->assertInstanceOf(Task::class, $list->tasks->first());
    }
}
