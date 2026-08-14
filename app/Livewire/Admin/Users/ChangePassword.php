<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use App\Traits\SweetAlert2\Livewire\Toast;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class ChangePassword extends Component
{
    use Toast;

    #[Locked]
    public int $userId;

    public $current_password;
    public $password;
    public $password_confirmation;

    public function mount(int $userId): void
    {
        $this->userId = $userId;
    }

    public function render(): View
    {
        return view('livewire.admin.users.change-password');
    }

    public function save(): void
    {
        $this->validate([
            'password' => ['required', 'min:8', 'max:64', 'confirmed'],
        ]);

        $user = $this->user();

        $user->update([
            'password' => bcrypt($this->password),
        ]);

        $this->toastSuccess('Contraseña Actualizada');

        $this->reset(['password', 'password_confirmation']);
    }

    private ?User $user = null;

    private function user(): User
    {
        return $this->user ??= User::findOrFail($this->userId);
    }
}
