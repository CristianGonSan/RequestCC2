<?php

namespace App\Exports\Excel;

use Illuminate\Contracts\Database\Query\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class QueryExport implements FromQuery, WithMapping, WithHeadings
{
    protected Builder $query;
    protected array $headings;
    protected array $columnFormatter;



    public function __construct(Builder $query, array $columnFormatter, array $onlyColumns = [])
    {
        $this->query = $query;

        $this->columnFormatter = empty($onlyColumns) ?
            $columnFormatter :
            array_intersect_key($columnFormatter, array_flip($onlyColumns));

        $this->headings = array_column($this->columnFormatter, 'header');
    }

    public function query(): Builder
    {
        return $this->query;
    }

    public function headings(): array
    {
        return $this->headings;
    }

    public function map($item): array
    {
        $row = [];
        foreach ($this->columnFormatter as $column => $map) {
            $format = $map['format'] ?? null;
            $row[$column] = $format ?
                $format($item) : ($item->$column ?? null);
        }
        return $row;
    }
}
