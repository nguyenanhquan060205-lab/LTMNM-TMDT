<?php

namespace Tests\Feature\Invoice;

use App\Contracts\Services\InvoiceServiceContract;
use ReflectionMethod;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class InvoiceContractTest extends TestCase
{
    public function test_invoice_routes_are_protected_and_pdf_contract_is_stable(): void
    {
        $route = app('router')->getRoutes()->getByName('invoices.show');

        $this->assertContains('auth', $route->gatherMiddleware());
        $this->assertContains('not_locked', $route->gatherMiddleware());
        $this->assertTrue(interface_exists(InvoiceServiceContract::class));

        $method = new ReflectionMethod(InvoiceServiceContract::class, 'generatePdf');

        $this->assertSame(Response::class, $method->getReturnType()?->getName());
    }
}
