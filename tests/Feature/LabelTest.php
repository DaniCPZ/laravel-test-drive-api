<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Label;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LabelTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user =  User::factory()->create();
        Sanctum::actingAs($this->user);
    }

    public function test_user_can_create_new_label()
    {
        $label = Label::factory()->raw();

       $this->postJson(route('label.store'), $label)
            ->assertCreated();

        $this->assertDatabaseHas('labels', [
            'title' => $label['title'],
            'color' => $label['color'],
        ]);
    }

    public function test_user_can_delete_a_label()
    {
        $label = Label::factory()->create();

        $this->deleteJson(route('label.destroy', $label->id))->assertNoContent();

        $this->assertDatabaseMissing('labels', [
            'title' => $label->title
        ]);
    }

    public function test_user_can_update_label()
    {
        $label = Label::factory()->create();

        $this->patchJson(route('label.update', $label->id), [
            'color' => 'new-color',
            'title' => $label->title
        ])->assertOk();

        $this->assertDatabaseHas('labels', [
            'color' => 'new-color'
        ]);
    }

    public function test_fetch_all_label_for_a_user()
    {
        $label = Label::factory()->create(['user_id' => $this->user->id]);
        Label::factory()->create();

        $response = $this->getJson(route('label.index'))->assertOk();

        $this->assertEquals($response[0]['title'], $label->title);
    }
}