<?php

namespace Tests\Feature\Profile;

use App\Contracts\Services\ProfileServiceContract;
use Tests\TestCase;

class ProfileContractTest extends TestCase
{
    public function test_profile_routes_are_protected_and_contract_is_stable(): void
    {
        $route = app('router')->getRoutes()->getByName('profile.update');

        $this->assertContains('auth', $route->gatherMiddleware());
        $this->assertContains('not_locked', $route->gatherMiddleware());
        $this->assertTrue(interface_exists(ProfileServiceContract::class));
        $this->assertTrue(method_exists(ProfileServiceContract::class, 'updateProfile'));
        $this->assertTrue(method_exists(ProfileServiceContract::class, 'updatePassword'));
        $this->assertTrue(method_exists(ProfileServiceContract::class, 'updateBankInformation'));
    }
}
