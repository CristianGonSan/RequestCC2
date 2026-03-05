<?php

namespace App\Livewire\Admin\Type;

use App\Models\Type;
use Livewire\Component;
use Livewire\WithPagination;

class TypeTable extends Component
{
    use WithPagination;

    public string $search = ''; // Término de búsqueda ingresado por el usuario
    public int $perPage = 12; // Número de resultados por página

    public string $orderBy = 'id'; // Campo por el cual se ordenarán los reportes
    public string $orderDirection = 'desc'; // Dirección del ordenamiento

    protected array $queryString = ['search', 'perPage', 'orderBy', 'orderDirection']; // Mantener el estado en la URL


    public function render()
    {
        $types = $this->getQuery()->paginate($this->perPage);

        return view('livewire.admin.type.type-table', [
            'types' => $types
        ]);
    }

    public function getQuery()
    {
        $query = Type::query();

        $query->orderBy($this->orderBy, $this->orderDirection);

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        return $query;
    }

    public function refreshPage(): void
    {
        $this->resetPage();
    }
}
