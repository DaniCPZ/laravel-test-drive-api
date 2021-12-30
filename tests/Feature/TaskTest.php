<?php

namespace Tests\Feature;

use App\Models\Label;
use Tests\TestCase;
use App\Models\Task;
use App\Models\User;
use App\Models\TodoList;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
    	parent::setUp();
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $this->list = TodoList::factory()->create();
        $this->label = Label::factory()->create();
        $this->task = Task::factory()->create([
            'todo_list_id' => $this->list->id,
            'label_id' => $this->label->id,
        ]);
    }

    public function test_fetch_all_tasks_of_a_todo_list()
    {
        $response = $this->getJson(route('todo-list.tasks.index', $this->list->id))
            ->assertOk()
            ->json();

        $this->assertEquals(1, count($response));
        $this->assertEquals($this->list->id, $response[0]['todo_list_id']);
    }

    public function test_store_a_task_for_a_todo_list()
    {
        $task = Task::factory()->make([
            'todo_list_id' => $this->list->id,
            'label_id' => $this->label->id,
        ]);

        $this->postJson(route('todo-list.tasks.store', $this->list->id), [
            'title' => $task->title,
            'label_id' => $task->label_id,
        ])->assertCreated();

        $this->assertDatabaseHas('tasks', [
            'title' => $task->title,
            'todo_list_id' => $task->todo_list_id,
            'label_id' => $task->label_id,
        ]);
    }

    public function test_store_a_task_for_a_todo_list_without_a_label()
    {
        $task = Task::factory()->make();

        $this->postJson(route('todo-list.tasks.store', $this->list->id), [
            'title' => $task->title
        ])->assertCreated();

        $this->assertDatabaseHas('tasks', [
            'title' => $task->title,
            'todo_list_id' => $this->list->id,
            'label_id' => null
        ]);
    }

    public function test_delete_a_task_from_database()
    {
        $this->deleteJson(route('tasks.destroy', $this->task->id))
             ->assertNoContent();

        $this->assertDatabaseMissing('tasks', [
            'id' => $this->task->id,
            'title ' => $this->task->title,
        ]);
    }

    public function test_update_a_task_of_a_todo_list()
    {
        $this->patchJson(route('tasks.update', $this->task->id), [
            'title' => 'updated title',
        ])->assertOk();

        $this->assertDatabaseHas('tasks', [
            'title' => 'updated title',
        ]);
    }
}