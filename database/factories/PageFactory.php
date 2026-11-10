<?php

namespace Database\Factories;

use App\Models\Page;
use Illuminate\Database\Eloquent\Factories\Factory;

class PageFactory extends Factory
{
    protected $model = Page::class;

    public function definition(): array
    {
        $title = fake()->sentence(3);
        return [
            'title' => $title,
            'slug' => str()->slug($title).'-'.fake()->unique()->randomNumber(4),
            'menu_name' => $title,
            'description' => '<p>'.fake()->paragraphs(3, true).'</p>',
            'status' => 'publish',
            'sort' => fake()->numberBetween(0, 20),
        ];
    }
}
