<?php

namespace Database\Factories;

use App\Models\kategoriBerita;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<kategoriBerita>
 */
class KategoriBeritaFactory extends Factory
{
    protected $model = kategoriBerita::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama_kategori' => fake()->words(2, true),
            'deskripsi' => fake()->sentence(),
        ];
    }
}
