<?php

namespace App\Enums;

enum ImportStatusEnum: string
{
    case PENDING = 'PENDING';
    case READY = 'READY';
    case FAILED = 'FAILED';

    case EMPTY_FILE_ERROR = 'EMPTY_FILE_ERROR';

    case HAS_ERRORS = 'HAS_ERRORS';
}
