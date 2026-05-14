<?php

namespace Database\Factories;

use App\Models\AnggotaTimExplorasi;
use App\Models\TimExplorasi;
use App\Models\CollectorInfo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AnggotaTimExplorasi>
 */
class AnggotaTimExplorasiFactory extends Factory
{
    protected $model = AnggotaTimExplorasi::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'exploration_team_id' => TimExplorasi::factory(),
            'collector_id' => CollectorInfo::factory(),
            'Peran' => fake()->randomElement(['Ketua', 'Anggota', 'Dokumentasi']),
        ];
    }
}
