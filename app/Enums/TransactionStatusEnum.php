<?php

namespace App\Enums;

enum TransactionStatusEnum: string
{
    public const PENDING = 'Pending';
    public const PAID = 'Paid';
    public const SHIPPED = 'Shipped';
    public const COMPLETED = 'Completed';

    public static function getValues(): array
    {
        return [
            self::PENDING,
            self::PAID,
            self::SHIPPED,
            self::COMPLETED,
        ];
    }
}
