<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category;

class ProductFactory extends Factory
{
    public function definition()
    {
        return [
            'categoryid' => Category::factory(),
            'name' => $this->faker->word(),
            'price' => $this->faker->numberBetween(100000, 50000000),
            'saleprice' => $this->faker->numberBetween(50000, 40000000),
            'IsOnSale' => $this->faker->boolean(),
            'IsPublished' => true,
            'imageUrl' => $this->faker->imageUrl(),
        ];
    }
}
