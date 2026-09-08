<?php

namespace App\Services\Reports;

use App\Enums\Requests\MaterialRequestStatus;
use App\Enums\Requests\MoneyRequestStatus;
use App\Models\Catalogs\Company;
use App\Models\Incomes\Income;
use App\Models\MaterialRequests\MaterialRequest;
use App\Models\MoneyRequests\MoneyRequest;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * Calcula el balance de gastos e ingresos por empresa dentro de un rango de fechas.
 */
class CompanyBalanceReport
{
    /**
     * Obtiene el listado de empresas con su gasto, ingreso y balance total.
     *
     * @return Collection<int, array{company: string, expense: float, income: float, balance: float}>
     */
    public function get(Carbon $from, Carbon $to): Collection
    {
        $moneyRequestExpense = MoneyRequest::query()
            ->selectRaw('COALESCE(SUM(money_requests.amount), 0)')
            ->join('cost_centers', 'cost_centers.id', '=', 'money_requests.cost_center_id')
            ->whereColumn('cost_centers.company_id', 'companies.id')
            ->where('money_requests.status', MoneyRequestStatus::Paid)
            ->whereBetween('money_requests.created_at', [$from, $to]);

        $materialRequestExpense = MaterialRequest::query()
            ->selectRaw('COALESCE(SUM(material_requests.total_spent), 0)')
            ->join('cost_centers', 'cost_centers.id', '=', 'material_requests.cost_center_id')
            ->whereColumn('cost_centers.company_id', 'companies.id')
            ->where('material_requests.status', '!=', MaterialRequestStatus::Cancelled)
            ->whereBetween('material_requests.created_at', [$from, $to]);

        $income = Income::query()
            ->selectRaw('COALESCE(SUM(incomes.amount), 0)')
            ->whereColumn('incomes.company_id', 'companies.id')
            ->whereBetween('incomes.income_date', [$from, $to]);

        return Company::query()
            ->select(['companies.id', 'companies.name'])
            ->selectSub($moneyRequestExpense, 'money_requests_expense')
            ->selectSub($materialRequestExpense, 'material_requests_expense')
            ->selectSub($income, 'income')
            ->orderBy('companies.name')
            ->get()
            ->map(function (Company $company): array {
                $expense = (float) $company->getAttribute('money_requests_expense')
                    + (float) $company->getAttribute('material_requests_expense');
                $income = (float) $company->getAttribute('income');

                return [
                    'company' => $company->name,
                    'expense' => $expense,
                    'income'  => $income,
                    'balance' => $income - $expense,
                ];
            })->sortByDesc('income');
    }
}
