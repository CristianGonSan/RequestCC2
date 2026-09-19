<?php

namespace App\Http\Controllers\Exports;

use App\Enums\Requests\MoneyRequestStatus;
use App\Exports\Excel\MoneyRequestsExport;
use App\Http\Controllers\Controller;
use App\Models\MoneyRequests\MoneyRequest;
use Illuminate\Contracts\Database\Query\Builder;
use App\Models\Catalogs\Type;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExportMoneyRequestsController extends Controller
{
    public function index(): View
    {
        return view('exports.money-requests', [
            'typeOptions'   => Type::options(),
            'statusOptions' => MoneyRequestStatus::options(),
            'columnOptions' => MoneyRequestsExport::options(),
        ]);
    }

    public function export(Request $request): BinaryFileResponse
    {
        $onlyColumns = $request->input('columns', []);
        $query       = $this->getQuery($request);

        $export = new MoneyRequestsExport($query, $onlyColumns);

        return Excel::download($export, 'Solicitudes.xlsx');
    }

    public function getQuery(Request $request): Builder|MoneyRequest
    {
        $query = MoneyRequest::with(['user', 'type', 'costCenter']);

        $query
            ->when($request->filled('created_at_start'),
                fn (Builder $q) => $q->whereDate('created_at', '>=', $request->date('created_at_start')))
            ->when($request->filled('created_at_end'),
                fn (Builder $q) => $q->whereDate('created_at', '<=', $request->date('created_at_end')))
            ->when($request->filled('updated_at_start'),
                fn (Builder $q) => $q->whereDate('updated_at', '>=', $request->date('updated_at_start')))
            ->when($request->filled('updated_at_end'),
                fn (Builder $q) => $q->whereDate('updated_at', '<=', $request->date('updated_at_end')))
            ->when($request->filled('min_amount'),
                fn (Builder $q) => $q->where('amount', '>=', (float) str_replace(',', '', $request->string('min_amount'))))
            ->when($request->filled('max_amount'),
                fn (Builder $q) => $q->where('amount', '<=', (float) str_replace(',', '', $request->string('max_amount'))))
            ->when($request->filled('cost_centers'),
                fn (Builder $q) => $q->whereIn('cost_center_name', $request->array('cost_centers')))
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
            ->when($request->filled('status'),
                fn (Builder $q) => $q->whereIn('status', $request->array('status')))
            ->orderBy(
                $request->string('orderBy', 'created_at'),
                $request->string('orderDirection', 'desc')
            );

        return $query;
    }
}
