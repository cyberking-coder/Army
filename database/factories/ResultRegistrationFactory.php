<?php

namespace Database\Factories;

use App\Models\Exam;
use App\Models\ResultRegistration;
use Illuminate\Database\Eloquent\Factories\Factory;

class ResultRegistrationFactory extends Factory
{
    protected $model = ResultRegistration::class;

    public function definition(): array
    {
        return [
            'exam_id' => Exam::inRandomOrder()->first()?->id ?? Exam::factory(),
            'name' => fake()->name(),
            'mobile' => fake()->numerify('9#########'),
            'email' => fake()->safeEmail(),
            'roll_no' => fake()->bothify('TA##########'),
            'zone' => fake()->randomElement(['North', 'South', 'East', 'West', 'Central', 'Kohima', 'Others']),
            'submitted_at' => fake()->dateTimeBetween('-3 months'),
        ];
    }
}
