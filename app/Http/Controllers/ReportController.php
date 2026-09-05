<?php

namespace App\Http\Controllers;

use App\Enums\Requests\MoneyRequestStatus;
use App\Models\MoneyRequests\MoneyRequest;
use App\Models\Catalogs\Type;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request): Application|Factory|View
    {
        if ($request->has('end_date')) {
            $endDate = $request->date('end_date');
        } else {
            $endDate = now();
        }

        if ($request->has('start_date')) {
            $startDate = $request->date('start_date');
        } else {
            $startDate = now()->subDays(30);
        }

        $statusOptions = MoneyRequestStatus::options();
        $typesOptions  = Type::options();

        $table = (new MoneyRequest)->getTable();

        $query = MoneyRequest::query()
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate);

        $moneyRequestTotal = $query->count();

        $paid = $query->where('status', MoneyRequestStatus::Paid);

        $moneyRequestsPaid = $paid->count();
        $moneyRequestsTotalAmountPaid   = $paid->sum('amount');

        // --- Reporte por tipo (para gráficos, sí se transforma a array) ---
        $moneyRequestsByType = MoneyRequest::select([
            "{$table}.type_id",
            'types.name as type_name',
            DB::raw('COUNT(*) as type_count'),
            DB::raw("SUM({$table}.amount) as amount_count"),
        ])
            ->join('types', 'types.id', '=', "{$table}.type_id")
            ->whereDate("{$table}.created_at", '>=', $startDate)
            ->whereDate("{$table}.created_at", '<=', $endDate)
            ->where("{$table}.status", MoneyRequestStatus::Paid)
            ->groupBy("{$table}.type_id", 'types.name')
            ->orderBy('types.name')
            ->get();

        $moneyRequestsByType = [
            'labels'       => $moneyRequestsByType->pluck('type_name'),
            'type_count'   => $moneyRequestsByType->pluck('type_count'),
            'amount_count' => $moneyRequestsByType->pluck('amount_count'),
        ];

        // --- Reporte por centro de costo (para TABLA, se deja como colección de filas) ---
        $moneyRequestsByCostCenter = MoneyRequest::select([
            "{$table}.cost_center_id",
            'cost_centers.name as cost_center_name',
            DB::raw('COUNT(*) as cost_center_count'),
            DB::raw("SUM({$table}.amount) as amount_count"),
        ])
            ->join('cost_centers', 'cost_centers.id', '=', "{$table}.cost_center_id")
            ->whereDate("{$table}.created_at", '>=', $startDate)
            ->whereDate("{$table}.created_at", '<=', $endDate)
            ->where("{$table}.status", MoneyRequestStatus::Paid)
            ->groupBy("{$table}.cost_center_id", 'cost_centers.name')
            ->orderBy('cost_centers.name')
            ->get();
        // Nota: se deja tal cual (colección), NO se convierte a array de labels/counts.

        // --- Reporte por estado (para gráficos, se transforma a array) ---
        $moneyRequestsByStatus = MoneyRequest::select([
            'status',
            DB::raw('COUNT(*) as status_count'),
            DB::raw('SUM(amount) as amount_count'),
        ])
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->groupBy('status')->orderBy('status')
            ->get();

        $moneyRequestsByStatus = [
            'labels' => $moneyRequestsByStatus->pluck('status')->map(function ($item) use ($statusOptions) {
                return $statusOptions[$item->value];
            }),
            'status_count' => $moneyRequestsByStatus->pluck('status_count')->toArray(),
            'amount_count' => $moneyRequestsByStatus->pluck('amount_count')->toArray(),
        ];

        return view('reports.index', compact([
            'moneyRequestTotal',
            'moneyRequestsPaid',
            'moneyRequestsTotalAmountPaid',
            'moneyRequestsByType',
            'moneyRequestsByCostCenter',
            'moneyRequestsByStatus',
            'startDate',
            'endDate',
        ]));
    }
}
