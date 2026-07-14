<?php

namespace Database\Factories;

use App\Models\Penerimaan;
use App\Models\TimExplorasi;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Penerimaan>
 */
class PenerimaanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->teknisiRegistrasi(),
            'tanggal_explorasi'=> fake()->date(),
            'jenis_form' =>fake()->randomElement(['explorasi','penerimaan']),
            'tanggal_penerimaan' => fake()->date(),
            'tempat_asal' => fake()->city(),
            'country'=> fake()->country(),
            'source' =>fake()->sentence(),
            'native' => fake()->country(),
            'exploration_team_id'=> TimExplorasi::factory(),
        ];
    }
}
