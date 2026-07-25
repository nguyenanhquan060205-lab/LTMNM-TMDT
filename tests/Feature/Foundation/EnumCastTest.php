<?php

namespace Tests\Feature\Foundation;

use App\Enums\ProductStatus;
use App\Enums\UserRole;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EnumCastTest extends TestCase
{
    use RefreshDatabase;

    public function test_enum_casts_work_for_user_and_product(): void
    {
        $user = User::factory()->create(['role' => UserRole::Admin]);
        $product = Product::factory()->create(['status' => ProductStatus::Hidden]);

        $this->assertSame(UserRole::Admin, $user->fresh()->role);
        $this->assertSame(ProductStatus::Hidden, $product->fresh()->status);
    }
}
