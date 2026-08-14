<?php

namespace App\Livewire\Catalogs\Types;

use App\Models\Type;
use App\Traits\SweetAlert2\FlashToast;
use App\Traits\SweetAlert2\Livewire\Toast;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Component;

class TypeCreate extends Component
{
    use FlashToast, Toast;

    public string $name = '';

    public bool $createAnother = false;

    public function render(): View
    {
        return view('livewire.catalogs.types.type-create');
    }

    public function save(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:64', Rule::unique('types')],
        ]);

        $type = Type::create($validated);

        if ($this->createAnother) {
            $this->reset([
                'name',
            ]);

            $this->dispatch('reset');
            $this->toastSuccess('Tipo creado');
        } else {
            $this->flashToastSuccess('Tipo creado');
            redirect()->route('types.show', $type->id);
        }
    }
}
