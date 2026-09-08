<?php

namespace App\Traits\Livewire\Tables;

use App\Traits\Livewire\WithTableSorting;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

trait HasLivewireTableBehavior
{
    use WithoutUrlPagination, WithPagination, WithTableSorting;

    public function search(): void
    {
        $this->resetPage();
    }

    public function clearSearch(): void
    {
        $this->searchTerm = '';
        $this->resetPage();
    }

    public function afterSortChanged(): void
    {
        $this->resetPage();
    }

    public function updatedPage($page): void
    {
        $this->page = $page;
    }

    public function updatedFilters(mixed $value, string $key): void
    {
        if (! filled($value)) {
            $this->filters[$key] = null;
        }

        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset([
            'searchTerm',
            'perPage',
            'page',
            'sortColumn',
            'sortDirection',
            'filters',
        ]);
        $this->resetPage();
    }
}
