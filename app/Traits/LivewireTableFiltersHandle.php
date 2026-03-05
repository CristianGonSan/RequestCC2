<?php

namespace App\Traits;

use Illuminate\Support\Facades\Session;

trait LivewireTableFiltersHandle
{
    public string $filtersName;
    public array $filters = [];

    public array $defaultFilters = [];

    public function loadFilters(string $filtersName, array  $defaultFilters = [])
    {
        $this->filtersName = $filtersName;
        $this->defaultFilters = $defaultFilters;
        $this->filters = Session::get($this->filtersName, $this->defaultFilters);
        $this->setPage($this->filters['page'] ?? 1);
    }

    public function search()
    {
        $this->resetPage();
    }

    public function updatedPage($page)
    {
        $this->filters['page'] = $page;
        $this->saveFilters();
    }

    public function resetFilters()
    {
        $this->filters = array_merge([], $this->defaultFilters);
        $this->resetPage();
    }

    private function saveFilters()
    {
        Session::put($this->filtersName, $this->filters);
    }
}
