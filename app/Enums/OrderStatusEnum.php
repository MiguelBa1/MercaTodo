<?php

namespace App\Enums;

enum OrderStatusEnum: string
{
    case PENDING = 'Pending';
    case PAID = 'Paid';
    case SHIPPED = 'Shipped';
    case COMPLETED = 'Completed';
}
