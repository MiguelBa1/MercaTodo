<?php

namespace App\Enums;

enum OrderStatusEnum: string
{
    case PENDING = 'Pending';
    case ACCEPTED = 'Accepted';
    case REJECTED = 'Rejected';
    case IN_PROGRESS = 'In Progress';
}
