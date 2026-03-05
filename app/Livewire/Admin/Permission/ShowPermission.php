<?php

namespace App\Livewire\Admin\Permission;

use Livewire\Component;

class ShowPermission extends Component
{
    public $permission;
    public $name;

    public function mount($permission) {
        $this->permission = $permission;
        $this->name = $permission->name;
    }

    public function render()
    {
        return view('livewire.admin.permission.show-permission', [
            'roles' => $this->permission->roles()->orderBy('name')->paginate(12, ['*'], 'rolesPage')
        ]);
    }

    public function save()
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'min:3', 'max:255', 'unique:permissions,name,' . $this->permission->id]
        ]);

        $this->permission->update($validated);

        session()->flash('message', 'Actualizado.');
    }

    public function delete() {
        $this->permission->delete();

        session()->flash('success', 'Permiso Eliminado Exitosamente.');
        $this->redirect(route('admin.permissions.index'));
    }
}
