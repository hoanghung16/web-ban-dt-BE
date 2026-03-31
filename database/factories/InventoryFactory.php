<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;

class InventoryFactory extends Factory
{
    public function definition()
    {
        return [
            'ProductId' => Product::factory(),
            'QuantityInStock' => $this->faker->numberBetween(1, 100),
        ];
    }
}
