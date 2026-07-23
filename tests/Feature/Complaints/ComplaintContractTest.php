<?php

namespace Tests\Feature\Complaints;

use App\Contracts\Services\ComplaintServiceContract;
use App\Models\OrderItem;
use ReflectionMethod;
use Tests\TestCase;

class ComplaintContractTest extends TestCase
{
    public function test_complaint_route_and_order_item_contract_are_stable(): void
    {
        $route = app('router')->getRoutes()->getByName('complaints.store');

        $this->assertContains('auth', $route->gatherMiddleware());
        $this->assertTrue(interface_exists(ComplaintServiceContract::class));

        $method = new ReflectionMethod(ComplaintServiceContract::class, 'createForOrderItem');

        $this->assertSame(OrderItem::class, $method->getParameters()[1]->getType()?->getName());
    }
}
