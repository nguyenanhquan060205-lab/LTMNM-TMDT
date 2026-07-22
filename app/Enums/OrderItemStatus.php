<?php

namespace App\Enums;

enum OrderItemStatus: string
{
    case Pending = 'pending';
    case Confirmed = 'confirmed';
    case Cancelled = 'cancelled';
}
