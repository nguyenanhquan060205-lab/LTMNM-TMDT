<?php

namespace Tests\Feature\Foundation;

use App\Models\User;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PasswordHashingTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_is_authenticatable_and_password_is_hashed(): void
    {
        $user = User::factory()->create(['password' => 'Password123!']);

        $this->assertInstanceOf(Authenticatable::class, $user);
        $this->assertNotSame('Password123!', $user->password);
        $this->assertTrue(Hash::check('Password123!', $user->password));
    }
}
