<?php

namespace App\View\Components\Reports;

use App\Services\Reports\CompanyBalanceReport;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Carbon;
use Illuminate\View\Component;

/**
 * Muestra una tabla de balance (gastos vs ingresos) por empresa.
 */
class CompanyBalanceTable extends Component
{
    public Collection $balances;

    public Carbon $from;

    public Carbon $to;

    public function __construct(?string $from = null, ?string $to = null)
    {
        $this->from = $from !== null ? Carbon::parse($from) : now()->subDays(30);
        $this->to   = $to !== null ? Carbon::parse($to) : now();

        $this->balances = app(CompanyBalanceReport::class)->get($this->from, $this->to);
    }

    public function render(): View
    {
        return view('components.reports.company-balance-table');
    }
}
