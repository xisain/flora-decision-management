<?php

namespace Database\Factories;

use App\Models\Criteria;
use App\Models\CriteriaOrdinal;
use App\Models\InspeksiNilaiCriteria;
use App\Models\InspeksiTanaman;
use Illuminate\Database\Eloquent\Factories\Factory;

class InspeksiNilaiCriteriaFactory extends Factory
{
    protected $model = InspeksiNilaiCriteria::class;

    public function definition(): array
    {
        return [
            'inspeksi_tanaman_id' => InspeksiTanaman::factory(),
            'criteria_id'         => Criteria::factory(),
            'nilai_numeric'       => $this->faker->randomFloat(2, 0, 100),
            'criteria_ordinal_id' => null,
        ];
    }

    // State: nilai dari kriteria numerik
    public function numerik(float $nilai): static
    {
        return $this->state(fn () => [
            'nilai_numeric'       => $nilai,
            'criteria_ordinal_id' => null,
        ]);
    }

    // State: nilai dari kriteria ordinal — butuh CriteriaOrdinal yang sudah ada
    public function ordinal(CriteriaOrdinal $ordinal): static
    {
        return $this->state(fn () => [
            'criteria_id'         => $ordinal->criteria_id,
            'nilai_numeric'       => null,
            'criteria_ordinal_id' => $ordinal->id,
        ]);
    }
}
