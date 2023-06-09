<?php

namespace App\Enums;

enum OrderStatusEnum: string
{
    case PENDING = 'PENDING';
    case COMPLETED = 'COMPLETED';
    case REJECTED = 'REJECTED';
}
