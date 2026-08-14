<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use App\Traits\SweetAlert2\FlashToast;
use App\Traits\SweetAlert2\Livewire\Toast;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class UserCreate extends Component
{
    use Toast, FlashToast;

    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    public array $selectedRoles = [];

    public bool $createAnother = false;

    public function render(): View
    {
        $roles = Role::orderBy('name', 'desc')
            ->pluck('name', 'name')->toArray();

        return view('livewire.admin.users.user-create', [
            'roles' => $roles
        ]);
    }

    public function save(): void
    {
        $validated = $this->validate([
            'name'          => ['required', 'string', 'max:128', Rule::unique('users')],
            'email'         => ['required', 'email', 'max:255', Rule::unique('users')],
            'password'      => ['required', 'min:8', 'max:64', 'confirmed'],
            'selectedRoles' => ['nullable', 'array']
        ]);

        $validated['password'] = bcrypt($validated['password']);

        $user = User::create($validated);

        $user->syncRoles($validated['selectedRoles'] ?? []);

        if ($this->createAnother) {
            $this->reset([
                'name',
                'email',
                'password',
                'password_confirmation',
                'selectedRoles'
            ]);

            $this->dispatch('reset');
            $this->toastSuccess('Usuario creado');
        } else {
            $this->flashToastSuccess('Usuario creado');
            redirect()->route('users.show', $user->id);
        }
    }
}
