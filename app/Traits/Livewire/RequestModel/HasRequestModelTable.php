<?php

namespace App\Traits\Livewire\RequestModel;

use App\Traits\SweetAlert2\Livewire\Toast;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

trait HasRequestModelTable
{
    use Toast, WithoutUrlPagination, WithPagination;

    public function search(): void
    {
        $this->resetPage();
    }

    public function clearSearch(): void
    {
        $this->searchTerm = '';
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

    public function updatedSortColumn(): void
    {
        $this->resetPage();
    }

    public function updatedSortDirection(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->resetPage();
        $this->reset([
            'searchTerm',
            'perPage',
            'page',
            'sortColumn',
            'sortDirection',
            'filters',
        ]);
    }
}
