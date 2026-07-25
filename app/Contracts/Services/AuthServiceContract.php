<?php

declare(strict_types=1);

namespace App\Contracts\Services;

use App\Models\User;

interface AuthServiceContract
{
    /**
     * Register a public user from validated input. Public registration ignores
     * any incoming role and uses the default user role contract.
     *
     * @param  array{full_name:string,username:string,email?:string|null,password:string}  $attributes
     */
    public function register(array $attributes, bool $loginAfterRegistration = true): User;

    /**
     * Attempt login with validated credentials.
     *
     * @param  array{username:string,password:string}  $credentials
     */
    public function attemptLogin(array $credentials, bool $remember = false): User;

    public function logout(): void;

    public function currentUser(): ?User;
}
