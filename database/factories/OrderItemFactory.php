<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Order;
use App\Models\Product;

class OrderItemFactory extends Factory
{
    public function definition()
    {
        return [
            'orderid' => Order::factory(),
            'productid' => Product::factory(),
            'quantity' => $this->faker->numberBetween(1, 10),
            'unitprice' => $this->faker->numberBetween(100000, 50000000),
        ];
    }
}
