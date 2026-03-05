<?php

namespace App\Livewire\Admin\Role;

use Livewire\Component;
use Spatie\Permission\Models\Permission;

class PermissionUpdate extends Component
{
    public $role;

    public $permissions = [];
    public $selectedPermissions = [];

    public function mount($role) {
        $this->role = $role;

        $this->permissions = Permission::orderBy('name')->get()->map(function ($item) {
            return $item->name;
        });

        foreach ($this->role->permissions as $permission) {
            $this->selectedPermissions[$permission->name] = true;
        }
    }

    public function render()
    {
        return view('livewire.admin.role.permission-update');
    }

    public function save() {
        $keys = [];

        foreach ($this->selectedPermissions as $key => $boolean) {
            if ($boolean) {
                $keys[] = $key;
            }
        }

        $this->role->syncPermissions($keys);

        session()->flash('permission_message', 'Actualizado.');
    }
}
