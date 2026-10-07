<?php

namespace Database\Factories;

use App\Models\Berita;
use App\Models\kategoriBerita;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Berita>
 */
class BeritaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'slugs' => Str::slug(fake()->unique()->sentence()),
            'kategori_berita_id' => kategoriBerita::factory(),
            'judul' => fake()->sentence(),
            'content' => '<div>'.e(fake()->paragraph()).'</div>',
            'image_url' => '',
            'user_id' => User::factory(),
            'status' => 'public',
            'visitor' => 0,
        ];
    }
}
