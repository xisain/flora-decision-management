<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\penyemaian;
use Illuminate\Database\Eloquent\Factories\Factory;

class penyemaianFactory extends Factory
{
    protected $model = penyemaian::class;
    /**
     * Summary of definition
     * @return array{catatan: string, lokasi_semai: string, tanggal_semai: string, user_id: UserFactory}
     */
    public function definition(): array
    {
        return [
            'user_id'       => User::factory(),
            'tanggal_semai' => $this->faker->date(),
            'lokasi_semai'  => $this->faker->city(),
            'catatan'       => $this->faker->sentence(),
        ];
    }
}
