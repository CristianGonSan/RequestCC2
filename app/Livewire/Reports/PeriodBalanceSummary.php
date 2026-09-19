<?php

namespace App\Livewire\Reports;

use App\Exports\Excel\PeriodBalanceExport;
use App\Exports\PDF\PeriodBalanceReport;
use App\Services\Reports\CostCenterReportService;
use App\Traits\Livewire\Tables\HasLivewireTableBehavior;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Session;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PeriodBalanceSummary extends Component
{
    use HasLivewireTableBehavior;

    #[Session]
    public string $dateFrom;

    #[Session]
    public string $dateTo;

    #[Session]
    public int $page = 1;

    #[Session]
    public string $sortColumn = 'balance';

    #[Session]
    public string $sortDirection = 'desc';

    public float $totalExpense = 0;

    public float $totalIncome = 0;

    public float $balance = 0;

    public ?float $margin = null;

    protected array $theadConfig = [
        [
            'column' => 'name',
            'label'  => 'Nombre',
        ],
        [
            'column' => 'total_income',
            'label'  => 'Ingresos',
        ],
        [
            'column' => 'total_expense',
            'label'  => 'Gastos',
        ],
        [
            'column' => 'balance',
            'label'  => 'Balance',
        ],
    ];

    public function mount(): void
    {
        $this->dateFrom ??= Carbon::now()->startOfMonth()->toDateString();
        $this->dateTo ??= Carbon::now()->endOfMonth()->toDateString();

        $this->refreshReportData();
    }

    public function render(): View
    {
        $balance = $this->getCostCentersBalanceQuery()
            ->orderBy($this->sortColumn, $this->sortDirection)
            ->paginate();

        return view('livewire.reports.period-balance-summary', [
            'balanceCostCenters' => $balance,
        ]);
    }

    public function updatePeriod(): void
    {
        $this->refreshReportData();
    }

    public function exportExcel(): ?BinaryFileResponse
    {
        $query = $this->getCostCentersBalanceQuery()
            ->orderBy($this->sortColumn, $this->sortDirection);

        return Excel::download(new PeriodBalanceExport($query), 'Balance.xlsx');
    }

    public function exportPdf(): StreamedResponse
    {
        $pdfReport = new PeriodBalanceReport(
            $this->getRangeStart(),
            $this->getRangeEnd(),
            $this->sortColumn,
            $this->sortDirection
        );

        return $pdfReport->streamDownloadPdf();
    }

    private function refreshReportData(): void
    {
        $this->calculate();
        $this->dispatchExpenseChart();
        $this->dispatchBalanceScatterPlot();
    }

    private function calculate(): void
    {
        $service = $this->getReportService();

        $this->totalExpense = $service->getTotalExpense();
        $this->totalIncome  = $service->getTotalIncome();
        $this->balance      = $this->totalIncome - $this->totalExpense;

        $this->margin = $this->totalIncome > 0
            ? ($this->balance / $this->totalIncome) * 100
            : 0;
    }

    private function dispatchExpenseChart(): void
    {
        $this->dispatch(
            'income-expense-chart-updated',
            incomes: $this->totalIncome,
            expenses: $this->totalExpense,
        );
    }

    private function dispatchBalanceScatterPlot(): void
    {
        $this->dispatch(
            'balance-scatter-plot-updated',
            dataset: $this->getBalanceScatterPlotData()
        );
    }

    private function getReportService(): CostCenterReportService
    {
        return new CostCenterReportService(
            $this->getRangeStart(),
            $this->getRangeEnd()
        );
    }

    private function getCostCentersBalanceQuery(): Builder
    {
        return $this->getReportService()
            ->getBalanceQuery();
    }

    private function getBalanceScatterPlotData(): array
    {
        return $this->getCostCentersBalanceQuery()
            ->get()
            ->map(fn ($costCenter) => [
                'costCenter' => $costCenter->name,
                'income'     => $costCenter->total_income,
                'expense'    => $costCenter->total_expense,
                'balance'    => $costCenter->balance,
            ])
            ->toArray();
    }

    private function getRangeStart(): Carbon
    {
        return Carbon::parse($this->dateFrom)->startOfDay();
    }

    private function getRangeEnd(): Carbon
    {
        return Carbon::parse($this->dateTo)->endOfDay();
    }
}
