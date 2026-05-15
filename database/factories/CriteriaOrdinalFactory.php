<?php

namespace Database\Factories;

use App\Models\CriteriaOrdinal;
use App\Models\Criteria;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CriteriaOrdinal>
 */
class CriteriaOrdinalFactory extends Factory
{
    protected $model = CriteriaOrdinal::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'criteria_id' => Criteria::factory()->ordinal(),
            'label' => fake()->randomElement(['Buruk', 'Cukup', 'Baik', 'Sangat Baik']),
            'nilai' => fake()->numberBetween(1, 5),
            'operator' => 'eq',
            'range_from' => null,
            'range_to' => null,
            'urutan' => fake()->numberBetween(1, 5),
        ];
    }

    public function baik(): static
    {
        return $this->state(fn () => [
            'label' => 'Baik',
            'nilai' => 4,
            'urutan' => 4,
        ]);
    }

    public function buruk(): static
    {
        return $this->state(fn () => [
            'label' => 'Buruk',
            'nilai' => 1,
            'urutan' => 1,
        ]);
    }
}
