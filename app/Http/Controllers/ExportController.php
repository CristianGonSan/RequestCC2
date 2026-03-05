<?php

namespace App\Http\Controllers;

use App\Exports\Excel\RequestsExport;
use Illuminate\Contracts\Database\Query\Builder;
use App\Models\RequestModel;
use App\Models\Type;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExportController extends Controller
{

    private array $columnsOptions = [
        'id' => 'id',
        'created_at' => 'Creado el',
        'updated_at' => 'Actualizado el',
        'user' => 'Solicita',
        'concept' => 'Concepto',
        'cost_center' => 'Centro de Costos',
        'payee' => 'Titular',
        'amount' => 'Importe',
        'type' => 'Tipo de Movimiento',
        'is_transfer' => 'Método de Pago',
        'bank' => 'Banco',
        'card' => 'Clave/Tarjeta',
        'account' => 'Cuenta',
        'branch' => 'Sucursal',
        'reference' => 'Referencia',
        'covenant' => 'Convenio',
        'status' => 'Estatus'
    ];

    public function index(): View
    {
        $typeOptions = Type::options();
        $statusOptions = RequestModel::STATUSES_TEXT;

        $columnsOptions = $this->columnsOptions;

        return view('requests.export', compact([
            'typeOptions',
            'statusOptions',
            'columnsOptions'
        ]));
    }

    public function export(Request $request): BinaryFileResponse
    {
        $columns = $request->input('columns', []);
        $query = $this->getQuery($request);

        $export = new RequestsExport($query, $columns);

        return Excel::download($export, 'Solicitudes.xlsx');
    }

    public function getQuery(Request $request): Builder|RequestModel
    {
        $functions = [
            'created_at_start' => function ($query, $value) {
                $query->whereDate('created_at', '>=', $value);
            },
            'created_at_end' => function ($query, $value) {
                $query->whereDate('created_at', '<=', $value);
            },
            'updated_at_start' => function ($query, $value) {
                $query->whereDate('updated_at', '>=', $value);
            },
            'updated_at_end' => function ($query, $value) {
                $query->whereDate('updated_at', '<=', $value);
            },
            'min_amount' => function ($query, $value) {
                $value = (float) str_replace(',', '', $value);
                $query->where('amount', '>=', $value);
            },
            'max_amount' => function ($query, $value) {
                $value = (float) str_replace(',', '', $value);
                $query->where('amount', '<=', $value);
            },
            'cost_centers' => function ($query, $value) {
                $query->whereIn('cost_center', $value);
            },
            'concept' => function ($query, $value) {
                $concepts = explode("|", $value);
                $query->where(function ($q) use ($concepts) {
                    foreach ($concepts as $concept) {
                        $q->orWhere('concept', 'like', "%{$concept}%");
                    }
                });
            },
            'users' => function ($query, $value) {
                $query->whereIn('user_id', $value);
            },
            'payee' => function ($query, $value) {
                $query->where('payee', 'like', "%$value%");
            },
            'bank' => function ($query, $value) {
                $query->where('bank', 'like', "%$value%");
            },
            'is_transfer' => function ($query, $value) {
                $query->where('is_transfer', $value == 'transfer');
            },
            'type' => function ($query, $value) {
                $query->whereIn('type', $value);
            },
            'status' => function ($query, $value) {
                $query->whereIn('status', $value);
            }
        ];

        $query = RequestModel::query()->with(['user:id,name', 'typeModel:key,name']);

        foreach ($functions as $key => $function) {
            if ($value = $request->input($key)) {
                $function($query, $value);
            }
        }

        $query->orderBy(
            $request->input('orderBy', 'created_at'),
            $request->input('orderDirection', 'desc')
        );

        return $query;
    }
}
