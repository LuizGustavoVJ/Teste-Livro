<?php

namespace Database\Factories;

use App\Models\Arquivo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Arquivo>
 */
class ArquivoFactory extends Factory
{
    protected $model = Arquivo::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $extension = $this->faker->randomElement(['jpg', 'png', 'gif', 'pdf']);
        $filename = $this->faker->word() . '_' . time() . '.' . $extension;

        $mimeTypes = [
            'jpg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'pdf' => 'application/pdf',
        ];

        return [
            'nome_original' => $this->faker->word() . '.' . $extension,
            'caminho' => 'uploads/imagens/' . $filename,
            'mime_type' => $mimeTypes[$extension],
        ];
    }
}

