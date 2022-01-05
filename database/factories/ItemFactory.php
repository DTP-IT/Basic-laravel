<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
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
        $categoryIds = Category::pluck('id');
        $userIds = User::pluck('id');
        
        return [
            'title' => $this->faker->title(),
            'publisher' => $this->faker->company(),
            'image' => $this->faker->image(public_path('images'), 640, 480, null, false, true),
            'category_id' => $this->faker->randomElement($categoryIds),
            'user_id' => $this->faker->randomElement($userIds),
            'quantity' => random_int(10, 50),
            'price' => rand(20000.00, 50000.00),
        ];
    }
}
