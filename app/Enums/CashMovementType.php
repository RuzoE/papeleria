<?php

namespace App\Enums;

enum CashMovementType: string
{
    case SaleIncome = 'sale_income';
    case ServiceIncome = 'service_income';
    case Expense = 'expense';
    case Withdrawal = 'withdrawal';
    case Adjustment = 'adjustment';

    public function label(): string
    {
        return match ($this) {
            self::SaleIncome => 'Ingreso Venta',
            self::ServiceIncome => 'Ingreso Servicio',
            self::Expense => 'Gasto',
            self::Withdrawal => 'Retiro',
            self::Adjustment => 'Ajuste',
        };
    }
}
