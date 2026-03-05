<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use App\Exports\BaseExport;
use App\Models\User;

class ExportUsers extends BaseExport
{
    public function __construct(Collection $results, array $onlyColumns = [])
    {
        parent::__construct($results, [
            'id' => [
                'header' => 'ID',
            ],
            'name' => [
                'header' => 'Nombre',
            ],
            'email' => [
                'header' => 'Email',
            ],
            'created_at' => [
                'header' => 'Creado el',
                'format' => function (User $item) {
                    return $item->created_at->format('d-m-Y');
                }
            ],
            'enabled' => [
                'header' => 'Está',
                'format' => function (User $item) {
                    return $item->enabled ? 'Habilitado' : 'Deshabilitado';
                }
            ]
        ], $onlyColumns);
    }
}
