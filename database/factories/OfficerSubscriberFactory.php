<?php

namespace Database\Factories;

use App\Models\OfficerSubscriber;
use Illuminate\Database\Eloquent\Factories\Factory;

class OfficerSubscriberFactory extends Factory
{
    protected $model = OfficerSubscriber::class;

    public function definition(): array
    {
        return [
            'full_name' => fake()->name(),
            'mobile' => fake()->numerify('9#########'),
            'email' => fake()->unique()->safeEmail(),
            'status' => fake()->randomElement(['subscribed', 'subscribed', 'subscribed', 'unverified', 'unsubscribed']),
            'verified_at' => fn (array $a) => $a['status'] === 'subscribed' ? now() : null,
        ];
    }
}
