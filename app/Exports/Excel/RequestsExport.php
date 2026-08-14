<?php

namespace App\Exports\Excel;

use Illuminate\Contracts\Database\Query\Builder;
use App\Exports\Excel\QueryExport;
use App\Models\RequestModel;

class RequestsExport extends QueryExport
{
    public function __construct(Builder $query, array $onlyColumns = [])
    {
        parent::__construct($query, [
            'id' => [
                'header' => 'id'
            ],
            'created_at' => [
                'header' => 'Creado el',
                'format' => fn(RequestModel $item) => $item->created_at->format('Y-m-d h:i:s a')
            ],
            'updated_at' => [
                'header' => 'Actualizado el',
                'format' => fn(RequestModel $item) => $item->updated_at->format('Y-m-d h:i:s a')
            ],
            'user' => [
                'header' => 'Solicita',
                'format' => fn(RequestModel $item) => $item->user->name
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
                'header' => 'Monto'
            ],
            'type' => [
                'header' => 'Tipo de Movimiento',
                'format' => fn(RequestModel $item) => $item->type->name
            ],
            'is_transfer' => [
                'header' => 'Método de Pago',
                'format' => fn(RequestModel $item) => $item->paymentMethod()
            ],
            'bank' => [
                'header' => 'Banco'
            ],
            'card' => [
                'header' => 'Tarjeta/CLABE'
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
                'format' => fn(RequestModel $item) => $item->status->label()
            ],
        ], $onlyColumns);
    }
}
