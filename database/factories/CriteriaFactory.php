<?php

namespace Database\Factories;

use App\Models\Criteria;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Criteria>
 */
class CriteriaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Criteria::class;

    public function definition(): array
    {
        return [
            'nama_criteria' => fake()->randomElement([
                'Tingkat Kesehatan',
                'Tinggi Tanaman',
                'Jumlah Daun',
                'Kondisi Batang',
            ]),
            'satuan' => fake()->randomElement(['cm', 'helai', null]),
            'bobot' => fake()->randomFloat(2, 0.01, 1.00),
            'tipe' => fake()->randomElement(['benefit', 'cost']),
            'skala' => fake()->randomElement(['numerik', 'ordinal']),
            'preference_function' => fake()->randomElement([
                'usual',
                'quasi',
                'linear',
                'level',
                'gaussian',
                'v_shape',
            ]),
            'param_q' => null,
            'param_p' => null,
            'param_sigma' => null,
            'is_active' => true,
        ];
    }

    public function numeric(): static
    {
        return $this->state(fn () => [
            'skala' => 'numerik',
            'satuan' => 'cm',
        ]);
    }

    public function ordinal(): static
    {
        return $this->state(fn () => [
            'skala' => 'ordinal',
            'satuan' => null,
        ]);
    }

    public function benefit(): static
    {
        return $this->state(fn () => [
            'tipe' => 'benefit',
        ]);
    }

    public function cost(): static
    {
        return $this->state(fn () => [
            'tipe' => 'cost',
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn () => [
            'is_active' => false,
        ]);
    }
}
