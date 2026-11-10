<?php

namespace Database\Factories;

use App\Models\Newsline;
use Illuminate\Database\Eloquent\Factories\Factory;

class NewslineFactory extends Factory
{
    protected $model = Newsline::class;

    public function definition(): array
    {
        return [
            'headline' => fake()->sentence(8),
            'is_active' => true,
            'sort' => fake()->numberBetween(0, 10),
        ];
    }
}
