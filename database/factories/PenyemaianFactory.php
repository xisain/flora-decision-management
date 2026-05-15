<?php

namespace Database\Factories;

use App\Models\Penyemaian;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Penyemaian>
 */
class PenyemaianFactory extends Factory
{
    protected $model = Penyemaian::class;

    /**
     * @return array{user_id: UserFactory, tanggal_semai: string, lokasi_semai: string, catatan: string}
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'tanggal_semai' => $this->faker->date(),
            'lokasi_semai' => $this->faker->city(),
            'catatan' => $this->faker->sentence(),
        ];
    }
}
