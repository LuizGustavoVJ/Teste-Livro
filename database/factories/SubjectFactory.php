<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subject>
 */
class SubjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subjects = [
            'Ficção Científica',
            'Romance',
            'Mistério',
            'Fantasia',
            'Biografia',
            'História',
            'Ciência',
            'Tecnologia',
            'Filosofia',
            'Arte',
            'Culinária',
            'Viagem',
            'Autoajuda',
            'Negócios',
            'Educação',
            'Saúde',
            'Esportes',
            'Política',
            'Religião',
            'Psicologia'
        ];

        return [
            'description' => $this->faker->unique()->randomElement($subjects),
        ];
    }
}

