<?php

namespace Tests\Feature\Auth;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_and_is_authenticated_with_hashed_password(): void
    {
        $response = $this->post(route('auth.register.store'), [
            'full_name' => 'New User',
            'username' => 'newuser',
            'email' => 'newuser@example.test',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'role' => 'admin',
        ]);

        $response->assertRedirect(route('home'));
        $this->assertAuthenticated();

        $user = User::query()->where('username', 'newuser')->firstOrFail();

        $this->assertNotSame('Password123!', $user->password);
        $this->assertTrue(Hash::check('Password123!', $user->password));
        $this->assertSame(UserRole::User, $user->role);
    }

    public function test_register_rejects_duplicate_username(): void
    {
        User::factory()->create(['username' => 'duplicate']);

        $this->from(route('auth.register.create'))->post(route('auth.register.store'), [
            'full_name' => 'Duplicate User',
            'username' => 'duplicate',
            'email' => 'duplicate@example.test',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ])->assertRedirect(route('auth.register.create'))
            ->assertSessionHasErrors('username');
    }

    public function test_login_with_valid_credentials_regenerates_session(): void
    {
        User::factory()->create([
            'username' => 'loginuser',
            'password' => 'Password123!',
        ]);

        $this->get(route('auth.login.create'));
        $beforeSessionId = session()->getId();

        $this->post(route('auth.login.store'), [
            'username' => 'loginuser',
            'password' => 'Password123!',
        ])->assertRedirect(route('home'));

        $this->assertAuthenticated();
        $this->assertNotSame($beforeSessionId, session()->getId());
    }

    public function test_login_with_invalid_credentials_fails(): void
    {
        User::factory()->create([
            'username' => 'loginuser',
            'password' => 'Password123!',
        ]);

        $this->from(route('auth.login.create'))->post(route('auth.login.store'), [
            'username' => 'loginuser',
            'password' => 'wrong-password',
        ])->assertRedirect(route('auth.login.create'))
            ->assertSessionHasErrors('username');

        $this->assertGuest();
    }

    public function test_locked_user_cannot_login_or_use_protected_routes(): void
    {
        $lockedUser = User::factory()->create([
            'username' => 'lockeduser',
            'is_locked' => true,
            'password' => 'Password123!',
        ]);

        $this->post(route('auth.login.store'), [
            'username' => 'lockeduser',
            'password' => 'Password123!',
        ])->assertSessionHasErrors('username');

        $this->assertGuest();

        $this->actingAs($lockedUser)
            ->get(route('profile.show'))
            ->assertForbidden();
    }

    public function test_regular_user_cannot_access_admin_dashboard(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('admin.dashboard'))
            ->assertForbidden();
    }

    public function test_logout_uses_post_and_invalidates_authenticated_session(): void
    {
        $this->actingAs(User::factory()->create());

        $this->post(route('auth.logout'))
            ->assertRedirect(route('home'));

        $this->assertGuest();
    }

    public function test_guest_is_redirected_from_protected_route(): void
    {
        $this->get(route('profile.show'))
            ->assertRedirect(route('auth.login.create'));
    }
}
