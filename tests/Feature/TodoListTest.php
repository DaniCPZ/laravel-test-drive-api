<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\TodoList;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TodoListTest extends TestCase
{
    use RefreshDatabase;

    private TodoList $list;

    public function setUp(): void
    {
    	parent::setUp();
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $this->list = TodoList::factory()->create();
    }

    public function test_index_todo_list()
    {
        $response = $this->getJson(route('todo-list.index'));

        $this->assertEquals($this->list->count(), count($response->json()));
    }

    public function test_fetch_single_todo_list()
    {
        $response = $this->getJson(route('todo-list.show', $this->list->id))
            ->assertOk()
            ->json();

        $this->assertEquals($response['id'], $this->list->id);
    }

    public function test_store_new_todo_list()
    {
        $list = TodoList::factory()->make();

    	$response = $this->postJson(route('todo-list.store', [
                'name' => $list->name,
            ]))->assertCreated()
            ->json();

        $this->assertEquals($list->name, $response['name']);

        $this->assertDatabaseHas('todo_lists', [
            'name' => $list->name,
        ]);
    }

    public function test_while_storing_todo_list_name_field_is_required()
    {
        $this->withExceptionHandling();
    	$this->postJson(route('todo-list.store'))
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name']);
    }

    public function test_delete_todo_list()
    {
    	$this->deleteJson(route('todo-list.destroy', $this->list->id))
            ->assertNoContent();

        $this->assertDatabaseMissing('todo_lists', [
            'name' => $this->list->name,
        ]);
    }

    public function test_update_todo_list()
    {
        $this->patchJson(route('todo-list.update', $this->list->id), [
            'name' => 'updated name',
        ])->assertOk();

        $this->assertDatabaseHas('todo_lists', [
            'id' => $this->list->id,
            'name' => 'updated name',
        ]);
    }

    public function test_while_updating_todo_list_name_field_is_required()
    {
        $this->withExceptionHandling();

    	$this->patchJson(route('todo-list.update', $this->list->id))
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name']);
    }
}
