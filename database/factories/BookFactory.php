<?php

namespace Database\Factories;

use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    protected $model = Book::class;

    public function definition(): array
    {
        $title = fake()->catchPhrase().' Guide';
        $price = fake()->randomElement([499, 899, 999, 1299, 1499, 1999]);
        return [
            'title' => $title,
            'slug' => str()->slug($title).'-'.fake()->unique()->randomNumber(4),
            'author' => fake()->name(),
            'description' => fake()->paragraph(5),
            'price' => $price,
            'discount_price' => fake()->boolean(60) ? $price - 200 : null,
            'stock' => fake()->numberBetween(5, 200),
            'is_active' => true,
        ];
    }
}
