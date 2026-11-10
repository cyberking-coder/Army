<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        $subtotal = fake()->randomElement([499, 899, 999, 1299, 1499]);
        return [
            'order_number' => strtoupper(Str::random(8)),
            'full_name' => fake()->name(),
            'mobile' => fake()->numerify('9#########'),
            'email' => fake()->safeEmail(),
            'shipping_address' => fake()->streetAddress(),
            'city' => fake()->city(),
            'state' => fake()->randomElement(['Telangana','Maharashtra','Delhi','Karnataka','Tamil Nadu']),
            'pincode' => fake()->numerify('######'),
            'subtotal' => $subtotal,
            'discount' => 0,
            'final_cost' => $subtotal,
            'status' => fake()->randomElement(['pending', 'successful', 'rejected']),
        ];
    }
}
