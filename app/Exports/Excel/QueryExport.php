<?php

namespace App\Exports\Excel;

use Illuminate\Contracts\Database\Query\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

abstract class QueryExport implements FromQuery, WithHeadings, WithMapping
{
    protected Builder $query;

    protected array $columnFormatters;

    public function __construct(Builder $query, array $onlyColumns = [])
    {
        $this->query = $query;

        $columnFormatters = static::columnFormatters();

        if (empty($onlyColumns)) {
            $this->columnFormatters = $columnFormatters;
            return;
        }

        $this->columnFormatters = [];
        foreach ($onlyColumns as $column) {
            if (\array_key_exists($column, $columnFormatters)) {
                $this->columnFormatters[$column] = $columnFormatters[$column];
            }
        }
    }

    public static function options(): array
    {
        return array_map(fn ($column) => $column['header'], static::columnFormatters());
    }

    abstract public static function columnFormatters();

    public function query(): Builder
    {
        return $this->query;
    }

    public function headings(): array
    {
        return array_column($this->columnFormatters, 'header');
    }

    public function map($item): array
    {
        $row = [];
        foreach ($this->columnFormatters as $column => $map) {
            $format       = $map['format'] ?? null;
            $row[$column] = $format ?
                $format($item) : ($item->$column ?? null);
        }
        return $row;
    }
}
