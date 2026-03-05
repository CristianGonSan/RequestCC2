<?php

namespace App\Livewire\Admin\Role;

use Livewire\Component;

class ShowRole extends Component
{
    public $role;

    public $name;

    public function mount($role) {
        $this->role = $role;
        $this->name = $role->name;
    }

    public function render()
    {
        return view('livewire.admin.role.show-role', [
            'users' => $this->role->users()->orderBy('id', 'desc')->paginate(12, ['*'], 'usersPage'),
        ]);
    }

    public function save()
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'min:3', 'max:255', 'unique:roles,name,' . $this->role->id]
        ]);

        $this->role->update($validated);

        session()->flash('message', 'Actualizado.');
    }

    public function delete() {
        $this->role->delete();

        session()->flash('success', 'Rol Eliminado Exitosamente.');
        $this->redirect(route('admin.roles.index'));
    }
}
