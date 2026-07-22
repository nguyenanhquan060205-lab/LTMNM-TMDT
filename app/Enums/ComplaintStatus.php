<?php

namespace App\Enums;

enum ComplaintStatus: string
{
    case Pending = 'pending';
    case Resolved = 'resolved';
}
