<?php

namespace Database\Factories;

use App\Models\Penyemaian;
use App\Models\PenyemaianTanaman;
use App\Models\Tanaman;
use Illuminate\Database\Eloquent\Factories\Factory;

class PenyemaianTanamanFactory extends Factory
{
    protected $model = PenyemaianTanaman::class;

    public function definition(): array
    {
        return [
            'penyemaian_id' => Penyemaian::factory(),
            'tanaman_id' => Tanaman::factory(),
            'status' => 'hidup',
        ];
    }

    public function mati(): static
    {
        return $this->state(fn () => ['status' => 'mati']);
    }
}
