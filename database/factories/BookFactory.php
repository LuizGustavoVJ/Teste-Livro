<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'publication_year' => $this->faker->numberBetween(1900, 2024),
            'isbn' => $this->faker->isbn13(),
            'price' => $this->faker->randomFloat(2, 10, 200),
            'cover_image_path' => null,
        ];
    }

    /**
     * Indica que o livro deve ter uma imagem de capa.
     */
    public function withCoverImage(): static
    {
        return $this->state(fn (array $attributes) => [
            'cover_image_path' => 'book-covers/' . $this->faker->uuid() . '.jpg',
        ]);
    }
}

