<?php

namespace App\Enums;

enum TransactionStatusEnum: string
{
    case APPROVED = 'APPROVED';
    case PENDING = 'PENDING';
    case REJECTED = 'REJECTED';
    case APPROVED_PARTIAL = 'APPROVED_PARTIAL';
    case PARTIAL_EXPIRED = 'PARTIAL_EXPIRED';
    case FAILED = 'FAILED';
}
