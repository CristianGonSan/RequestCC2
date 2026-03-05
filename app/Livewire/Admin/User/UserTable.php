<?php

namespace App\Livewire\Admin\User;

use App\Exports\ExportUsers;
use App\Exports\GenericExport;
use Livewire\Component;
use Livewire\WithPagination;

use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;

class UserTable extends Component
{
    use WithPagination;

    public string $search = ''; // Término de búsqueda ingresado por el usuario
    public int $perPage = 12; // Número de resultados por página

    public string $orderBy = 'id'; // Campo por el cual se ordenarán los reportes
    public string $orderDirection = 'desc'; // Dirección del ordenamiento

    public $enabled = -1;

    protected array $queryString = ['search', 'perPage', 'orderBy', 'orderDirection']; // Mantener el estado en la URL

    public function render()
    {
        $users = $this->getQuery()->paginate($this->perPage);

        return view('livewire.admin.user.user-table', [
            'users' => $users
        ]);
    }

    public function updated($name, $value)
    {
        $this->resetPage();
    }

    public function getQuery()
    {
        $query = User::query();

        $query->orderBy($this->orderBy, $this->orderDirection);

        if ($this->enabled > -1) {
            $query->where('enabled', $this->enabled);
        }

        if ($this->search) {
            $query
            ->where('name', 'like', '%' . $this->search . '%')
            ->orWhere('email', 'like', '%' . $this->search . '%');
        }

        return $query;
    }

    public function export()
    {
        $results = collect($this->getQuery()->paginate($this->perPage)->items());

        $export = new ExportUsers($results);

        return Excel::download($export, 'Usuarios.xlsx');
    }
}
