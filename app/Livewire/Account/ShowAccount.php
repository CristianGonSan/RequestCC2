<?php

namespace App\Livewire\Account;

use App\Models\RequestModel;

use Hash;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ShowAccount extends Component
{
    public $user;
    public $name;
    public $email;

    public $current_password;
    public $password;
    public $password_confirmation;

    public $paidRequestCount;
    public $paidAmountSum;

    public function mount()
    {
        $this->user = Auth::user();
        $this->name = $this->user->name;
        $this->email = $this->user->email;

        $requestPaid = $this->user->requests()->where('status', RequestModel::STATUS_PAID);

        $this->paidRequestCount = $requestPaid->count();
        $this->paidAmountSum = $requestPaid->sum('amount');
    }

    public function render()
    {
        return view('livewire.account.show-account', [
            'types' => $this->user->types()->orderBy('id', 'desc')->get(),
            'companies' => $this->user->companies()->orderBy('id', 'desc')->get(),
            'roles' => $this->user->getRoleNames()
        ]);
    }

    public function save()
    {
        $this->validate([
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $this->user->id],
        ]);

        $this->user->update([
            'name' => $this->name,
            'email' => $this->email,
        ]);

        session()->flash('message', 'Actualizado.');
    }

    public function changePassword()
    {
        $this->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        if (!Hash::check($this->current_password, $this->user->password)) {
            $this->addError('current_password', 'La contraseña actual es icorrecta.');
            return;
        }

        $this->user->update([
            'password' => Hash::make($this->password),
        ]);

        session()->flash('password_message', 'Actualizado.');
        $this->reset(['current_password', 'password', 'password_confirmation']);
    }

    public function disableAccount()
    {
        $this->user->enabled = false;
        $this->user->save();

        $this->redirect('/#');
    }
}
