<?php

namespace Tests\Feature\Foundation;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SeederSmokeTest extends TestCase
{
    use RefreshDatabase;

    public function test_database_seeder_creates_foundation_demo_data(): void
    {
        $this->seed();

        $this->assertDatabaseHas('users', ['username' => 'admin']);
        $this->assertDatabaseHas('users', ['username' => 'minhhuy']);
        $this->assertSame(6, Category::query()->count());
        $this->assertSame(6, Product::query()->count());
    }

    public function test_seeded_passwords_are_hashed(): void
    {
        $this->seed();

        $user = User::query()->where('username', 'admin')->firstOrFail();

        $this->assertNotSame('Password123!', $user->password);
        $this->assertTrue(Hash::check('Password123!', $user->password));
    }
}
