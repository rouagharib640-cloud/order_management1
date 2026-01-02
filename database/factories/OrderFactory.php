<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    public function definition(): array
    {
        $statuses = ['pending', 'confirmed', 'processing', 'shipped', 'delivered'];
        
        return [
            'order_number' => 'ORD-' . strtoupper(uniqid()),
            'customer_name' => $this->faker->name(),
            'customer_email' => $this->faker->safeEmail(),
            'customer_phone' => $this->faker->phoneNumber(),
            'shipping_address' => $this->faker->address(),
            'billing_address' => $this->faker->address(),
            'total_amount' => $this->faker->randomFloat(2, 50, 5000),
            'tax_amount' => $this->faker->randomFloat(2, 0, 500),
            'shipping_cost' => $this->faker->randomFloat(2, 0, 100),
            'status' => $this->faker->randomElement($statuses),
            'notes' => $this->faker->optional(0.3)->sentence(),
            'created_at' => $this->faker->dateTimeBetween('-6 months', 'now'),
        ];
    }
}