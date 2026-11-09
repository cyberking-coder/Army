<?php

namespace Database\Factories;

use App\Models\Review;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'rating' => fake()->numberBetween(4, 5),
            'comment' => fake()->sentence(12),
            'is_published' => true,
            'submitted_at' => fake()->dateTimeBetween('-1 month'),
        ];
    }
}
