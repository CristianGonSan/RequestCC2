<?php

namespace App\Livewire\Admin\Type;

use Livewire\Component;
use Livewire\WithPagination;

class ShowType extends Component
{
    use WithPagination;

    public $type;

    public $key;
    public $name;
    public $description;

    public function mount($type) {
        $this->type = $type;

        $this->key = $type->key;
        $this->name = $type->name;
        $this->description = $type->description;
    }

    public function render()
    {
        return view('livewire.admin.type.show-type', [
            'users' => $this->type->users()->orderBy('id', 'desc')->paginate(12, ['*'], 'usersPage'),
            'requests' => $this->type->requests()->orderBy('id', 'desc')->paginate(12, ['id', 'amount', 'concept', 'status'], 'requestPage')
        ]);
    }

    public function save()
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'key' => ['required', 'string', 'max:255', 'unique:types,key,' . $this->type->id],
            'description' => ['nullable']
        ]);

        $this->type->update($validated);

        session()->flash('message', 'Actualizado.');
    }

    public function disable() {
        $this->type->enabled = false;
        $this->type->save();
    }

    public function enable() {
        $this->type->enabled = true;
        $this->type->save();
    }

    public function delete() {
        $this->type->delete();

        session()->flash('success', 'Tipo Eliminado Exitosamente.');
        $this->redirect(route('admin.types.index'));
    }
}
