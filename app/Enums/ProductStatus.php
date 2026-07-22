<?php

namespace App\Enums;

enum ProductStatus: string
{
    case Approved = 'approved';
    case Sold = 'sold';
    case Hidden = 'hidden';
}
