<?php

namespace App\Enums;

enum SaleStatus: string
{
    case Completed = 'completed';
    case Cancelled = 'cancelled';
    case Pending = 'pending';

    public function label(): string
    {
        return match ($this) {
            self::Completed => 'Completada',
            self::Cancelled => 'Cancelada',
            self::Pending => 'Pendiente',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Completed => 'green',
            self::Cancelled => 'red',
            self::Pending => 'yellow',
        };
    }
}
