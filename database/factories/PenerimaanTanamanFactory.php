<?php

namespace Database\Factories;

use App\Models\Penerimaan;
use App\Models\PenerimaanTanaman;
use App\Models\TanamanInfo;
use App\Models\CollectorInfo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PenerimaanTanaman>
 */
class PenerimaanTanamanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'penerimaan_id' => Penerimaan::factory(),
            'jumlah_material' => (string) fake()->numberBetween(1, 20),
            'nomor_akses' => fake()->unique()->bothify('ACC-####'),
            'habitus' => fake()->randomElement(['tree', 'shrub']),
            'tanaman_info_id' => TanamanInfo::factory(),
            'collector_id' => CollectorInfo::factory(),
            'locality' => fake()->city(),
            'vak_no' => fake()->bothify('VAK-##'),
        ];
    }
}
