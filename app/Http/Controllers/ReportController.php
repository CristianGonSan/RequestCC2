<?php

namespace App\Http\Controllers;

use App\Models\Configuration;
use App\Models\RequestModel;
use App\Models\Type;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

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

        $statusOptions = RequestModel::STATUSES_TEXT;
        $typesOptions = Type::options();

        $query = RequestModel::query()
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate);

        $requestTotal = $query->count();

        $paid = $query->where('status', RequestModel::STATUS_PAID);

        $requestsPaid = $paid->count();
        $totalAmountPaid = $paid->sum('amount');

        $requestsByType = RequestModel::select([
            'type',
            DB::raw('COUNT(*) as type_count'),
            DB::raw('SUM(amount) as amount_count')
        ])
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->where('status', RequestModel::STATUS_PAID)
            ->groupBy('type')->orderBy('type')
            ->get();

        $requestsByType = [
            'labels' => $requestsByType->pluck('type')->map(function ($item) use ($typesOptions) {
                return $typesOptions[$item] ?? 'Desconocido';
            }),
            'type_count' => $requestsByType->pluck('type_count'),
            'amount_count' => $requestsByType->pluck('amount_count'),
        ];

        $requestsByCostCenter = RequestModel::select([
            'cost_center',
            DB::raw('COUNT(*) as cost_center_count'),
            DB::raw('SUM(amount) as amount_count')
        ])
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->where('status', RequestModel::STATUS_PAID)
            ->groupBy('cost_center')->orderBy('cost_center')
            ->get();

        $requestsByStatus = RequestModel::select([
            'status',
            DB::raw('COUNT(*) as status_count'),
            DB::raw('SUM(amount) as amount_count')
        ])
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->groupBy('status')->orderBy('status')
            ->get();

        $requestsByStatus = [
            'labels' => $requestsByStatus->pluck('status')->map(function ($item) use ($statusOptions) {
                return $statusOptions[$item];
            }),
            'status_count' => $requestsByStatus->pluck('status_count')->toArray(),
            'amount_count' => $requestsByStatus->pluck('amount_count')->toArray(),
        ];

        return view('reports.index', compact([
            'requestTotal',
            'requestsPaid',
            'totalAmountPaid',
            'requestsByType',
            'requestsByCostCenter',
            'requestsByStatus',
            'startDate',
            'endDate'
        ]));
    }
}
