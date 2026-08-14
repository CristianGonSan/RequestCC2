<?php

namespace App\Livewire\Account;

use App\Traits\SweetAlert2\Livewire\Toast;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Livewire\Component;

class ChangePassword extends Component
{
    use Toast;

    public string $current_password;

    public string $password;

    public string $password_confirmation;

    public function render(): View
    {
        return view('livewire.account.change-password');
    }

    public function update(): void
    {
        $this->validate([
            'current_password'  => ['required', 'min:8', 'max:64'],
            'password'          => ['required', 'min:8', 'max:64', 'confirmed'],
        ]);

        $user = Auth::user();

        if (Hash::check($this->current_password, $user->password)) {
            $user->update([
                'password' => Hash::make($this->password),
            ]);

            $this->toastSuccess('Contraseña Actualizada');
            $this->resetErrorBag();
            $this->reset();
        } else {
            $this->addError('current_password', 'Contraseña incorrecta');
        }
    }
}
