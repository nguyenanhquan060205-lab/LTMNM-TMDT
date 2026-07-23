<?php

declare(strict_types=1);

namespace App\Contracts\Services;

use App\Enums\ComplaintStatus;
use App\Models\Complaint;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Support\Collection;

interface ComplaintServiceContract
{
    /**
     * @param  array{description:string}  $attributes
     */
    public function createForOrderItem(User $user, OrderItem $orderItem, array $attributes): Complaint;

    /**
     * @return Collection<int, Complaint>
     */
    public function listForUser(User $user): Collection;

    public function resolve(User $admin, Complaint $complaint, ComplaintStatus $status, ?string $response = null): Complaint;
}
