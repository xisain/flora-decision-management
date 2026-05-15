<?php

namespace Database\Factories;

use App\Models\TanamanInfo;
use Illuminate\Database\Eloquent\Factories\Factory;

class TanamanInfoFactory extends Factory
{
    protected $model = TanamanInfo::class;

    public function definition(): array
    {
        $genera = ['Shorea', 'Dipterocarpus', 'Duabanga', 'Dryobalanops', 'Hopea'];

        return [
            'scientific_name' => $this->faker->unique()->randomElement($genera) . ' ' . $this->faker->word(),
            'nama_lokal'      => $this->faker->word(),
            'marga'           => $this->faker->word(),
            'marga_jenis'     => null,
            'suku'            => $this->faker->word(),
            'spesies'         => $this->faker->word(),
            'author_name'     => $this->faker->lastName(),
        ];
    }
}
