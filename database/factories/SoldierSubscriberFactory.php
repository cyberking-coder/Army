<?php

namespace Database\Factories;

use App\Models\SoldierSubscriber;
use Illuminate\Database\Eloquent\Factories\Factory;

class SoldierSubscriberFactory extends Factory
{
    protected $model = SoldierSubscriber::class;

    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'mobile' => fake()->numerify('9#########'),
            'email' => fake()->unique()->safeEmail(),
            'status' => fake()->randomElement(['subscribed', 'subscribed', 'subscribed', 'unverified', 'unsubscribed']),
            'verified_at' => fn (array $a) => $a['status'] === 'subscribed' ? now() : null,
        ];
    }
}
