<?php

namespace Database\Factories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoleFactory extends Factory
{
    protected $model = Role::class;

    public function definition(): array
    {
        return [
            'name' => fake()->randomElement(['admin', 'teknisi registrasi', 'teknisi pembibitan']),
            'description' => fake()->sentence(),
        ];
    }

    public function admin(): static
    {
        return $this->state(fn () => [
            'name' => 'admin',
            'description' => 'Administrator',
        ]);
    }

    public function peneliti(): static
    {
        return $this->state(fn () => [
            'name' => 'teknisi registrasi',
            'description' => 'teknisi registrasi',
        ]);
    }

    public function teknisiRegistrasi(): static
    {
        return $this->state(fn () => [
            'name' => 'teknisi registrasi',
            'description' => 'Teknisi Registrasi',
        ]);
    }

    public function teknisiPembibitan(): static
    {
        return $this->state(fn () => [
            'name' => 'teknisi pembibitan',
            'description' => 'Teknisi Pembibitan',
        ]);
    }
}
