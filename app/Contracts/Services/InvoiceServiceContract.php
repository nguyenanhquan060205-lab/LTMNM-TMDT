<?php

declare(strict_types=1);

namespace App\Contracts\Services;

use App\Models\Order;
use Symfony\Component\HttpFoundation\Response;

interface InvoiceServiceContract
{
    public function generatePdf(Order $order): Response;
}
