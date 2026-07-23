<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\Services\AuthServiceContract;
use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthService implements AuthServiceContract
{
    public function register(array $attributes, bool $loginAfterRegistration = true): User
    {
        $user = User::query()->create([
            'full_name' => $attributes['full_name'],
            'username' => $attributes['username'],
            'email' => $attributes['email'] ?? null,
            'role' => UserRole::User,
            'password' => $attributes['password'],
        ]);

        if ($loginAfterRegistration) {
            Auth::login($user);
        }

        return $user;
    }

    public function attemptLogin(array $credentials, bool $remember = false): User
    {
        if (! Auth::attempt($credentials, $remember)) {
            throw ValidationException::withMessages([
                'username' => 'The provided credentials are incorrect.',
            ]);
        }

        /** @var User $user */
        $user = Auth::user();

        if ($user->is_locked) {
            Auth::logout();

            throw ValidationException::withMessages([
                'username' => 'This account is locked.',
            ]);
        }

        return $user;
    }

    public function logout(): void
    {
        Auth::logout();
    }

    public function currentUser(): ?User
    {
        /** @var User|null $user */
        $user = Auth::user();

        return $user;
    }
}
