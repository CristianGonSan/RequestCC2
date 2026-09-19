<?php

namespace App\Exports\Excel;

use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithCustomChunkSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PeriodBalanceExport implements FromQuery, WithColumnFormatting, WithColumnWidths, WithCustomChunkSize, WithHeadings, WithMapping, WithStyles
{
    public function __construct(
        private readonly Builder $query
    ) {}

    public function query(): Builder
    {
        return $this->query;
    }

    public function headings(): array
    {
        return [
            'Centro de Costos',
            'Ingresos',
            'Gastos',
            'Balance',
        ];
    }

    /**
     * @param  mixed  $item
     */
    public function map($item): array
    {
        return [
            $item->name,
            (float) $item->total_income,
            (float) $item->total_expense,
            (float) $item->balance,
        ];
    }

    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_NUMBER_00,
            'C' => NumberFormat::FORMAT_NUMBER_00,
            'D' => '[Color 10]0.00;[Red]-0.00;0.00',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 16,
            'B' => 16,
            'C' => 16,
            'D' => 16,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
