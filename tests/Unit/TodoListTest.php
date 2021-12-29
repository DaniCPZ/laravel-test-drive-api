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


    public function test_if_todo_list_is_deleted_then_all_its_tasks_will_be_deleted()
    {
        $list = TodoList::factory()->create();
        $task = Task::factory()->create([
            'todo_list_id' => $list->id,
        ]);
        $task2 = Task::factory()->create();

        $list->delete();

        $this->assertDatabaseMissing('todo_lists', [
            'id' => $list->id,
        ]);
        $this->assertDatabaseMissing('tasks', [
            'id' => $task->id,
        ]);
        $this->assertDatabaseHas('tasks', [
            'id' => $task2->id,
        ]);
    }
}
