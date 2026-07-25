<?php

namespace Tests\Feature\Products;

use App\Contracts\Services\MediaServiceContract;
use App\Contracts\Services\ProductServiceContract;
use Tests\TestCase;

class ProductContractTest extends TestCase
{
    public function test_product_routes_and_service_contracts_are_stable(): void
    {
        $route = app('router')->getRoutes()->getByName('seller.products.store');

        $this->assertContains('auth', $route->gatherMiddleware());
        $this->assertContains('not_locked', $route->gatherMiddleware());
        $this->assertTrue(interface_exists(ProductServiceContract::class));
        $this->assertTrue(interface_exists(MediaServiceContract::class));
        $this->assertTrue(method_exists(ProductServiceContract::class, 'publicIndex'));
        $this->assertTrue(method_exists(ProductServiceContract::class, 'createForSeller'));
        $this->assertTrue(method_exists(MediaServiceContract::class, 'storeProductImage'));
    }
}
