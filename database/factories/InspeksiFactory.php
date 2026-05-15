<?php

namespace Database\Factories;

use App\Models\Inspeksi;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class InspeksiFactory extends Factory
{
    protected $model = Inspeksi::class;

    public function definition(): array
    {
        return [
            'user_id'          => User::factory(),
            'tanggal_inspeksi' => $this->faker->date(),
            'catatan'          => $this->faker->sentence(),
            'stage'            => 'checkup',
        ];
    }

    public function checkup(): static
    {
        return $this->state(fn () => ['stage' => 'checkup']);
    }

    public function labeling(): static
    {
        return $this->state(fn () => ['stage' => 'labeling']);
    }

    public function aklimatisasi(): static
    {
        return $this->state(fn () => ['stage' => 'aklimatisasi']);
    }

    public function evaluasi(): static
    {
        return $this->state(fn () => ['stage' => 'evaluasi']);
    }
}
