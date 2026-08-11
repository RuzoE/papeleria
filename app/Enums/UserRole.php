<?php

namespace App\Enums;

enum UserRole: string
{
    case Admin = 'admin';
    case Employee = 'employee';
    case Cashier = 'cashier';
    case Viewer = 'viewer';

    public function label(): string
    {
        return match ($this) {
            self::Admin => 'Administrador',
            self::Employee => 'Empleado',
            self::Cashier => 'Cajero',
            self::Viewer => 'Consulta',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Admin => 'purple',
            self::Employee => 'blue',
            self::Cashier => 'green',
            self::Viewer => 'gray',
        };
    }
}
