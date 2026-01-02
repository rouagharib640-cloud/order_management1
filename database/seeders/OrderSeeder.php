<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        Order::factory(100)->create()->each(function ($order) {
            // Create 1-5 order items for each order
            OrderItem::factory(rand(1, 5))->create([
                'order_id' => $order->id,
            ]);
        });
    }
}