<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class OrderFactory extends Factory
{
    public function definition()
    {
        return [
            'userid' => User::factory(),
            'status' => $this->faker->randomElement(['Pending', 'Processing', 'Delivered', 'Cancelled']),
            'paymentstatus' => $this->faker->randomElement(['Paid', 'Unpaid', 'Refunded']),
            'totalprice' => $this->faker->numberBetween(100000, 100000000),
            'shipname' => $this->faker->name(),
            'shipaddress' => $this->faker->address(),
            'shipphone' => $this->faker->phoneNumber(),
        ];
    }
}
