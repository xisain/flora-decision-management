<?php

namespace Database\Factories;

use App\Models\CollectorInfo;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CollectorInfoFactory extends Factory
{
    protected $model = CollectorInfo::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'full_name' => fake()->name(),
            'initial_collector_name' => strtoupper(fake()->unique()->lexify('???')),
            'is_manual' => false,
            'last_sequence' => fake()->numberBetween(0, 100),
        ];
    }

    public function manual(): static
    {
        return $this->state(fn () => [
            'user_id' => null,
            'is_manual' => true,
        ]);
    }
}
