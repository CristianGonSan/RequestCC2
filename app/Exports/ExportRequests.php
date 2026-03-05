<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use App\Exports\BaseExport;
use App\Models\RequestModel;

class ExportRequests extends BaseExport
{
    public function __construct(Collection $results, array $onlyColumns = [])
    {
        parent::__construct($results, [
            'id' => [
                'header' => 'ID'
            ],
            'created_at' => [
                'header' => 'Fecha',
                'format' => function (RequestModel $item) {
                    return $item->created_at->format('Y-m-d h:i:s a');
                }
            ],
            'user' => [
                'header' => 'Solicita',
                'format' => function (RequestModel $item) {
                    return $item->user?->name ?? 'N/A';
                }
            ],
            'concept' => [
                'header' => 'Concepto'
            ],
            'cost_center' => [
                'header' => 'Centro de Costos'
            ],
            'payee' => [
                'header' => 'Titular'
            ],
            'amount' => [
                'header' => 'Importe'
            ],
            'type' => [
                'header' => 'Tipo de Movimiento',
                'format' => function (RequestModel $item) {
                    return $item->typeModel?->name ?? 'Desconocido';
                }
            ],
            'is_transfer' => [
                'header' => 'Método de Pago',
                'format' => function (RequestModel $item) {
                    return $item->is_transfer ? 'Transferencia' : 'Efectivo';
                }
            ],
            'bank' => [
                'header' => 'Banco'
            ],
            'card' => [
                'header' => 'Clave/Tarjeta'
            ],
            'account' => [
                'header' => 'Cuenta'
            ],
            'branch' => [
                'header' => 'Sucursal'
            ],
            'reference' => [
                'header' => 'Referencia'
            ],
            'covenant' => [
                'header' => 'Convenio'
            ],
            'status' => [
                'header' => 'Estatus',
                'format' => function (RequestModel $item) {
                    return $item->getStatusText();
                }
            ],
        ], $onlyColumns);
    }
}
