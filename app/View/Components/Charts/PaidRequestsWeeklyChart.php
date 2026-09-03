<?php

namespace App\View\Components\Charts;

use App\Enums\Requests\MoneyRequestStatus;
use App\Models\MoneyRequests\MoneyRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\Component;

/**
 * Componente autocontenido que muestra, en un grafico de barras (Chart.js 4),
 * el total de solicitudes pagadas (status 'S05') de los ultimos siete dias.
 * Si $onlyCurrentUser es true, filtra unicamente las solicitudes del usuario loggueado.
 */
class PaidRequestsWeeklyChart extends Component
{
    /**
     * Etiquetas de fecha para el eje X del grafico.
     *
     * @var array<int, string>
     */
    public array $labels;

    /**
     * Totales pagados por dia, alineados con $labels.
     *
     * @var array<int, float>
     */
    public array $totals;

    /**
     * Id unico del elemento canvas, para evitar colisiones si hay varias instancias.
     */
    public string $canvasId;

    public function __construct(
        public bool $onlyCurrentUser = false,
    ) {
        $this->canvasId = $this->onlyCurrentUser
            ? 'paidRequestsWeeklyChartUser'
            : 'paidRequestsWeeklyChartGlobal';

        [$this->labels, $this->totals] = $this->buildChartData();
    }

    /**
     * Construye las etiquetas y totales de los ultimos siete dias, usando cache de 60 minutos.
     *
     * @return array{0: array<int, string>, 1: array<int, float>}
     */
    protected function buildChartData(): array
    {
        $cacheKey = $this->onlyCurrentUser
            ? 'paid_requests_weekly_chart_data_user_'.Auth::id()
            : 'paid_requests_weekly_chart_data';

        return Cache::remember($cacheKey, now()->addMinutes(60), function (): array {
            $startDate = Carbon::now()->subDays(6)->startOfDay();
            $endDate = Carbon::now()->endOfDay();

            $query = MoneyRequest::query()
                ->where('status', MoneyRequestStatus::Paid)
                ->whereBetween('updated_at', [$startDate, $endDate]);

            if ($this->onlyCurrentUser) {
                $query->where('user_id', Auth::id());
            }

            // 'S05' corresponde al value del status pagado en MoneyRequestStatus.
            $paidTotalsByDate = $query
                ->selectRaw('DATE(updated_at) as paid_date, SUM(amount) as total_amount')
                ->groupBy('paid_date')
                ->pluck('total_amount', 'paid_date');

            $labels = [];
            $totals = [];

            for ($dayOffset = 0; $dayOffset < 7; $dayOffset++) {
                $date = Carbon::now()->subDays(6 - $dayOffset);
                $dateKey = $date->toDateString();

                $labels[] = $date->translatedFormat('d M');
                $totals[] = (float) ($paidTotalsByDate[$dateKey] ?? 0);
            }

            return [$labels, $totals];
        });
    }

    public function render(): View
    {
        return view('components.charts.paid-requests-weekly-chart');
    }
}
