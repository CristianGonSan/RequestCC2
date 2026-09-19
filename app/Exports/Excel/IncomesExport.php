<?php

namespace App\Exports\Excel;

use App\Models\Incomes\Income;

class IncomesExport extends QueryExport
{
    public static function columnFormatters(): array
    {
        return [
            'id' => [
                'header' => 'id',
            ],
            'income_date' => [
                'header' => 'Fecha de Ingreso',
                'format' => fn (Income $item): string => $item->income_date->format('Y-m-d'),
            ],
            'user' => [
                'header' => 'Solicita',
                'format' => fn (Income $item): string => $item->user->name,
            ],
            'concept' => [
                'header' => 'Concepto',
            ],
            'cost_center' => [
                'header' => 'Centro de Costos',
                'format' => fn (Income $item): string => $item->costCenter->name,
            ],
            'payee' => [
                'header' => 'Titular',
            ],
            'amount' => [
                'header' => 'Monto',
            ],
            'type' => [
                'header' => 'Tipo de Movimiento',
                'format' => fn (Income $item): string => $item->type->name,
            ],
            'payment_method' => [
                'header' => 'Método de Pago',
                'format' => fn (Income $item): string => $item->payment_method,
            ],
            'bank' => [
                'header' => 'Banco',
            ],
            'card' => [
                'header' => 'Tarjeta/CLABE',
            ],
            'account' => [
                'header' => 'Cuenta',
            ],
            'branch' => [
                'header' => 'Sucursal',
            ],
            'reference' => [
                'header' => 'Referencia',
            ],
            'covenant' => [
                'header' => 'Convenio',
            ],
        ];
    }
}
