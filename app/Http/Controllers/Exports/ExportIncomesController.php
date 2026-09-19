<?php

namespace App\Http\Controllers\Exports;

use App\Exports\Excel\IncomesExport;
use App\Http\Controllers\Controller;
use App\Models\Catalogs\Type;
use App\Models\Incomes\Income;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExportIncomesController extends Controller
{
    public function index(): View
    {
        return view('exports.incomes', [
            'typeOptions'   => Type::options(),
            'columnOptions' => IncomesExport::options(),
        ]);
    }

    public function export(Request $request): BinaryFileResponse
    {
        $onlyColumns = $request->input('columns', []);
        $query       = $this->getQuery($request);

        $export = new IncomesExport($query, $onlyColumns);

        return Excel::download($export, 'Ingresos.xlsx');
    }

    public function getQuery(Request $request): Builder|Income
    {
        $query = Income::with(['user', 'type', 'costCenter']);

        $query
            ->when($request->filled('income_date_start'),
                fn (Builder $q) => $q->whereDate('income_date', '>=', $request->date('income_date_start')))
            ->when($request->filled('income_date_end'),
                fn (Builder $q) => $q->whereDate('income_date', '<=', $request->date('income_date_end')))
            ->when($request->filled('min_amount'),
                fn (Builder $q) => $q->where('amount', '>=', (float) str_replace(',', '', $request->string('min_amount'))))
            ->when($request->filled('max_amount'),
                fn (Builder $q) => $q->where('amount', '<=', (float) str_replace(',', '', $request->string('max_amount'))))
            ->when($request->filled('cost_centers'),
                fn (Builder $q) => $q->whereHas('costCenter',
                    fn (Builder $q) => $q->whereIn('name', $request->array('cost_centers'))))
            ->when($request->filled('concept'),
                fn (Builder $q) => $q->where(function (Builder $q) use ($request): void {
                    foreach (explode('|', $request->string('concept')) as $concept) {
                        $q->orWhere('concept', 'like', "%{$concept}%");
                    }
                }))
            ->when($request->filled('users'),
                fn (Builder $q) => $q->whereIn('user_id', $request->array('users')))
            ->when($request->filled('payee'),
                fn (Builder $q) => $q->where('payee', 'like', "%{$request->string('payee')}%"))
            ->when($request->filled('bank'),
                fn (Builder $q) => $q->where('bank', 'like', "%{$request->string('bank')}%"))
            ->when($request->filled('payment_method'),
                fn (Builder $q) => $q->where('is_transfer', $request->string('payment_method') == 'transfer'))
            ->when($request->filled('type'),
                fn (Builder $q) => $q->whereIn('type_id', $request->array('type')))
            ->orderBy(
                $request->string('orderBy', 'income_date'),
                $request->string('orderDirection', 'desc')
            );

        return $query;
    }
}
