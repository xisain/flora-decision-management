<?php

namespace Database\Factories;

use App\Models\Tanaman;
use App\Models\PenerimaanTanaman;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Tanaman>
 */
class TanamanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tanaman_penerimaan_id' => PenerimaanTanaman::factory(),
            'nomor_urut' => fake()->numberBetween(1, 100),
        ];
    }
}
