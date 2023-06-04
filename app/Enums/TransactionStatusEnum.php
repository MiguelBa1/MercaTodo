<?php

namespace App\Enums;

enum TransactionStatusEnum: string
{
    case PENDING = 'Pending';
    case APPROVED = 'Approved';
    case APPROVED_PARTIAL = 'Approved Partial';
    case PARTIAL_EXPIRED = 'Partial Expired';
    case FAILED = 'Failed';
}
