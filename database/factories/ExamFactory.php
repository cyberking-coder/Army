<?php

namespace Database\Factories;

use App\Models\Exam;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExamFactory extends Factory
{
    protected $model = Exam::class;

    public function definition(): array
    {
        $name = fake()->randomElement([
            'Territorial Army Commission (TAC) Exam',
            'TA Officer Entry Exam',
            'TA Soldier Recruitment Exam',
            'TA JCO Written Test',
        ]).' - '.fake()->date('Y');
        return [
            'name' => $name,
            'slug' => str()->slug($name).'-'.fake()->unique()->randomNumber(4),
            'description' => fake()->paragraph(),
            'exam_date' => fake()->dateTimeBetween('+1 week', '+6 months'),
            'is_active' => true,
        ];
    }
}
