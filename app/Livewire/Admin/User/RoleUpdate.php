<?php

namespace App\Livewire\Admin\User;

use Livewire\Component;
use Spatie\Permission\Models\Role;

class RoleUpdate extends Component
{
    public $user;

    public $roles = [];
    public $selectedRoles = [];

    public function mount($user) {
        $this->user = $user;

        $this->roles = Role::orderBy('name')->get()->map(function ($item) {
            return $item->name;
        });

        foreach ($this->user->getRoleNames() as $role) {
            $this->selectedRoles[$role] = true;
        }
    }

    public function render()
    {
        return view('livewire.admin.user.role-update');
    }

    public function save() {
        $keys = [];

        foreach ($this->selectedRoles as $key => $boolean) {
            if ($boolean) {
                $keys[] = $key;
            }
        }

        $this->user->syncRoles($keys);

        session()->flash('role_message', 'Actualizado.');
    }
}
