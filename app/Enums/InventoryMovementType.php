<?php

namespace App\Enums;

enum InventoryMovementType: string
{
    case Entry = 'entry';
    case Exit = 'exit';
    case Adjustment = 'adjustment';
    case Return = 'return';

    public function label(): string
    {
        return match ($this) {
            self::Entry => 'Entrada',
            self::Exit => 'Salida',
            self::Adjustment => 'Ajuste',
            self::Return => 'Devolución',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Entry => 'green',
            self::Exit => 'red',
            self::Adjustment => 'yellow',
            self::Return => 'blue',
        };
    }
}
