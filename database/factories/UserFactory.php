<?php

namespace Database\Factories;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected static ?string $password = null;

    public function definition(): array
    {
        return [
            'full_name' => fake()->name(),
            'gender' => fake()->randomElement(['male', 'female', null]),
            'date_of_birth' => fake()->optional()->date(),
            'role' => UserRole::User,
            'username' => fake()->unique()->userName(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->boolean(80) ? fake()->unique()->numerify('09########') : null,
            'address' => fake()->optional()->address(),
            'avatar_path' => 'avatars/default.jpg',
            'is_locked' => false,
            'bank_account_number' => fake()->optional()->numerify('############'),
            'bank_name' => fake()->optional()->randomElement(['Vietcombank', 'Techcombank', 'ACB']),
            'password' => static::$password ??= Hash::make('Password123!'),
            'remember_token' => Str::random(10),
        ];
    }

    public function admin(): static
    {
        return $this->state(fn (): array => ['role' => UserRole::Admin]);
    }
}
