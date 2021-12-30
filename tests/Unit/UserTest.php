<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\TodoList;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_has_many_todo_lists()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $list = TodoList::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->assertInstanceOf(TodoList::class, $user->todo_lists->first());
    }
}