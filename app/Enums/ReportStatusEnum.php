<?php

namespace App\Enums;

enum ReportStatusEnum: string
{
    case PENDING = 'pending';
    case PROCESSING = 'processing';
    case COMPLETED = 'completed';
    case FAILED = 'failed';
}
