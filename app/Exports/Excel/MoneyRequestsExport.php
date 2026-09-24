<?php

namespace App\Exports\Excel;

use App\Models\MoneyRequests\MoneyRequest;

class MoneyRequestsExport extends QueryExport
{
    public static function columnFormatters(): array
    {
        return [
            'id' => [
                'header' => 'id',
            ],
            'paid_at' => [
                'header' => 'Pagado el',
                'format' => fn (MoneyRequest $item) => $item->paid_at?->format('Y-m-d h:i:s a') ?? null,
            ],
            'created_at' => [
                'header' => 'Creado el',
                'format' => fn (MoneyRequest $item) => $item->created_at->format('Y-m-d h:i:s a'),
            ],
            'updated_at' => [
                'header' => 'Actualizado el',
                'format' => fn (MoneyRequest $item) => $item->updated_at->format('Y-m-d h:i:s a'),
            ],
            'user' => [
                'header' => 'Solicita',
                'format' => fn (MoneyRequest $item) => $item->user->name,
            ],
            'concept' => [
                'header' => 'Concepto',
            ],
            'cost_center' => [
                'header' => 'Centro de Costos',
                'format' => fn (MoneyRequest $item) => $item->costCenter->name,
            ],
            'payee' => [
                'header' => 'Titular',
            ],
            'amount' => [
                'header' => 'Monto',
            ],
            'type' => [
                'header' => 'Tipo de Movimiento',
                'format' => fn (MoneyRequest $item) => $item->type->name,
            ],
            'payment_method' => [
                'header' => 'Método de Pago',
                'format' => fn (MoneyRequest $item) => $item->payment_method,
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
            'status' => [
                'header' => 'Estatus',
                'format' => fn (MoneyRequest $item) => $item->status_label,
            ],
        ];
    }
}
