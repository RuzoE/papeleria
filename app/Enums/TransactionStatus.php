<?php

namespace App\Enums;

enum TransactionStatus: string
{
    case Completed = 'completed';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::Completed => 'Completada',
            self::Cancelled => 'Cancelada',
        };
    }
}
