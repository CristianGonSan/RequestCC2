<?php

namespace App\Services\Reports;

use App\Models\Catalogs\CostCenter;
use App\Models\Incomes\Income;
use App\Models\MoneyRequests\MoneyRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Carbon;

class CostCenterReportService
{
    /**
     * Inicializa el servicio con el rango de fechas fijo.
     */
    public function __construct(
        public readonly Carbon $from,
        public readonly Carbon $to
    ) {}

    /**
     * Devuelve el query builder para el balance de centros de costo dentro del periodo configurado.
     *
     * @return Builder<CostCenter>
     */
    public function getBalanceQuery(bool $onlyWithActivity = true): Builder
    {
        $incomeTotals = Income::query()
            ->selectRaw('cost_center_id, SUM(amount) as total_income')
            ->whereBetween('income_date', [$this->from, $this->to])
            ->groupBy('cost_center_id');

        $expenseTotals = MoneyRequest::query()
            ->selectRaw('cost_center_id, SUM(amount) as total_expense')
            ->whereBetween('paid_at', [$this->from, $this->to])
            ->whereNotNull('cost_center_id')
            ->groupBy('cost_center_id');

        $query = CostCenter::query();

        $query->leftJoinSub($incomeTotals, 'income_totals', function (JoinClause $join): void {
            $join->on('cost_centers.id', '=', 'income_totals.cost_center_id');
        })
            ->leftJoinSub($expenseTotals, 'expense_totals', function (JoinClause $join): void {
                $join->on('cost_centers.id', '=', 'expense_totals.cost_center_id');
            });

        if ($onlyWithActivity) {
            $query->where(function (Builder $q): void {
                $q->whereNotNull('income_totals.cost_center_id')
                    ->orWhereNotNull('expense_totals.cost_center_id');
            });
        }

        $query->select('cost_centers.*')
            ->selectRaw('COALESCE(income_totals.total_income, 0) as total_income')
            ->selectRaw('COALESCE(expense_totals.total_expense, 0) as total_expense')
            ->selectRaw('COALESCE(income_totals.total_income, 0) - COALESCE(expense_totals.total_expense, 0) as balance');

        return $query;
    }

    public function getTotalIncome(): float
    {
        return (float) Income::query()
            ->whereBetween('income_date', [$this->from, $this->to])
            ->sum('amount');
    }

    public function getTotalExpense(): float
    {
        return (float) MoneyRequest::query()
            ->whereBetween('paid_at', [$this->from, $this->to])
            ->sum('amount');
    }

    public function getIncomeCount(): int
    {
        return Income::query()
            ->whereBetween('income_date', [$this->from, $this->to])
            ->count();
    }

    public function getMoneyRequestCount(): int
    {
        return MoneyRequest::query()
            ->whereBetween('paid_at', [$this->from, $this->to])
            ->count();
    }
}
