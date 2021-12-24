<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\App;

class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->title(),
            'publisher' => $this->faker->company(),
            'image' => $this->faker->image(public_path('images'), 640, 480, null, false, true),
            'category_id' => random_int(1, 5),
            'user_id' => random_int(1, 101),
            'quantity' => random_int(10, 50),
            'price' => rand(20000.00, 50000.00),
        ];
    }
}
