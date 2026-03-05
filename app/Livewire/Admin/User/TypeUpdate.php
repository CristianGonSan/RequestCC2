<?php

namespace App\Livewire\Admin\User;

use App\Models\Type;
use Livewire\Component;

class TypeUpdate extends Component
{
    public $user;

    public $types = [];
    public $selectedTypes = [];

    public function mount($user)
    {
        $this->user = $user;

        $this->types = Type::where('enabled', true)->orderBy('key')->get();

        foreach ($this->user->types as $type) {
            $this->selectedTypes[$type->id] = true;
        }
    }

    public function render()
    {
        return view('livewire.admin.user.type-update');
    }

    public function save()
    {
        $keys = [];

        foreach ($this->selectedTypes as $key => $boolean) {
            if ($boolean) {
                $keys[] = $key;
            }
        }

        $this->user->types()->sync($keys);

        session()->flash('type_message', 'Actualizado.');
    }
}
