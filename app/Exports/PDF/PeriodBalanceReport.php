<?php

namespace App\Exports\PDF;

use App\Services\Reports\CostCenterReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\DomPDF\PDF as BarryvdbPdf;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PeriodBalanceReport
{
    public function __construct(
        public readonly Carbon $from,
        public readonly Carbon $to,
        public readonly string $orderBy = 'balance',
        public readonly string $direction = 'desc'
    ) {}

    public function streamDownloadPdf(): StreamedResponse
    {
        $pdf  = $this->makePdf();
        $name = 'balance-periodo-'.$this->from->format('Y-m-d').'-al-'.$this->to->format('Y-m-d').'.pdf';

        return response()->streamDownload(
            fn () => print ($pdf->output()),
            $name
        );
    }

    public function makePdf(): BarryvdbPdf
    {
        $service = new CostCenterReportService($this->from, $this->to);

        $totalExpense = $service->getTotalExpense();
        $totalIncome  = $service->getTotalIncome();

        $balance = $totalIncome - $totalExpense;
        $margin  = $totalIncome > 0
            ? ($balance / $totalIncome) * 100
            : 0;

        $logoPath   = public_path('img/logos/codias.png');
        $logoBase64 = 'data:image/png;base64,'.base64_encode(file_get_contents($logoPath));

        $data = [
            'from'              => $this->from,
            'to'                => $this->to,
            'date'              => date('d/m/Y'),
            'costCenters'       => $service->getBalanceQuery()->orderBy($this->orderBy, $this->direction)->get(),
            'totalIncome'       => $totalIncome,
            'totalExpense'      => $totalExpense,
            'balance'           => $balance,
            'margin'            => $margin,
            'moneyRequestCount' => $service->getMoneyRequestCount(),
            'incomeCount'       => $service->getIncomeCount(),
            'logoBase64'        => $logoBase64,
        ];

        $pdf = Pdf::loadView('pdf.period-balance', $data)
            ->setPaper('letter', 'portrait');

        return $pdf;
    }
}
