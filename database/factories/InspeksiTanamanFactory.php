<?php

namespace Database\Factories;

use App\Models\Inspeksi;
use App\Models\InspeksiTanaman;
use App\Models\Tanaman;
use Illuminate\Database\Eloquent\Factories\Factory;

class InspeksiTanamanFactory extends Factory
{
    protected $model = InspeksiTanaman::class;

    public function definition(): array
    {
        return [
            'inspeksi_id' => Inspeksi::factory(),
            'tanaman_id'  => Tanaman::factory(),
            'status'      => 'hidup',
            'catatan'     => $this->faker->sentence(),
        ];
    }

    public function mati(): static
    {
        return $this->state(fn () => ['status' => 'mati']);
    }

    public function sakit(): static
    {
        return $this->state(fn () => ['status' => 'sakit']);
    }
}
