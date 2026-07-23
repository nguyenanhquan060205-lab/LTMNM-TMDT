<?php

declare(strict_types=1);

namespace App\Contracts\Services;

interface AdminDashboardServiceContract
{
    /**
     * @return array{users:int,products:int,orders:int,complaints:int,revenue:string}
     */
    public function statistics(): array;
}
