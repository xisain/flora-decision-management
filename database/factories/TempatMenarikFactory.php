<?php

namespace Database\Factories;

use App\Models\TempatMenarik;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TempatMenarik>
 */
class TempatMenarikFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama' => fake()->words(3, true),
            'deskripsi' => fake()->paragraph(),
            'foto' => 'bulungan/tempat-menarik/'.fake()->uuid().'.jpg',
            'kategori' => fake()->randomElement(array_keys(TempatMenarik::CATEGORIES)),
            'is_featured' => false,
            'is_active' => true,
            'urutan' => 0,
        ];
    }
}
