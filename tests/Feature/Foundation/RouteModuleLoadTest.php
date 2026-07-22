<?php

namespace Tests\Feature\Foundation;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class RouteModuleLoadTest extends TestCase
{
    public function test_contract_route_names_exist(): void
    {
        foreach ([
            'home',
            'products.index',
            'products.show',
            'auth.login.create',
            'auth.login.store',
            'auth.logout',
            'auth.register.create',
            'auth.register.store',
            'profile.show',
            'profile.edit',
            'profile.update',
            'profile.password.update',
            'profile.bank.update',
            'seller.products.index',
            'seller.products.create',
            'seller.products.store',
            'seller.products.edit',
            'seller.products.update',
            'seller.products.destroy',
            'cart.index',
            'cart.items.store',
            'cart.items.update',
            'cart.items.destroy',
            'orders.index',
            'orders.show',
            'orders.store',
            'orders.cancel',
            'seller.orders.index',
            'seller.orders.show',
            'seller.order-items.confirm',
            'seller.order-items.cancel',
            'reviews.create',
            'reviews.store',
            'complaints.index',
            'complaints.show',
            'complaints.create',
            'complaints.store',
            'messages.index',
            'messages.show',
            'messages.store',
            'messages.read',
            'messages.destroy',
            'invoices.show',
            'invoices.download',
            'admin.dashboard',
            'admin.users.index',
            'admin.users.lock',
            'admin.users.unlock',
            'admin.products.index',
            'admin.products.update-status',
            'admin.orders.index',
            'admin.complaints.index',
            'admin.complaints.update',
            'admin.categories.index',
            'admin.categories.store',
            'admin.categories.update',
            'admin.categories.destroy',
        ] as $routeName) {
            $this->assertTrue(Route::has($routeName), "Missing route {$routeName}");
        }
    }
}
