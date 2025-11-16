<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;

class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'nombre' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password_hash' => static::$password ??= Hash::make('password'),
            'telefono' => $this->faker->phoneNumber(),
            'role_id' => Role::factory(),
            'manager_id' => null,
            'razon_social' => $this->faker->company(),
            'is_active' => true,
            'created_by' => null,
            'updated_by' => null,
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function developer(): static
    {
        return $this->state(fn (array $attributes) => [
            'role_id' => Role::where('name', 'developer')->first()->id,
        ]);
    }

    public function cliente(): static
    {
        return $this->state(fn (array $attributes) => [
            'role_id' => Role::where('name', 'cliente')->first()->id,
        ]);
    }

    public function designer(): static
    {
        return $this->state(fn (array $attributes) => [
            'role_id' => Role::where('name', 'designer')->first()->id,
        ]);
    }

    public function cm(): static
    {
        return $this->state(fn (array $attributes) => [
            'role_id' => Role::where('name', 'cm')->first()->id,
        ]);
    }
}