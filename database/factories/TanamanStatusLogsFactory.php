<?php

namespace Database\Factories;

use App\Models\Tanaman;
use App\Models\TanamanStatusLogs;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends Factory<TanamanStatusLogs>
 */
class TanamanStatusLogsFactory extends Factory
{
    protected $model = TanamanStatusLogs::class;

    /**
     * @return array{tanaman_id: TanamanFactory, stage: string, status: string, user_id: UserFactory, catatan: string, tanggal_proses: Carbon}
     */
    public function definition(): array
    {
        return [
            'tanaman_id' => Tanaman::factory(),
            'stage' => fake()->randomElement(['penyemaian', 'checkup', 'labeling', 'aklimatisasi', 'evaluasi', 'siap_tanam']),
            'status' => fake()->randomElement(['hidup', 'mati', 'recovery', 'dormant']),
            'user_id' => User::factory(),
            'catatan' => fake()->sentence(),
            'tanggal_proses' => now(),
        ];
    }

    public function penyemaian(): static
    {
        return $this->state(fn () => ['stage' => 'penyemaian']);
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

    public function siapTanam(): static
    {
        return $this->state(fn () => ['stage' => 'siap_tanam']);
    }

    public function hidup(): static
    {
        return $this->state(fn () => ['status' => 'hidup']);
    }

    public function mati(): static
    {
        return $this->state(fn () => ['status' => 'mati']);
    }
}
