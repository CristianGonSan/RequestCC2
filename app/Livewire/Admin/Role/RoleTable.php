<?php

namespace App\Livewire\Admin\Role;

use Livewire\Component;
use Livewire\WithPagination;

use Spatie\Permission\Models\Role;

class RoleTable extends Component
{
    use WithPagination;

    public string $search = ''; // Término de búsqueda ingresado por el usuario
    public int $perPage = 12; // Número de resultados por página

    public string $orderBy = 'id'; // Campo por el cual se ordenarán los reportes
    public string $orderDirection = 'desc'; // Dirección del ordenamiento

    protected array $queryString = ['search', 'perPage', 'orderBy', 'orderDirection']; // Mantener el estado en la URL

    public function render()
    {
        $roles = $this->getQuery()->paginate($this->perPage);

        return view('livewire.admin.role.role-table', [
            'roles' => $roles
        ]);
    }

    public function getQuery()
    {
        $query = Role::query();

        $query->orderBy($this->orderBy, $this->orderDirection);

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        return $query;
    }

    /**
     * Reinicia la paginación al cambiar el término de búsqueda.
     *
     * @return void
     */
    public function refreshPage(): void
    {
        $this->resetPage(); // Reinicia la página actual a la primera
    }
}
