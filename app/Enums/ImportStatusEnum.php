<?php

namespace App\Enums;

enum ImportStatusEnum: string
{
    case PENDING = 'PENDING';
    case READY = 'READY';
    case FAILED = 'FAILED';

    case HAS_ERRORS = 'HAS_ERRORS';
}
