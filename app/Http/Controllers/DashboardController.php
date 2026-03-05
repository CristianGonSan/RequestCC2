<?php

namespace App\Http\Controllers;

use App\Models\RequestModel;
use Auth;
use Illuminate\Contracts\Support\Renderable;

class DashboardController extends Controller
{

    /**
     * Muestra el panel de control de la aplicación.
     *
     * @return Renderable
     */
    public function index(): Renderable
    {
        $user = Auth::user();

        $now = now();

        $currentMonthQuery = $user->requests()
            ->whereYear('created_at', $now->year)
            ->whereMonth('created_at', $now->month);

        $currentMonth = ucfirst($now->translatedFormat('F Y'));

        $now->subMonth();

        $previousMonthQuery = $user->requests()
            ->whereYear('created_at', $now->year)
            ->whereMonth('created_at', $now->month);

        $previousMonth = ucfirst($now->translatedFormat('F Y'));

        $months = [
            'current' => [
                'name' => $currentMonth,
                'total' => $total = $currentMonthQuery->count(),
                'paid' => $paid = $currentMonthQuery->where('status', RequestModel::STATUS_PAID)->count(),
                'percentage' => number_format($total > 0 ? (100 / $total) * $paid : 0, 2)
            ],
            'previous' => [
                'name' => $previousMonth,
                'total' => $total = $previousMonthQuery->count(),
                'paid' => $paid = $previousMonthQuery->where('status', RequestModel::STATUS_PAID)->count(),
                'percentage' => number_format($total > 0 ? (100 / $total) * $paid : 0, 2)
            ]
        ];

        return view('dashboard', compact('months'));
    }
}
