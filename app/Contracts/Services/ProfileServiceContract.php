<?php

declare(strict_types=1);

namespace App\Contracts\Services;

use App\Models\User;

interface ProfileServiceContract
{
    /**
     * @param  array{full_name:string,phone?:string|null,address?:string|null,avatar_path?:string|null}  $attributes
     */
    public function updateProfile(User $user, array $attributes): User;

    public function updatePassword(User $user, string $currentPassword, string $newPassword): bool;

    /**
     * @param  array{bank_account_number?:string|null,bank_name?:string|null}  $attributes
     */
    public function updateBankInformation(User $user, array $attributes): User;
}
