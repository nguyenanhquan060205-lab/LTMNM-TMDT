<?php

namespace Tests\Feature\Admin;

use App\Contracts\Services\AdminDashboardServiceContract;
use Tests\TestCase;

class AdminContractTest extends TestCase
{
    public function test_admin_dashboard_route_requires_admin_middleware_and_contract_exists(): void
    {
        $route = app('router')->getRoutes()->getByName('admin.dashboard');

        $this->assertContains('auth', $route->gatherMiddleware());
        $this->assertContains('not_locked', $route->gatherMiddleware());
        $this->assertContains('admin', $route->gatherMiddleware());
        $this->assertTrue(interface_exists(AdminDashboardServiceContract::class));
        $this->assertTrue(method_exists(AdminDashboardServiceContract::class, 'statistics'));
    }
}
