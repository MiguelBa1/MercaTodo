<?php

namespace App\Enums;

enum DocumentTypeEnum: string
{
    case PASSPORT = 'Passport';
    case NATIONAL_ID_CARD = 'National ID Card';
    case DRIVER_LICENSE = 'Driver License';
    case SOCIAL_SECURITY_NUMBER = 'Social Security Number';
    case TAX_ID_NUMBER = 'Tax ID Number';
    case OTHER = 'Other';
}
