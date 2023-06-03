<?php

namespace App\Enums;

enum TransactionStatusEnum: string
{
    case PENDING = 'Pending';
    case PAID = 'Paid';
    case SHIPPED = 'Shipped';
    case COMPLETED = 'Completed';
}
