<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'roles_id' => Role::factory(),
            'account_status' => 1,
            'phone_number' => fake()->phoneNumber(),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function admin(): static
    {
        return $this->state(fn () => [
            'roles_id' => Role::factory()->admin(),
        ]);
    }


    public function teknisiRegistrasi(): static
    {
        return $this->state(fn () => [
            'roles_id' => Role::factory()->teknisiRegistrasi(),
        ]);
    }

    public function teknisiPembibitan(): static
    {
        return $this->state(fn () => [
            'roles_id' => Role::factory()->teknisiPembibitan(),
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn () => [
            'account_status' => false,
        ]);
    }
}
