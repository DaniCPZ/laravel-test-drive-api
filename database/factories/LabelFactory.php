<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class LabelFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => $this->faker->word(3,true),
            'color' => $this->faker->colorName(),
            'user_id' => function(){
                return User::factory()->create()->id;
            }
        ];
    }
}
