<?php

namespace App\Enums;

enum ExportStatusEnum: string
{
    case PENDING = 'PENDING';
    case READY = 'READY';
    case FAILED = 'FAILED';
}
