<?php

namespace Database\Factories;

use App\Models\TimExplorasi;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TimExplorasi>
 */
class TimExplorasiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = TimExplorasi::class;

    public function definition(): array
    {
        return [
            'nama_tim' => 'Tim '.fake()->city(),
            'deskripsi_team' => fake()->sentence(),
            'lokasi_explorasi' => fake()->city(),
        ];
    }
}
