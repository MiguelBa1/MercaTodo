<?php

namespace App\Enums;

use Illuminate\Validation\Rules\Enum;

class DocumentTypeEnum extends Enum
{
    public const PASSPORT = 'Passport';
    public const NATIONAL_ID_CARD = 'National ID Card';
    public const DRIVER_LICENSE = 'Driver License';
    public const SOCIAL_SECURITY_NUMBER = 'Social Security Number';
    public const TAX_ID_NUMBER = 'Tax ID Number';
    public const OTHER = 'Other';

    public static function getRandomValue(): string
    {
        $values = self::getValues();
        $randomKey = array_rand($values);

        return $values[$randomKey];
    }

    public static function getValues(): array
    {
        return [
            self::PASSPORT,
            self::NATIONAL_ID_CARD,
            self::DRIVER_LICENSE,
            self::SOCIAL_SECURITY_NUMBER,
            self::TAX_ID_NUMBER,
            self::OTHER,
        ];
    }
}
