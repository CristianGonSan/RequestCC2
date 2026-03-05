<?php

namespace App\Livewire\Admin\User;

use App\Models\RequestModel;
use Hash;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class ShowAccount extends Component
{
    use WithPagination;

    public $user;
    public $name;
    public $email;

    public $password;
    public $password_confirmation;

    public $paidRequestCount = 0;
    public $paidAmountSum = 0;

    public function mount($user)
    {
        $this->user = $user;
        $this->name = $this->user->name;
        $this->email = $this->user->email;

        $requestPaid = $this->user->requests()->where('status', RequestModel::STATUS_PAID);

        $this->paidRequestCount = $requestPaid->count();
        $this->paidAmountSum = $requestPaid->sum('amount');
    }

    public function render()
    {
        return view('livewire.admin.user.show-account', [
            'requests' => $this->user->requests()->orderBy('id', 'desc')
            ->paginate(12, ['id', 'amount', 'concept', 'status'], 'requestPage')
        ]);
    }

    public function save()
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $this->user->id],
        ]);

        $this->user->update($validated);

        session()->flash('message', 'Actualizado.');
    }

    public function changePassword()
    {
        $this->validate([
            'password' => 'required|min:8|confirmed',
        ]);

        $this->user->update([
            'password' => Hash::make($this->password),
        ]);

        session()->flash('password_message', 'Guardado.');
        $this->reset(['password', 'password_confirmation']);
    }

    #[On('disableAccount')]
    public function disableAccount() {
        $this->user->enabled = false;
        $this->user->save();
        $this->dispatch('accountDisabled');
    }

    #[On('enableAccount')]
    public function enableAccount() {
        $this->user->enabled = true;
        $this->user->save();
        $this->dispatch('accountEnabled');
    }

    #[On('deleteAccount')]
    public function delete() {
        $this->user->delete();
        session()->flash('success', 'Usuario Eliminado Exitosamente.');
        $this->redirect(route('admin.users.index'));
    }
}
