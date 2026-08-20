<?php

namespace App\Livewire\Catalogs\Types;

use App\Models\Catalogs\Type;
use App\Traits\SweetAlert2\FlashToast;
use App\Traits\SweetAlert2\Livewire\Toast;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class TypeEdit extends Component
{
    use FlashToast, Toast;

    #[Locked]
    public int $typeId;

    public string $name;

    public function mount(int $typeId): void
    {
        $this->typeId   = $typeId;
        $type           = $this->type();

        $this->name     = $type->name;
    }

    public function render(): View
    {
        return view('livewire.catalogs.types.type-edit');
    }

    public function save(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:64', Rule::unique('types')->ignore($this->typeId)],
        ]);

        $this->type()->update($validated);

        $this->toastSuccess('Información actualizada');
    }

    private ?Type $type = null;

    private function type(): Type
    {
        return $this->type ??= Type::findOrFail($this->typeId);
    }
}
